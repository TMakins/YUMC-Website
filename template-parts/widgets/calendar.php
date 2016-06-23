<?php
/*
 *  calendar.php
 *  ------------
 *  PHP: Calendar/events widget for the front page
 */
?>

<?php
$fb_events = get_facebook_events(2);

foreach($fb_events as $event)
{
    $events_array[] = array(
            'timestamp' => parse_fb_timestamp($event["start_time"]),
            'title'     => $event["name"],
            'location'  => $event["place"]["name"],
            'url'       => "https://www.facebook.com/events/" . $event["id"],
            'origin'    => "fb",
        );
}

$args = array(
    'post_status'    => 'publish',
    'post_type'      => 'yumc_event',
    'meta_key'       => 'yumc_event_timestamp',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'posts_per_page' => 2
);

$site_events = get_posts( $args );

foreach($site_events as $site_event)
{
    $events_array[] = array(

        );
}

?>

<section class="widget calender_widget grid_5">
    <h2 class="widget_title">Upcoming Trips & Events</h2>

    <?php
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
    ?>

    <a href="" class="button view_all_more_button">
        See All
    </a>
</section>