<?php
/*
 *  archive-yumc_events.php
 *  -----------------------
 *  PHP: Template for displaying all events
 */

get_header();

$fb_events = yumc_get_facebook_events();
$events_array = array();

foreach($fb_events as $event) {
	$events_array[] = array(
		'id' => 'fb-' . $event["id"],
		'timestamp' => yumc_parse_fb_timestamp($event["start_time"]),
		'title' => $event["name"],
		'content' => nl2br( $event["description"] ),
		'location' => $event["place"]["name"],
		'url' => rtrim( get_post_type_archive_link( 'yumc_events' ), '/' ) . '#event-fb-' . $event["id"],
		'origin' => "fb",
		'image' => $event["cover"]["source"],
	);
}

$args = array(
	'post_status'    => 'publish',
	'post_type'      => 'yumc_events',
	'meta_key'       => 'yumc_event_unix_timestamp',
	'orderby'        => 'meta_value_num',
	'order'          => 'DESC',
);

$site_events = get_posts( $args );

foreach($site_events as $site_event)
{
	if( yumc_parse_event_timestamp( get_post_meta( $site_event->ID, 'yumc_event_unix_timestamp', true ) ) > new DateTime("14-07-2014") ) {
		$events_array[] = array(
			'id' => $site_event->ID,
			'timestamp' => yumc_parse_event_timestamp(get_post_meta($site_event->ID, 'yumc_event_unix_timestamp', true)),
			'title' => $site_event->post_title,
			'content' => $site_event->post_content,
			'location' => "temp",
			'url' => rtrim( get_post_type_archive_link( 'yumc_events' ), '/' ) . '#event-' . $site_event->ID,
			'origin' => "wp",
		);
	}
}

if( $events_array ) {
	usort($events_array,
		function ($a, $b) {
			$ad = $a['timestamp'];
			$bd = $b['timestamp'];

			if ($ad == $bd) {
				return 0;
			}

			return $ad < $bd ? -1 : 1;
		}
	);
}

?>
	<main id="main" class="site-main grid_16 prefix_2 suffix_2" role="main">
		<article id="events-page" class="event_listing">
			<header class="entry-header grid_11 prefix_1 suffix_1">
				<h1 class="entry-title">Upcoming Events<span class="page-title-underline"></span></h1>
			</header><!-- .entry-header -->
			<div class="clear"></div>
			<?php
				foreach($events_array as $event) {
					?>
					<div>
						<a href="<?php echo $event["url"] ?>" id="event-<?php echo $event["id"]; ?>" class="event_entry calendar_entry">
							<div class="date_stamp">
								<span class="day"><?php echo $event["timestamp"]->format('d'); ?></span>
								<span class="month"><?php echo $event["timestamp"]->format('M'); ?></span>
							</div>
							<div class="content">
								<h2><?php echo $event["title"]; ?></h2>
								<p>
									<?php
									echo $event["timestamp"]->format('g');
									if ($event["timestamp"]->format('i') != "00")
										echo ':' . $event["timestamp"]->format('i');
									echo $event["timestamp"]->format('a') . '.';
									?>
									Click to show more info.
								</p>
								<div class="event_drop_down_icon">+</div>
							</div>
						</a>
						<div class="event_content event_dropdown">
							<?php
							if ($event["origin"] == "fb")
							{
								if($event["image"])
								{
									?>
									<div class="event_image" style="width: 842px; height: 421px; overflow: hidden;">
										<img src="<?php echo $event["image"]; ?>" style="width: 100%;" />
									</div>
									<?php
								}
							}
							else if ( has_post_thumbnail( $event["id"] ) ) {
								echo get_the_post_thumbnail( $event["id"], '842-421-thumb', array( 'class' => 'event_image' ));
							}
							?>

							<?php echo $event["content"] ?>
						</div>
						<div class="clear"></div>
					</div>
					<?php
				}
			?>
		</article>
	</main><!-- #main -->
<?php
get_footer();
