<?php
/**
 * Plugin Name: SD Events Registration Popup
 * Plugin URI: http://softwaredesign.ie
 * Description: If enabled this plugin will show a popup on the homepage which links to the events application page
 * Version: 1.0.1
 * Author: Rob Gleeson
 * Author URI: http://softwaredesign.ie
 * License: GPL2
 */

add_action( 'wp_enqueue_scripts', 'sd_download_forms' );
add_filter('admin_init', 'my_general_settings_register_fields');

function my_general_settings_register_fields() {
    register_setting('general', 'download_cta_title', 'esc_attr');
    add_settings_field(
        'download_cta_title',
        '<label for="download_cta_title">'.__('Download CTA Text' , 'download_cta_title' ).'</label>' , 'sd_settings', 'general'
    );
}

function sd_settings() {
    $value = get_option( 'download_cta_title', '' );
    echo '<input type="text" id="download_cta_title" name="download_cta_title" value="' . $value . '" />';
}

function sd_download_forms() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'prefix-style', plugins_url('css/style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
    wp_enqueue_script('sd_download_forms_js', plugin_dir_url( __FILE__ ) . 'js/index.js', array('jquery'), '1.0', true);
}

// Make Download Forms page public when plugin activated
function sd_downloads_activate() {
    render_download_popup();
    toggle_download_forms('publish');
}
register_activation_hook( __FILE__, 'sd_downloads_activate' );

// Make Download Forms page private when plugin deactivated
function sd_downloads_deactivate() {
    toggle_download_forms('draft');
}
register_deactivation_hook( __FILE__, 'sd_downloads_deactivate' );

function toggle_download_forms($post_status){
    global $wpdb;
    $wsquery = array('post_status' => $post_status);
    $tid = array('post_name' => 'events-application');
    $tname = 'wp_posts';
    $wpdb->update( $tname, $wsquery , $tid);
}

add_action('wp_head', 'render_download_popup');
function render_download_popup(){
    $download_cta_title = get_option( 'download_cta_title', 'Register today for our Repak ELT - SIMI Road Show on 24th August' );
    if( is_home() || is_front_page()) { ?>
        <div id="download-forms-popup">
            <div id="download-forms-popup-body-wrap">
                <i class="ss-signpost sf-icon sf-icon-large"></i>
                <a href="/events-application">
                    <span id="download-forms-popup-msg"><?php echo $download_cta_title ?></span>
                </a>
            </div>
        </div>
    <?php }
}
