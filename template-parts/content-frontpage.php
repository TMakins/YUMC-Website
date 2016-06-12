<?php
/*
 *  content-frontpage.php
 *  ---------------------
 *  PHP: Template for displaying the static front page
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    // This page is only called when is_front_page, so check it's not the blog page
    if( is_home() ):
	    ?>

        <p>Error: make sure that a static page is set to be the front page!</p>

	    <?php
    //This is the actual front page
    else:
	    ?>
        <header class="entry-header grid_11 prefix_1 suffix_1">
            <?php the_title( '<h1 class="entry-title">', '<span class="page-title-underline"></span></h1>' ); ?>
        </header><!-- .entry-header -->

        <div class="entry-content frontpage-content">
            <?php
            the_content();
            ?>
        </div><!-- .entry-content -->
        <?php
    endif;
    ?>
</article><!-- #post-## -->

<div class="separator prefix_1 suffix_1"><span class="grid_9"></span></div>

<!-- Wicget column 1 - events widget -->
<section class="widget calender_widget grid_5">
    <h2 class="widget_title">Upcoming Trips & Events</h2>

    <article class="calendar_entry">
        <div class="date_stamp">
            <span class="day">12</span>
            <span class="month">Jun</span>
        </div>
        <h4>Trip: Stanage Edge</h4>
        <p>Meet outside the RKC at 9am</p>
    </article>

    <article class="calendar_entry">
        <div class="date_stamp">
            <span class="day">29</span>
            <span class="month">Oct</span>
        </div>
        <h4>Event: Freshers Fair</h4>
        <p>9am, we'll be at the back of the sports tent</p>
    </article>

    <a href="" class="button view_all_more_button">
        See All
    </a>
</section>
<div class="grid_1">&nbsp;</div>
<section class="widget social_widget grid_5">
    <h2 class="widget_title">Latest Social Media Updates</h2>

    <article class="social_entry">
        <div class="icon_stamp fb">&nbsp;</div>
        <p>YUMC crushed Lancaster at the bouldering comp last night, earning a huge 11.5 Roses [...]</p>
    </article>

    <article class="social_entry">
        <div class="icon_stamp twitter">&nbsp;</div>
        <p>YUMC crushed Lancaster at the bouldering comp last night, earning a huge 11.5 Roses [...]</p>
    </article>

    <a href="" class="button view_all_more_button">
        View More
    </a>
</section>