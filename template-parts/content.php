<?php
/*
 *  content.php
 *  -----------
 *  PHP: Template for displaying the block page.
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<a class="entry-thumb" href="<?php echo esc_url( get_permalink() ) ?>">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( '380-253-thumb' );
			}
			?>
		</a>
	
		<header class="entry-header">
			<?php
				if ( is_single() ) {
					the_title( '<h1 class="entry-title">', '<span class="page-title-underline"></span></h1>' );
					if ( 'post' === get_post_type() ) : ?>
						<div class="entry-meta">
							<?php yumc_posted_on(); ?>
						</div>
					<?php endif;
				} else {
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				}
			?>
		</header>
	
		<div class="entry-content">
			<?php
				if( is_single() ) {
					the_content();
				}
				else {
					yumc_the_excerpt(100);
				}

				/*
				wp_link_pages( array(
					'before' => '<div class="page-links">' . 'Pages:',
					'after'  => '</div>',
				) );
				*/
			?>
		</div><!-- .entry-content -->

		<!--
		<footer class="entry-footer">
			<?php yumc_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</article><!-- #post-## -->
<?php
$post_count++;
?>