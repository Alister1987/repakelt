<?php
/**
 * Plugin Name: SD PRI Announcement Banner
 * Plugin URI: http://softwaredesign.ie
 * Description: If enabled this plugin will show a popup on the banner of each page for PRI announcements
 * Version: 1.0.3
 * Author: Rob Gleeson
 * Author URI: http://softwaredesign.ie
 * License: GPL2
 */

add_action( 'wp_enqueue_scripts', 'sd_pri_announcement_scripts' );
add_filter('admin_init', 'sd_pri_settings_register_fields');

function sd_pri_settings_register_fields() {
    register_setting('general', 'pri_text', 'esc_attr');
    register_setting('general', 'pri_link', 'esc_attr');
    add_settings_field(
        'pri_text',
        '<label for="pri_text">'.__('PRI announcement text' , 'pri_text' ).'</label>' , 'sd_pri_text', 'general'
    );
    add_settings_field(
        'pri_link',
        '<label for="pri_link">'.__('PRI announcement link' , 'pri_link' ).'</label>' , 'sd_pri_link', 'general'
    );
}

function sd_pri_text() {
    $value = get_option( 'pri_text', '' );
    echo '<input type="text" id="pri_text" name="pri_text" value="' . $value . '" />';
}

function sd_pri_link() {
    $value = get_option( 'pri_link', '' );
    echo '<input type="text" id="pri_link" name="pri_link" value="' . $value . '" />';
}

function sd_pri_announcement_scripts() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'prefix-style', plugins_url('css/style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
}

function sd_pri_announcement_activate() {
    render_pri_message();
}
register_activation_hook( __FILE__, 'sd_pri_announcement_activate' );

add_action('wp_head', 'render_pri_message');
function render_pri_message(){
    $pri_text = get_option( 'pri_text', 'Register today for our Repak ELT - SIMI Road Show on 24th August' );
    $pri_link = '/'.get_option( 'pri_link', '' );
    $pri_icon = 'ss-alert';
    ?>
    <div id="pri-announcement-holder">
        <i class="<?php echo $pri_icon ?> sf-icon sf-icon-small"></i>
        <a href="<?php echo $pri_link ?>">
            <span id="pri-text"><?php echo $pri_text ?></span>
        </a>
    </div>
    <script>
        jQuery(document).ready(function(){
            function setBannerY(){
                var header = jQuery("#header-section");
                if( header.is(":visible") === false ){
                    header = jQuery("#mobile-header");
                    var offset = header.height() + header.offset().top + 60;
                } else {
                    var offset = header.height() + header.offset().top + 20;
                }
                jQuery("#pri-announcement-holder").css("top", offset).fadeIn();
            }

            function setBannerX(){
                var offset = ((window.innerWidth - jQuery("#pri-announcement-holder").width()) / 2) - 21;
                offset = offset < 0 ? 0 : offset;
                jQuery("#pri-announcement-holder").css('left', offset.toString() + 'px');
            }

            jQuery(window).resize(function(){
                setBannerY();
                setBannerX();
            });
        });
    </script>
<?php }
