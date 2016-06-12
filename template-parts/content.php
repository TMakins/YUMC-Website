<?php
/*
 *  content.php
 *  -----------
 *  PHP: Template for displaying the block page.
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( ($post_count < 2) ? 'grid_5 larger_post' : 'grid_3 smaller_post' ); ?>>
		<a class="entry-thumb" href="<?php echo esc_url( get_permalink() ) ?>">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( '380-253-thumb' );
			}
			else {
				?>
				<img src="<?php echo get_template_directory_uri(); ?>/images/placeholder.jpg" alt="No image" />
				<?php
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
					if( $post_count < 2 )
						yumc_the_excerpt(100);
					else
						yumc_the_excerpt(50);
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
	if( $post_count == 0 || ( $post_count > 1 && ( $post_count - 1 ) % 3 != 0 ) ):
	?>
		<div class="grid_1">&nbsp;</div>
	<?php
	endif;
	?>
	<?php
	if( $post_count == 1 || ( $post_count - 1) % 3 == 0 ):
		?>
		<div class="clearfix"></div>
		<?php
	endif;
	?>
<?php
$post_count++;
?>