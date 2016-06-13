<?php
/*
 *  functions.php
 *  -------------
 *  PHP: contains the core functions for the theme
 */

/*
 *  Constants
 * 	---------
 */

define("CONTENT_WIDTH", 860);
define("FB_ACCESS_TOKEN", "EAAPJBDecPHABAC1cBOmfPZATdb87Gr38lexC2JEFanlZARB3SvtYjxpBWKnQW0DbrVZCz2bZBCbhnBHeISTaG9iVQ2wgN5fVUsJ3ZABDQW3HE2U4RAeBzfj7eDfooJUk7R0ePjK15vfZCCIyri3gTfpPXPx1e0PpiReBqC4EEIthQZC4jvVfSnb");


/*
 *	Functions
 *	---------
 */

/*
 *	Sets up theme defaults and registers support for various WordPress features.
 *
 *	Note that this function is hooked into the after_setup_theme hook, which
 *	runs before the init hook. The init hook is too late for some features, such
 *	as indicating support for post thumbnails.
 */
function yumc_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Don't hardcode title tag, let WordPress provide it
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Setup the main nev menu
	register_nav_menus( array(
		'primary' => 'Primary',
	) );

	// Setup theme as HTML5
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Setup post format support
	// To-do: choose which post formats to add
	add_theme_support( 'post-formats', array(
		'image',
		'video',
		'link',
	) );
}
add_action( 'after_setup_theme', 'yumc_setup' );

/**
 * Sets the content width in pixels.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function yumc_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'yumc_content_width', CONTENT_WIDTH );
}
add_action( 'after_setup_theme', 'yumc_content_width', 0 );

/*
 *  Register widget areas
 *
 * 	To-do: this
 */
function yumc_widgets_init() {
	register_sidebar( array(
		'name'          => 'Front Page',
		'id'            => 'front-page',
		'description'   => 'Designed for event calendar and social media updates, so avoid putting something else here',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'yumc_widgets_init' );

/*
 *	Enqueue java scripts and styles.
 */
function yumc_scripts() {
	// Main stylesheet
	wp_enqueue_style( 'yumc-style', get_stylesheet_uri() );
	//Grid CSS
	wp_enqueue_style( 'yumc-grid', get_template_directory_uri() . '/stylesheets/grid.css' );

	// Small screen nav menu, for when the theme is made responsive
	wp_enqueue_script( 'yumc-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'yumc_scripts' );

/*
 *  Adds post thumbnail sizes
 */
function yumc_image_sizes() {
	add_image_size( '380-thumb', 380 ); // 380 pixels wide (and unlimited height)
	add_image_size( '380-253-thumb', 380, 253, true ); // Cropped 380 x 253
}
add_action( 'after_setup_theme', 'yumc_image_sizes' );

function parse_fb_timestamp($fb_timestamp){
	return new DateTime($fb_timestamp);
}

/*
 *  Shortens the excerpt to a given length
 */
function yumc_the_excerpt( $length ) {
	$string = get_the_excerpt();
	if (strlen( $string ) > $length )
	{
		$string = wordwrap( $string, $length );
		$string = substr( $string, 0, strpos( $string, "\n" ) );
	}
	echo $string . ' [...]';
}

/*
 *	Does a JSON Get request to get facebook events from the YUMC page
 */
function get_facebook_events() {
	$fb_events_json = file_get_contents("https://graph.facebook.com/ClimbingYork/events?access_token=" . FB_ACCESS_TOKEN);
	return json_decode($fb_events_json, true)["data"];
}

/*
 *	Implement the Custom Header feature.
 * 	_s theme file.
 */
require get_template_directory() . '/inc/custom-header.php';

/*
 *	Custom template tags for this theme.
 * 	_s theme file.
 */
require get_template_directory() . '/inc/template-tags.php';

/*
 *	"Customizer" additions.
 * 	_s theme file.
 */
require get_template_directory() . '/inc/customizer.php';