<?php
/*
 *  single.php
 *  ----------
 *  PHP: template for displaying single posts
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main grid_16 prefix_2 suffix_2" role="main">

		<?php
		while ( have_posts() ) : the_post();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( "blog" ); ?>>
				<a class="entry-thumb" href="<?php echo esc_url( get_permalink() ) ?>">
					<?php
						the_post_thumbnail( '880-440-thumb' );
					?>
				</a>

				<header class="entry-header">
					<?php
					the_title( '<h1 class="entry-title">', '<span class="page-title-underline"></span></h1>' );
					?>
				</header>

				<div class="entry-meta">
					<?php
					$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
					if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
						$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
					}

					$time_string = sprintf( $time_string,
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date() ),
						esc_attr( get_the_modified_date( 'c' ) ),
						esc_html( get_the_modified_date() )
					);

					echo 'Posted: <a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
					?>
				</div>

				<div class="entry-content">
					<?php
						the_content();
					?>
				</div>

				<div class="separator prefix_2 suffix_2"><span class="grid_7"></span></div>
				<div class="clear"></div>

				<?php

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>
			</article>
		<?php
		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
