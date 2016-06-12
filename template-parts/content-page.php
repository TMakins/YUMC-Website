<?php
/*
 *  content-page.php
 *  ----------------
 *  PHP: Template for displaying pages
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header grid_11 prefix_1 suffix_1">
        <?php the_title( '<h1 class="entry-title">', '<span class="page-title-underline"></span></h1>' ); ?>
    </header><!-- .entry-header -->

    <div class="entry-content page-content">
        <?php
        the_content();

        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'yumc' ),
            'after'  => '</div>',
        ) );
        ?>
    </div><!-- .entry-content -->
</article><!-- #post-## -->
<?php
// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) {
	comments_template();
}
?>