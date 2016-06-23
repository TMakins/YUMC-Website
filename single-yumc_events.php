<?php
/*
 *  single.php
 *  ----------
 *  PHP: template for displaying single posts
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<img src="https://premium.wpmudev.org/blog/wp-content/uploads/2014/09/eventon-wordpress-event-calendar.png" />

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content-', get_post_format() );

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
