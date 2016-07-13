<?php
/*
 *  single-yumc_events.php
 *  ----------------------
 *  PHP: Single event, not used so redirects to the event on the event archive page.
 */

wp_redirect( rtrim( get_post_type_archive_link( 'yumc_events' ), '/' ) . '#event-' . get_the_ID() );