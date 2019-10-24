<?php
/*
  Plugin Name: Local Authority Members
  Description: A plugin to show and sync local authority members
  Version: 1.0.0
  Author: Software Design Ltd.
  Author URI: http://www.softwaredesign.ie
 */

if (!class_exists('SD_LocalAuthorityMembers')) {
    global $SD_LocalAuthorityMembers_db_version;
    $SD_LocalAuthorityMembers_db_version = '1.3';

    class SD_LocalAuthorityMembers
    {

        /**
         * @var string
         */
        private $plugin_dir;

        /**
         * @var wpdb
         */
        private $wpdb;

        /**
         * @var string
         */
        private $SD_LocalAuthorityMembers_table_name;


        /**
         * SD_LocalAuthorityMembers constructor.
         *
         */
        public function __construct()
        {
            $this->plugin_dir = plugin_dir_path(__FILE__);

            global $wpdb;
            $this->wpdb = $wpdb;

            $this->SD_LocalAuthorityMembers_table_name = $this->wpdb->prefix . 'SD_LocalAuthorityMembers';
        }

        // processes the WP query results into a format that dataTables can use, register dataTables and render!
        private static function processResults($results)
        {

            self::enqueue_scripts();

            if ($results === null) {
                return "<script> var tableData = []</script>";
            }

            if (count($results) == 0) {
                return "<script> var tableData = []</script>";
            }
            $resultsArray = array();

            // loop through the array to get the data into a format that datatables accepts...
            foreach ($results as $value) {
                $itemArray = array();
                array_push($itemArray, $value->LocalAuthority);
                array_push($itemArray, $value->Operator);
                array_push($itemArray, $value->MemberNumber);
                array_push($itemArray, $value->UniqueID);
                array_push($itemArray, $value->RegisteredName);
                array_push($itemArray, $value->TradingAs);
                array_push($itemArray, $value->Address1);
                array_push($itemArray, $value->Address2);
                array_push($itemArray, $value->Address3);
                array_push($itemArray, $value->Town);
                array_push($itemArray, $value->County);
                array_push($itemArray, $value->PremisesType);
                array_push($itemArray, $value->Status);
                array_push($resultsArray, $itemArray);
            }


            $script = "<script> var tableData = " . json_encode($resultsArray) . "</script>";

            ob_start();
            include "template.html.php";
            $output = ob_get_clean();
            $output .= $script;

            return $output;

        }

        // [allMembers_AsJson]
        // get all local authority members as json
        // allMembers_AsJson get_AllMembersAsJSON
        public function get_allmembersasjson()
        {

            global $wpdb;

            $table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers';
            $stm = "SELECT * FROM " . $table_name . " where MemberNumber > 0 and Operator <> 'collector'";
            $results = $wpdb->get_results($stm);


            return SD_LocalAuthorityMembers::processResults($results);
        }

        // [collectors_asjson]
        // get all local authority members as json
        // collectors_asjson get_collectors_asjson
        public function get_collectorsasjson()
        {

            global $wpdb;

            $table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers';
            $stm = "SELECT * FROM " . $table_name . " where MemberNumber > 0 and Operator = 'collector'";
            $results = $wpdb->get_results($stm);


            $short_code_stream = SD_LocalAuthorityMembers::processResults($results);
            $this->override_operator_collection();

            return $short_code_stream;
        }

        private function override_operator_collection()
        {
            wp_enqueue_script('collector-script', plugins_url('js/load-collector.js', __FILE__), array('jquery'), '3', true);
        }

        private static function enqueue_scripts()
        {
            wp_enqueue_style('local-authority-style');
            wp_enqueue_style('data-table-style');
            wp_enqueue_style('data-table-buttons-style');

            wp_enqueue_script('data-table-script', '//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), '3', true);
            wp_enqueue_script('data-table-button', '//cdn.datatables.net/buttons/1.2.3/js/dataTables.buttons.min.js', array('data-table-script'), '3', true);
            wp_enqueue_script('data-table-print', '//cdn.datatables.net/buttons/1.2.3/js/buttons.print.min.js', array('data-table-script'), '3', true);
            wp_enqueue_script('data-table-html5', '//cdn.datatables.net/buttons/1.2.3/js/buttons.html5.min.js', array('data-table-script'), '3', true);
            wp_enqueue_script('data-table-flash', '//cdn.datatables.net/buttons/1.2.3/js/buttons.flash.min.js', array('data-table-script'), '3', true);
            wp_enqueue_script('data-table-pdf-make', '//repakelt.ie/wp-content/themes/cardinal/js/pdfmake.min.js', array('data-table-script'), '4', true);
            wp_enqueue_script('data-table-pdf-make-fonts', '//repakelt.ie/wp-content/themes/cardinal/js/vfs_fonts.js', array('data-table-script'), '4', true);
            wp_enqueue_script('data-table-jszip', '//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js', array('data-table-script'), '3', true);

            wp_enqueue_script('operators-script', plugins_url('js/load-operators.js', __FILE__), array('jquery'), '3', true);
            wp_enqueue_script('members-script', plugins_url('js/local-authority.js', __FILE__), array('jquery'), '3', true);
        }

        // [membersByLAName_AsJson]
        // $LAName is a string representing the local authority name.
        // membersByLAName_AsJson get_MembersByLAName_AsJson
        public function get_MembersByLAName_AsJson($atts)
        { //
            $LAName = $atts['laname'];

            global $wpdb;

            $table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers';
            $stm = $wpdb->prepare("SELECT * FROM " . $table_name . " where LocalAuthority = '%s' and MemberNumber > 0 and Operator <> '%s'", $LAName, $this->collector_name);
            $results = $wpdb->get_results($stm);

            return SD_LocalAuthorityMembers::processResults($results);
        }

        // OBSOLETE
        // [membersByLANum_AsJson]
        // $LANum is a int representing the local authority number.
        // membersByLANum_AsJson get_MembersByLANumber_AsJson
        public function get_MembersByLANumber_AsJson($atts)
        {

            $LANum = intval($atts['lanum']);

            if (!is_int($LANum) || ($LANum < 1)) {
                return "[]";
            } else {
                global $wpdb;

                $table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers';
                $stm = $wpdb->prepare("SELECT * FROM " . $table_name . " where Local_Authority_Number = %d and MemberNumber > 0", $LANum);
                $results = $wpdb->get_results($stm);

                return SD_LocalAuthorityMembers::processResults($results);
            }
        }

        // [parseAndInsertCSV]

        public static function do_parseAndInsertCSV()
        {
            $base = dirname(__FILE__) . '/log.txt';
            $logfile = fopen($base, 'a');
            fwrite($logfile, date('Y-m-d   H:i:s:   ') . 'function do_parseAndInsertCSV() called' . PHP_EOL);
            $upload_dir = wp_upload_dir();
            $CSV_PATH = $upload_dir['basedir'] . '/repak-list/';
            //'/wp-content/uploads/repak-list/';


            $csvfile = $CSV_PATH . "repak-local-authority-list.csv";
            global $wpdb;

            $sync_table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers_sync';
            fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . '$sync_table_name = ' . $sync_table_name);

            $display_table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers';

            fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . '$display_table_name = ' . $display_table_name);
            $temp_table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers_tmp';
            fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . '$temp_table_name = ' . $temp_table_name);

            if (file_exists($csvfile)) {
                $file = fopen($csvfile, "r");
                if (!$file) {
                    // echo "Error opening data file.\n";
                    fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . "Error opening data file.\n");
                    exit;
                }
                $size = filesize($csvfile);
                fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . '$size of CSV file is ' . $size);

                if (!$size) {
                    // echo "File is empty.\n";
                    fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . "File is empty.\n");
                    exit;
                }

                $deleteQuery = "delete from " . $sync_table_name . " where MemberNumber > -1";
                fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . '$deleteQuery = ' . $deleteQuery);

                $wpdb->query($deleteQuery);

                //if ($wpdb->query($wpdb->prepare($deleteQuery))) {
                $query = "INSERT INTO " . $sync_table_name . "("
                    . "LocalAuthority"
                    . ",MemberNumber"
                    . ",UniqueID"
                    . ",RegisteredName"
                    . ",TradingAs"
                    . ",Address1"
                    . ",Address2"
                    . ",Address3"
                    . ",Town"
                    . ",County"
                    . ",PremisesType"
                    . ",Status"
                    . ") VALUES";

                $lotCounter = 0;

                while (!feof($file)) {
                    $line_of_text = fgetcsv($file, 1024);
                    if ($lotCounter > 0) {

                        try {
                            $values = "("
                                . "'" . str_replace("'", "\'", trim($line_of_text[0])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[1])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[2])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[3])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[4])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[5])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[6])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[7])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[8])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[9])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[11])) . "'"
                                . ",'" . str_replace("'", "\'", trim($line_of_text[10])) . "'"
                                . ")";
                        } catch (Exception $ex) {
                            // echo "cannot insert new data (1).\n";

                            fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . "cannot insert new data (1).\n");
                            exit;
                        }

                        if (!empty($values)) {
                            // fix he query
                            $values = str_replace(")(", "),(", $values);
                            fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . '$query = ' . $query . $values);

                            if ($wpdb->query($query . $values)) {

                                fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . "updated data.\n");
                                //echo "updated data.\n";
                            } else {
                                // echo sprintf( "cannot insert line %d (%s) (2).\n", $lotCounter + 1, trim( $line_of_text[0] ) );
                            }
                            //} else {
                            //        echo "cannot delete old data.\n";
                            //        exit;
                            //   }
                        }
                    }
                    $lotCounter++;
                }
                fclose($file);

                $wpdb->prefix;

                $renameQuery = "RENAME TABLE
                  $display_table_name TO $temp_table_name,
                  $sync_table_name TO $display_table_name,
                  $temp_table_name TO $sync_table_name;";
                fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . ' $renameQuery = ' . $renameQuery);
                $wpdb->query($renameQuery);

            } else {
                fwrite($logfile, PHP_EOL . date('Y-m-d   H:i:s:   ') . "file cannot be found.\n");
                // echo "file cannot be found.\n";
                exit;
            }
            fclose($logfile);
        }

        public static function activate()
        {
            /**
             * @var wpdb $wpdb
             */
            global $SD_LocalAuthorityMembers_db_version;

            //// cron timestamp
            $timestamp = wp_next_scheduled('sd_syncCSV');

            if ($timestamp == false) {
                //Schedule the event for right now, then to repeat daily using the hook 'wi_create_daily_backup'
                wp_schedule_event(time(), '15_past_the_hour', 'sd_syncCSV');
            }

            function createTable($name)
            {
                global $wpdb;

                $charset_collate = $wpdb->get_charset_collate();

                $table_name = $wpdb->prefix . $name;

                $sql = "CREATE TABLE $table_name (
                                  LocalAuthority varchar(255) NOT NULL,
                                  Operator varchar(255) DEFAULT '',
                  MemberNumber mediumint(9) NOT NULL,
                                  UniqueID mediumint(9) NOT NULL,
                  RegisteredName varchar(255) NOT NULL,
                  TradingAs varchar(255),
                                  Address1 varchar(255),
                                  Address2 varchar(255),
                                  Address3 varchar(255),
                                  Town varchar(255),
                                  County varchar(255),
                                  PremisesType varchar(255),
                                  Status varchar(255)
              ) $charset_collate;";

                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }

            createTable('SD_LocalAuthorityMembers');
            createTable('SD_LocalAuthorityMembers_sync');

            add_option('SD_LocalAuthorityMembers_db_version', $SD_LocalAuthorityMembers_db_version);
        }

        public static function sd_add_our_schedule($schedules)
        {
            $schedules['minuteByMinute'] = array(
                'interval' => 60, //7 days * 24 hours * 60 minutes * 60 seconds
                'display' => __('Every Minute', 'my-plugin-domain?')
            );
            $schedules['5_past_the_hour'] = array(
                'interval' => 65 * 60, //7 days * 24 hours * 60 minutes * 60 seconds
                'display' => __('5 past the hour', 'my-plugin-domain?')
            );
            $schedules['15_past_the_hour'] = array(
                'interval' => 75 * 60, //7 days * 24 hours * 60 minutes * 60 seconds
                'display' => __('15 past the hour', 'my-plugin-domain?')
            );

            return $schedules;
        }

        public static function SD_LocalAuthorityMembers_register_scripts()
        {
            wp_register_style('local-authority-style', plugins_url('css/style.css', __FILE__));
            wp_register_style('data-table-style', '//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
            wp_register_style('data-table-buttons-style', '//cdn.datatables.net/buttons/1.2.3/css/buttons.dataTables.min.css');
        }

        public static function deactivate()
        {

            global $wpdb;

            $table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers';
            $sql = "DROP TABLE IF EXISTS $table_name;";
            $wpdb->query($sql);

            $table_name = $wpdb->prefix . 'SD_LocalAuthorityMembers_sync';
            $sql = "DROP TABLE IF EXISTS $table_name;";
            $wpdb->query($sql);

            wp_clear_scheduled_hook('sd_syncCSV');

            remove_shortcode('membersByLANum_AsJson');
            remove_shortcode('membersByLAName_AsJson');
            remove_shortcode('allmembers_asjson');
            remove_shortcode('parseAndInsertCSV');
        }
    }
}

