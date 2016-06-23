<?php
/*
 *  archive-yumc_events.php
 *  -----------------------
 *  PHP: Template for displaying all events
 */

get_header(); ?>

	<main id="main" class="site-main grid_16 prefix_2 suffix_2" role="main">
		<article id="events-page">
			<header class="entry-header grid_11 prefix_1 suffix_1">
				<h1 class="entry-title">Upcoming Events<span class="page-title-underline"></span></h1>
			</header><!-- .entry-header -->

		<?php
		if ( have_posts() ) : ?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				

			endwhile;

			the_posts_navigation();

		endif; ?>
		</article>
	</main><!-- #main -->
<?php
get_footer();
