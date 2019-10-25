<?php

/*
*
*	Cardinal Functions - Child Theme
*	------------------------------------------------
*	These functions will override the parent theme
*	functions. We have provided some examples below.
*
*
*/

/* LOAD PARENT THEME STYLES
================================================== */
function cardinal_child_enqueue_styles() {
	wp_enqueue_style( 'cardinal-parent-style', get_template_directory_uri() . '/style.css' );

}

add_action( 'wp_enqueue_scripts', 'cardinal_child_enqueue_styles' );

/* LOAD THEME LANGUAGE
================================================== */
/*
*	You can uncomment the line below to include your own translations
*	into your child theme, simply create a "language" folder and add your po/mo files
*/

// load_theme_textdomain('swiftframework', get_stylesheet_directory().'/language');


/* REMOVE PAGE BUILDER ASSETS
================================================== */
/*
*	You can uncomment the line below to remove selected assets from the page builder
*/

// function spb_remove_assets( $pb_assets ) {
//     unset($pb_assets['parallax']);
//     return $pb_assets;
// }
// add_filter( 'spb_assets_filter', 'spb_remove_assets' );


/* ADD CHECK SCRIPT FOR IBAN AND BIC
================================================== */
get_template_part( 'functions-cpt', 'validation' );

/* ADD/EDIT PAGE BUILDER TEMPLATES
================================================== */
function custom_prebuilt_templates( $prebuilt_templates ) {

	/*
	*	You can uncomment the lines below to add custom templates
	*/
	// $prebuilt_templates["custom"] = array(
	// 	'id' => "custom",
	// 	'name' => 'Custom',
	// 	'code' => 'your-code-here'
	// );

	/*
	*	You can uncomment the lines below to remove default templates
	*/
	// unset($prebuilt_templates['home-1']);
	// unset($prebuilt_templates['home-2']);

	// return templates array
	return $prebuilt_templates;

}

//add_filter( 'spb_prebuilt_templates', 'custom_prebuilt_templates' );

/* Custom blog post search */
add_action( 'wp_head', 'remove_my_original_sf_page_heading' );
function remove_my_original_sf_page_heading() {
	remove_action( 'sf_main_container_start', 'sf_page_heading', 20 );
}

include get_stylesheet_directory() . '/swift-framework/core/sf-override-page-heading.php';


function sd_register_sidebars() {
	if ( function_exists( 'register_sidebar' ) ) {

		$current_theme = get_option( 'sf_theme' );

		$current_theme_opts = 'sf_' . $current_theme . '_options';

		$sf_options           = get_option( $current_theme_opts );
		$footer_config        = $sf_options['footer_layout'];
		$enable_global_banner = false;
		$gb_layout            = '';
		if ( isset( $sf_options['enable_global_banner'] ) ) {
			$enable_global_banner = $sf_options['enable_global_banner'];
			$gb_layout            = $sf_options['global_banner_layout'];
		}
		$sidebar_before_title = apply_filters( 'sf_sidebar_before_title', '<div class="widget-heading title-wrap clearfix"><h2 class="spb-heading"><span>' );
		$sidebar_after_title  = apply_filters( 'sf_sidebar_after_title', '</span></h2></div>' );
		$footer_before_title  = apply_filters( 'sf_footer_before_title', '<div class="widget-heading title-wrap clearfix"><h6>' );
		$footer_after_title   = apply_filters( 'sf_footer_after_title', '</h6></div>' );
		$gb_before_title      = apply_filters( 'sf_gb_before_title', '<div class="widget-heading title-wrap clearfix"><h6>' );
		$gb_after_title       = apply_filters( 'sf_gb_after_title', '</h6></div>' );


		register_sidebar( array(
			'name'          => 'News bottom widget',
			'id'            => 'news-bottom-widget',
			'description'   => 'This widget area will be displayed on the bottom of the news section',
			'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
			'after_widget'  => '</section>',
			'before_title'  => $sidebar_before_title,
			'after_title'   => $sidebar_after_title,
		) );
	}
}

add_action( 'widgets_init', 'sd_register_sidebars', 0 );

function sd_news_bottom_widget() {

	global $wp_query;

	if ( $wp_query->is_posts_page && is_active_sidebar( 'news-bottom-widget' )){

		include get_stylesheet_directory() . '/news-footer.php';
	}
}

add_action( 'sf_main_container_end', 'sd_news_bottom_widget', 18 );


?>