if (class_exists('SD_LocalAuthorityMembers')) {
    add_filter('cron_schedules', array('SD_LocalAuthorityMembers', 'sd_add_our_schedule'));

    register_activation_hook(__FILE__, array('SD_LocalAuthorityMembers', 'activate'));
    register_deactivation_hook(__FILE__, array('SD_LocalAuthorityMembers', 'deactivate'));

    add_action('sd_syncCSV', array('SD_LocalAuthorityMembers', 'do_parseAndInsertCSV'));

    $SD_LocalAuthorityMembers = new SD_LocalAuthorityMembers();

    add_shortcode('membersByLANum_AsJson', array($SD_LocalAuthorityMembers, 'get_MembersByLANumber_AsJson'));
    add_shortcode('membersByLAName_AsJson', array($SD_LocalAuthorityMembers, 'get_MembersByLAName_AsJson'));
    add_shortcode('allmembers_asjson', array($SD_LocalAuthorityMembers, 'get_allmembersasjson'));
    add_shortcode('collectors_asjson', array($SD_LocalAuthorityMembers, 'get_collectorsasjson'));
    add_shortcode('parseAndInsertCSV', array('SD_LocalAuthorityMembers', 'do_parseAndInsertCSV'));

    add_action('wp_enqueue_scripts', array(
        'SD_LocalAuthorityMembers',
        'SD_LocalAuthorityMembers_register_scripts'
    ));

    //injection start
    add_action('admin_menu', 'simple_voting_admin_menu');
    function simple_voting_admin_menu()
    {
        add_menu_page('Запустить импорт', 'Запустить импорт', 'manage_options', 'simple-voting-admin', 'run_import', 'dashicons-edit');
    }

    function run_import()
    {
        SD_LocalAuthorityMembers::do_parseAndInsertCSV();
    }
    //injection finish
}
