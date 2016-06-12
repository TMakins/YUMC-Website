<?php
/*
 *  index.php
 *  ---------
 *  PHP: Base page layout
 */

get_header(); ?>

	<main id="main" class="site-main grid_16 prefix_2 suffix_2" role="main">
		<?php
		if( is_home() && !is_front_page() ):
		?>
			<section id="blog-page">
				<header class="entry-header grid_11 prefix_1 suffix_1">
					<h1 class="entry-title">
						<?php echo apply_filters( 'the_title', get_page( get_option( 'page_for_posts') )->post_title); ?>
						<span class="page-title-underline"></span>
					</h1>
				</header>
			</section>
		<?php
		endif;
		?>
		<?php
		$post_count = 0;
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				if( is_front_page() ) {
					//Front page, not blog
					$template_part = "frontpage";
				}
				elseif( is_page() ) {
					//Any page
					$template_part = "page";
				}
				else {
					$template_part = get_post_format();
				}
				if( $template_part ) {
					include( locate_template( 'template-parts/content-' . $template_part . '.php' ) );
				}
				else {
					include( locate_template( 'template-parts/content.php' ) );
				}

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
