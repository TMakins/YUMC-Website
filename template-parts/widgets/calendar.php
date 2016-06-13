<?php
/*
 *  calendar.php
 *  ------------
 *  PHP: Calendar/events widget for the front page
 */
?>

<?php
$fb_events = get_facebook_events();

foreach($fb_events as $event)
{
    $events_array[] = array(
            'timestamp' => parse_fb_timestamp($event["start_time"]),
            'title'     => $event["name"],
            'location'  => $event["place"]["name"],
            'url'       => "https://www.facebook.com/events/" . $event["id"],
            'origin'     => "fb",
        );
}

/*
foreach($events_array as $event)
{
    echo '<h3>' . $event["title"] . '</h3>';
    echo '<small>' . $event["timestamp"] . ' @ ' . $event["location"] . '</small>';
    echo '<p>' . $event["description"] . '</p>';
}
*/
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
                <?php if ($event["origin"] != "fb") { ?>
                    <?php echo '<p>' . $event["description"] . '</p>'; ?>
                <?php } else { ?>
                    <p>
                        <?php
                        echo $event["timestamp"]->format('g');
                        if ($event["timestamp"]->format('i') != "00")
                            echo ':' . $event["timestamp"]->format('i');
                        echo $event["timestamp"]->format('a') . '.';
                        ?>
                        Click to view on Facebook.</p>
                <?php } ?>
            </div>
        </a>
    <?php
    endforeach;
    ?>

    <a href="" class="button view_all_more_button">
        See All
    </a>
</section>