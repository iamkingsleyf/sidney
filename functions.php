<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'sidney', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/lib/languages', 'sidney' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Sidney Theme', 'sidney' ) );
define( 'CHILD_THEME_URL', 'http://wpcanada.ca/our-themes/sidney' );
define( 'CHILD_THEME_VERSION', '1.0.1' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Javascript files
add_action( 'wp_enqueue_scripts', 'sidney_enqueue_scripts' );
function sidney_enqueue_scripts() {

	wp_enqueue_script( 'sidney-responsive-menu', get_stylesheet_directory_uri() . '/lib/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );

}

//* Enqueue CSS files
add_action( 'wp_enqueue_scripts', 'sidney_enqueue_styles' );
function sidney_enqueue_styles() {

	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=PT+Sans:400,700|Merriweather:400,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'sidney-genericons-style', get_stylesheet_directory_uri() . '/lib/font/genericons.css' );
	wp_enqueue_style( 'dashicons' );

}

//* Add new image sizes
add_image_size( 'mini-square', 80, 80, TRUE );
add_image_size( 'medium-square', 120, 120, TRUE );
add_image_size( 'large-square', 150, 150, TRUE );
add_image_size( 'featured', 280, 100, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add the welcome text section to front page
add_action( 'genesis_before_loop', 'sidney_welcome_text' );
function sidney_welcome_text() {

	if ( ! is_front_page() || get_query_var( 'paged' ) >= 2 )
		return;

	genesis_widget_area( 'welcome_text', array(
		'before' => '<div class="welcome-text widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

//* Add before entry widget area to single post page
add_action( 'genesis_before_entry', 'sidney_before_entry' );
function sidney_before_entry() {

	if ( ! is_singular( 'post' ) )
		return;

	genesis_widget_area( 'before-entry', array(
		'before' => '<div class="before-entry widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Add single post navigation
add_action( 'genesis_after_entry', 'genesis_prev_next_post_nav', 9 );

//* Customize the post info function
add_filter( 'genesis_post_info', 'sidney_post_info_filter' );
function sidney_post_info_filter( $post_info ) {

	if ( ! is_page() ) {

	$post_info = '[post_date] [post_author_link] [post_comments] [post_edit]';
	return $post_info;

	}

}

//* Modify comment form
add_filter( 'comment_form_defaults', 'sidney_comment_form_defaults' );
function sidney_comment_form_defaults( $defaults ) {

	$defaults['title_reply'] = __( 'Join the Discussion!' );
	$defaults['comment_notes_before'] = '<p class="comment-box">' . __( 'Please submit your comment with a real name.' );
	$defaults['comment_notes_after'] = '<p class="comment-box">' . __( 'Thanks for your feedback!' );
	return $defaults;

}

//* Customize search form placeholder text
add_filter( 'genesis_search_text', 'sidney_search_text' );
function sidney_search_text( $text ) {
	return esc_attr( 'Search this website...' );
}

// Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

add_filter( 'genesis_post_date_shortcode', 'sidney_post_date_shortcode', 10, 2 );
/**
 * Customize Post Date format and add extra markup for CSS targeting.
 * 
 * @author Gary Jones
 * @link   http://code.garyjones.co.uk/style-post-info/
 * 
 * @param string $output Current HTML markup.
 * @param array  $atts   Attributes.
 * 
 * @return string HTML markup.
 */
function sidney_post_date_shortcode( $output, $atts ) {

	return sprintf(
		'<span class="date time published" title="%4$s">%1$s<span class="month">%2$s</span> <span class="year">%3$s</span> </span>',
		$atts['label'],
		get_the_time( 'j M' ),
		get_the_time( 'Y' ),
		get_the_time( 'Y-m-d\TH:i:sO' )
	);

}

//* Include and hook tertiary navigation menu
require_once( CHILD_DIR . '/lib/includes/tertiary-navigation-menu.php' );
add_action( 'genesis_before_footer', 'genesis_do_subnavtwo', 5 );

//* Register Genesis Menus
add_theme_support( 'genesis-menus', array( 'primary'   => __( 'Primary Navigation Menu', 'genesis' ), 'secondary' => __( 'Secondary Navigation Menu', 'genesis' ), 'tertiary' => __( 'Tertiary Navigation Menu', 'genesis' ), ) );

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'welcome_text',
	'name'        => __( 'Welcome Text', 'sidney' ),
	'description' => __( 'This is the welcome text widget area.', 'sidney' ),
) );
genesis_register_sidebar( array(
	'id'          => 'before-entry',
	'name'        => __( 'Before Entry', 'sidney' ),
	'description' => __( 'Widgets in this widget area will display before single entries.', 'sidney' ),
) );
genesis_register_sidebar( array(
	'id'          => 'widget-page',
	'name'        => __( 'Widget Page', 'sidney' ),
	'description' => __( 'This is the Widget Page template.', 'sidney' ),
) );