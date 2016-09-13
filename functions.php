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
define("TWITTER_API_KEY", "z6H7reVnqgExWjdZhRDK8lRon");
define("TWITTER_API_SECRET", "7whoDFWxKY8t5s3DHbOXmAB0Uyc16JBc1nmpMx5AL5JamiM6bJ");
define("TWITTER_ACCESS_TOKEN", "210615217-fsRwoB9RV2aU3WDXSL8S4tkEELZrwzsl22dpABbi");
define("TWITTER_ACCESS_SECRET", "R2s3Y3yayMElfhSNvb8h1wg1wk7a2fc3WMl3ZoQTkQnC2");
define("GOOGLE_CALENDAR_ID", "mountaineering@yusu.org");
define("GOOGLE_CALENDAR_KEY", "AIzaSyA_2MLeqEt0ry2OTjLKfHTYIiQ-zAd8H-Y");

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

	//jquery
	wp_enqueue_script( 'jquery' );

	// Small screen nav menu, for when the theme is made responsive
	wp_enqueue_script( 'yumc-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	//Events dropdown stuff
	wp_enqueue_script( 'yumc-events', get_template_directory_uri() . '/js/events.js', array( 'jquery' ) );

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
	add_image_size( '842-421-thumb', 842, 421, true );
}
add_action( 'after_setup_theme', 'yumc_image_sizes' );

function yumc_parse_fb_timestamp( $fb_timestamp ){
	return new DateTime($fb_timestamp);
}

function yumc_parse_google_timestamp( $google_timestamp ){
	return new DateTime($google_timestamp);
}

