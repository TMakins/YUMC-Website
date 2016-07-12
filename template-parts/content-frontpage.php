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

<div class="separator prefix_2 suffix_2"><span class="grid_7"></span></div>

<?php include( locate_template( 'template-parts/widgets/calendar.php' ) ); ?>

<div class="grid_1">&nbsp;</div>

<?php include( locate_template( 'template-parts/widgets/social.php' ) ); ?>