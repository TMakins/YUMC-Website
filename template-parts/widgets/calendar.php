<?php
/*
 *  calendar.php
 *  ------------
 *  PHP: Calendar/events widget for the front page
 *
 *  TO-DO: Add caching
 */
?>

<?php
$fb_events = yumc_get_facebook_events(2);
$google_events = yumc_get_google_events(2);
$events_array = array();

foreach($google_events as $event) {
    $events_array[] = array(
        'id' => 'google-' . $event["id"],
        'timestamp' => yumc_parse_google_timestamp($event["start"]["dateTime"]),
        'title' => $event["summary"],
        'content' => nl2br( $event["description"] ),
        'location' => $event["location"],
        'url' => rtrim( get_post_type_archive_link( 'yumc_events' ), '/' ) . '#event-google-' . $event["id"],
        'origin' => "google",
        'image' => "",
    );
}

foreach($fb_events as $event) {
    $exists = false;
    foreach( $events_array as $existing_event )	{
        if( $existing_event["title"] == $event["name"] ) {
            $exists = true;
            break;
        }
    }

    if(!$exists) {
        $events_array[] = array(
            'id' => 'fb-' . $event["id"],
            'timestamp' => yumc_parse_fb_timestamp($event["start_time"]),
            'title' => $event["name"],
            'content' => nl2br($event["description"]),
            'location' => $event["place"]["name"],
            'url' => rtrim(get_post_type_archive_link('yumc_events'), '/') . '#event-fb-' . $event["id"],
            'origin' => "fb",
            'image' => $event["cover"]["source"],
        );
    }
}

$args = array(
    'post_status'    => 'publish',
    'post_type'      => 'yumc_events',
    'meta_key'       => 'yumc_event_unix_timestamp',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'posts_per_page' => 2
);

$site_events = get_posts( $args );

foreach($site_events as $site_event)
{
    if( yumc_parse_event_timestamp( get_post_meta( $site_event->ID, 'yumc_event_unix_timestamp', true ) ) > new DateTime() ) {
        $events_array[] = array(
            'timestamp' => yumc_parse_event_timestamp(get_post_meta($site_event->ID, 'yumc_event_unix_timestamp', true)),
            'title' => $site_event->post_title,
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

<section class="widget calender_widget grid_5">
    <h2 class="widget_title">Upcoming Trips & Events</h2>
    <?php
    if( $events_array ):
        $count = 0;
        foreach($events_array as $event):
            $count++;
            if ($count > 2)
                break;
        ?>
            <a href="<?php echo $event["url"] ?>" <?php echo (($event["origin"] == "fb") ? "target=\"_blank\"" : ""); ?> class="widget_entry calendar_entry">
                <div class="date_stamp">
                    <span class="day"><?php echo $event["timestamp"]->format('d'); ?></span>
                    <span class="month"><?php echo $event["timestamp"]->format('M'); ?></span>
                </div>
                <div class="content">
                    <h4><?php echo $event["title"]; ?></h4>
                    <p>
                        <?php
                        echo $event["timestamp"]->format('g');
                        if ($event["timestamp"]->format('i') != "00")
                            echo ':' . $event["timestamp"]->format('i');
                        echo $event["timestamp"]->format('a') . '.';
                        ?>
                        <?php echo ($event["origin"] == "fb") ? "Click to view on Facebook." : "Click to find out more info."; ?>
                    </p>
                </div>
            </a>
        <?php
        endforeach;
    else:
    ?>
        <a href="" class="widget_entry calendar_entry" style="cursor: default; ">
            <div class="date_stamp">
                <span class="day">
                    :(
                </span>
            </div>
            <div class="content">
                <h4>No events planned for the future yet!</h4>
                <p>Click below to see our previous events.</p>
            </div>
        </a>
    <?php
    endif;
    ?>
    <a href="<?php echo get_post_type_archive_link( 'yumc_events' ); ?>" class="button view_all_more_button">
        See All
    </a>
</section>