function yumc_parse_twitter_timestamp( $twitter_timestamp ){
	return new DateTime($twitter_timestamp);
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
function yumc_get_facebook_events( $num_events = 30 ) {
	$fb_events_json = file_get_contents("https://graph.facebook.com/ClimbingYork/events?access_token=" . FB_ACCESS_TOKEN . "&limt=60&fields=name,description,place,start_time,id,cover");
	$fb_events_obj = json_decode($fb_events_json, true)["data"];

	// Sort events
	usort($fb_events_obj, function ($item1, $item2) {
		if ( yumc_parse_fb_timestamp( $item1["start_time"] ) == yumc_parse_fb_timestamp( $item2["start_time"] ) ) return 0;
		return yumc_parse_fb_timestamp( $item1["start_time"] ) < yumc_parse_fb_timestamp( $item2["start_time"] ) ? -1 : 1;
	});

	$fb_events = array();
	$count = 0;
	foreach($fb_events_obj as $fb_event)
	{
		if(yumc_parse_fb_timestamp( $fb_event["start_time"] ) > new DateTime() && $count < $num_events) {
			$fb_events[] = $fb_event;
			$count++;
		}

		if($count >= $num_events)
			break;
	}

	return $fb_events;
}


function yumc_get_google_events( $num_events = 30 ) {
	// https://www.googleapis.com/calendar/v3/calendars/mountaineering@yusu.org/events?key=AIzaSyA_2MLeqEt0ry2OTjLKfHTYIiQ-zAd8H-Y&singleEvents=true&timeMin=2016-09-13T00:00:00Z
	// https://www.googleapis.com/calendar/v3/calendars/mountaineering@yusu.org/events?key=AIzaSyA_2MLeqEt0ry2OTjLKfHTYIiQ-zAd8H-Y&singleEvents=true&timeMin=2016-09-13T12:05:47+00:00
	$now = new DateTime();
	$google_events_json = file_get_contents("https://www.googleapis.com/calendar/v3/calendars/" . GOOGLE_CALENDAR_ID . "/events?key=" . GOOGLE_CALENDAR_KEY . "&singleEvents=true&timeMin=" . $now->format("Y-m-d\TH:i:s") . (($now->format("P") == "+00:00") ? "Z" : $now->format("P")));
	$google_events_obj = json_decode($google_events_json, true)["items"];

	$google_events = array();
	$count = 0;
	foreach($google_events_obj as $google_event)
	{
		if($count < $num_events) {
			$google_events[] = $google_event;
			$count++;
		}

		if($count >= $num_events)
			break;
	}

	return $google_events;
}

/*
 *	Does a JSON Get request to get facebook posts from the YUMC page, and parses them so only the text based posts are returned
 */
function yumc_get_facebook_posts( $num_posts = 50 ) {
	$fb_posts_json = file_get_contents("https://graph.facebook.com/ClimbingYork/posts?access_token=" . FB_ACCESS_TOKEN);
	$posts_obj = json_decode($fb_posts_json, true)["data"];
	$filtered_posts_obj = array();
	$count = 0;
	foreach($posts_obj as $post) {
		if($post["message"] != null && $count < $num_posts) {
			$filtered_posts_obj[] = $post;
			$count++;
		}

		if($count >= $num_posts)
			break;
	}
	return $filtered_posts_obj;
}

/*
 *	Does a JSON Get request to get twitter posts from the YUMC account
 */
function yumc_get_twitter_posts( $num_posts = 50 ) {
	$oauth_hash = 'oauth_consumer_key=' . TWITTER_API_KEY . '&';
	$oauth_hash .= 'oauth_nonce=' . time() . '&';
	$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
	$oauth_hash .= 'oauth_timestamp=' . time() . '&';
	$oauth_hash .= 'oauth_token=' . TWITTER_ACCESS_TOKEN . '&';
	$oauth_hash .= 'oauth_version=1.0';
	$base = 'GET';
	$base .= '&';
	$base .= rawurlencode( 'https://api.twitter.com/1.1/statuses/user_timeline.json' );
	$base .= '&';
	$base .= rawurlencode( $oauth_hash );
	$key = '';
	$key .= rawurlencode( TWITTER_API_SECRET );
	$key .= '&';
	$key .= rawurlencode( TWITTER_ACCESS_SECRET );
	$signature = base64_encode( hash_hmac( 'sha1', $base, $key, true ) );
	$signature = rawurlencode($signature);

	$oauth_header = '';
	$oauth_header .= 'oauth_consumer_key="' . TWITTER_API_KEY . '", ';
	$oauth_header .= 'oauth_nonce="' . time() . '", ';
	$oauth_header .= 'oauth_signature="' . $signature . '", ';
	$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
	$oauth_header .= 'oauth_timestamp="' . time() . '", ';
	$oauth_header .= 'oauth_token="' . TWITTER_ACCESS_TOKEN . '", ';
	$oauth_header .= 'oauth_version="1.0", ';
	$curl_header = array( "Authorization: Oauth {$oauth_header}", 'Expect:' );

	$curl_request = curl_init();
	curl_setopt( $curl_request, CURLOPT_HTTPHEADER, $curl_header );
	curl_setopt( $curl_request, CURLOPT_HEADER, false );
	curl_setopt( $curl_request, CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json' );
	curl_setopt( $curl_request, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl_request, CURLOPT_SSL_VERIFYPEER, false );
	$json = curl_exec( $curl_request );
	curl_close( $curl_request );

	return array_slice( json_decode($json, true), 0, $num_posts );
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
		'rewrite'            => array( 'slug' => 'events' ),
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
 *	Nicked this from t'web, here: http://wordpress.stackexchange.com/questions/128538/image-resize-with-image-url
 */
function wpse128538_resize($url, $width, $height = null, $crop = null, $single = true) {

//validate inputs
	if (!$url OR !$width)
		return false;

//define upload path & dir
	$upload_info = wp_upload_dir();
	$upload_dir = $upload_info['basedir'];
	$upload_url = $upload_info['baseurl'];

//check if $img_url is local
	if (strpos($url, $upload_url) === false)
		return false;

//define path of image
	$rel_path = str_replace($upload_url, '', $url);
	$img_path = $upload_dir . $rel_path;

//check if img path exists, and is an image indeed
	if (!file_exists($img_path) OR !getimagesize($img_path))
		return false;

//get image info
	$info = pathinfo($img_path);
	$ext = $info['extension'];
	list($orig_w, $orig_h) = getimagesize($img_path);

//get image size after cropping
	$dims = image_resize_dimensions($orig_w, $orig_h, $width, $height, $crop);
	$dst_w = $dims[4];
	$dst_h = $dims[5];

//use this to check if cropped image already exists, so we can return that instead
	$suffix = "{$dst_w}x{$dst_h}";
	$dst_rel_path = str_replace('.' . $ext, '', $rel_path);
	$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

	if (!$dst_h) {
//can't resize, so return original url
		$img_url = $url;
		$dst_w = $orig_w;
		$dst_h = $orig_h;
	}
//else check if cache exists
	elseif (file_exists($destfilename) && getimagesize($destfilename)) {
		$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
	}
//else, we resize the image and return the new resized image url
	else {

// Note: pre-3.5 fallback check
		if (function_exists('wp_get_image_editor')) {

			$editor = wp_get_image_editor($img_path);

			if (is_wp_error($editor) || is_wp_error($editor->resize($width, $height, $crop)))
				return false;

			$resized_file = $editor->save();

			if (!is_wp_error($resized_file)) {
				$resized_rel_path = str_replace($upload_dir, '', $resized_file['path']);
				$img_url = $upload_url . $resized_rel_path;
			} else {
				return false;
			}
		} else {

			$resized_img_path = image_resize($img_path, $width, $height, $crop);
			if (!is_wp_error($resized_img_path)) {
				$resized_rel_path = str_replace($upload_dir, '', $resized_img_path);
				$img_url = $upload_url . $resized_rel_path;
			} else {
				return false;
			}
		}
	}

//return the output
	if ($single) {
//str return
		$image = $img_url;
	} else {
//array return
		$image = array(
			0 => $img_url,
			1 => $dst_w,
			2 => $dst_h
		);
	}

	return $image;
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