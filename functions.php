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
 *	Classes
 */
require_once get_template_directory() . '/inc/custom-metabox.php';

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

function yumc_parse_fb_timestamp( $fb_timestamp ){
	return new DateTime($fb_timestamp);
}

function yumc_parse_event_timestamp( $yumc_event_timestamp ){
	return DateTime::createFromFormat( 'U', $yumc_event_timestamp );
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
function yumc_get_facebook_events( $num_events = null ) {
	if( $num_events )
		$fb_events_json = file_get_contents("https://graph.facebook.com/ClimbingYork/events?access_token=" . FB_ACCESS_TOKEN);
	else
		$fb_events_json = file_get_contents("https://graph.facebook.com/ClimbingYork/events?access_token=" . FB_ACCESS_TOKEN . "&limit=" . $num_events);
	return json_decode($fb_events_json, true)["data"];
}

/*
 *	Creates all custom post types for the theme. These are:
 * 		-	Events post type
 */
function yumc_register_post_types() {
	$labels = array(
		'name'                  => 'Events',
		'singular_name'         => 'Event',
		'menu_name'             => 'Events',
		'name_admin_bar'        => 'Events',
		'add_new'               => 'Add New',
		'add_new_item'          => 'Add New Event',
		'new_item'              => 'New Event',
		'edit_item'             => 'Edit Event',
		'view_item'             => 'View Event',
		'all_items'             => 'All Events',
		'search_items'          => 'Search Events',
		'parent_item_colon'     => 'Parennt Event:',
		'not_found'             => 'No events found.',
		'not_found_in_trash'    => 'No events found in the Bin.',
		'featured_image'        => 'Event Image',
		'set_featured_image'    => 'Set event image',
		'remove_featured_image' => 'Remove event image',
		'use_featured_image'    => 'Use as event image',
		'archives'              => 'Event archives',
		'insert_into_item'      => 'Insert into event',
		'uploaded_to_this_item' => 'Uploaded to this event',
		'filter_items_list'     => 'Filter events list',
		'items_list_navigation' => 'Events list navigation',
		'items_list'            => 'Events list',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'event' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'thumbnail', 'editor' ),
		'menu_icon' 		 => 'dashicons-calendar',
		'menu_position'		 => 20,
	);

	register_post_type( 'yumc_events', $args );
}
add_action( 'init', 'yumc_register_post_types' );

function yumc_setup_events_post_type() {
	$event_date_mb = new Custom_Metabox('yumc-event-date', 'Event Date/Time', 'yumc_events');
	$event_date_mb->fields = array(
		'yumc_event_date',
		'yumc_event_time',
		'yumc_event_unix_timestamp',
	);
	$event_date_mb->html_content =
		array (
			array(
				'field_id'	=> 'yumc_event_date',
				'before'	=> '<p><label for="yumc_event_date">Date:</label>&emsp;<input type="text" id="yumc_event_date" name="yumc_event_date" value="',
				'after'		=> '"></p>'
			),
			array(
				'field_id'	=> 'yumc_event_time',
				'before'	=> '<p><label for="yumc_event_time">Time:</label>&emsp;<input type="text" id="yumc_event_time" name="yumc_event_time" value="',
				'after'		=> '"></p>'
			),
			array(
				'field_id'	=> 'yumc_event_unix_timestamp',
				'before'	=> '<p><label for="yumc_event_time">Unix Timestamp:</label>&emsp;<input type="text" readonly style="id="yumc_event_unix_timestamp" name="yumc_event_unix_timestamp" value="',
				'after'		=> '"></p>'
			),
		);
	$event_date_mb->init();
}
add_action( 'admin_init', 'yumc_setup_events_post_type' );

/*
 *	Changes login logo
 */
function yumc_login_logo() { ?>
	<style type="text/css">
		.login #login {
			width: 580px;
		}
		#login h1 {
			float: left;
			margin-right: 40px;
		}
		#login p#nav {
			clear: both;
			text-align: right;
		}
		#login p#backtoblog {
			display: none;
		}

		#login h1 a, .login h1 a {
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/yumc-logo.png);
			height: 300px;
			width: 220px;
			-webkit-background-size: 220px;
			background-size: 220px;
			margin: 0;
		}
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'yumc_login_logo' );

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