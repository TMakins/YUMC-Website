<?php
/*
 *  social.php
 *  ----------
 *  PHP: Calendar/events widget for the front page
 *
 *  TO-DO: Add caching
 */

$fb_posts = yumc_get_facebook_posts(2);
$twitter_posts = yumc_get_twitter_posts(2);

$posts_array = array();

foreach($fb_posts as $post) {
    $posts_array[] = array(
        'timestamp' => yumc_parse_fb_timestamp( $post["created_time"] ),
        'message' => trim( preg_replace( '/\s\s+/', ' ', $post["message"] ) ),
        'url' => "https://www.facebook.com/ClimbingYork/posts/" . substr( $post["id"], ( strrpos( $post["id"], '_' ) ?: -1 ) +1 ),
        'origin' => "fb",
    );
}

foreach($twitter_posts as $post) {
    $posts_array[] = array(
        'timestamp' => yumc_parse_twitter_timestamp( $post["created_at"] ),
        'message' => trim( preg_replace( '/\s\s+/', ' ', $post["text"] ) ),
        'url' => "https://twitter.com/theyumc/status/" . $post["id_str"],
        'origin' => "twitter",
    );
}


if( $posts_array ) {
    usort($posts_array,
        function ($a, $b) {
            $ad = $a['timestamp'];
            $bd = $b['timestamp'];

            if ($ad == $bd) {
                return 0;
            }

            return $ad > $bd ? -1 : 1;
        }
    );
}

?>

<section class="widget social_widget grid_5">
    <h2 class="widget_title">Latest Social Media Updates</h2>
    <?php
    if( $posts_array ):
        $count = 0;
        foreach( $posts_array as $post ):
            $count++;
            if ($count > 2)
                break;
    ?>
        <a href="<?php echo $post["url"]; ?>" target="_blank" class="widget_entry social_entry">
            <div class="icon_stamp <?php echo $post["origin"]; ?>">&nbsp;</div>
            <p><?php echo ( strlen( $post["message"] ) > 88 ) ? substr( $post["message"], 0, 82 ) . ' [...]' : $post["message"]; ?></p>
        </a>
    <?php
        endforeach;
    endif;
    ?>
    <div class="social_button_container">
        <a href="" class="button view_all_more_button">
            View More
        </a>
        <a class="twitter_button" href="https://twitter.com/theyumc" target="_blank">
            <img src="<?php echo get_template_directory_uri(); ?>/images/twitter_icon.png" alt="Twitter" />
        </a>
        <a class="facebook_button" href="https://www.facebook.com/ClimbingYork/" target="_blank">
            <img src="<?php echo get_template_directory_uri(); ?>/images/fb_icon.png" alt="Facebook" />
        </a>
    </div>
</section>