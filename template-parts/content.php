<?php
/*
 *  content.php
 *  -----------
 *  PHP: Template for displaying the block page.
 */
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( "blog " . ((!is_single()) ? "blog-page-post masonry grid_5" : "")); ?>>
		<a class="entry-thumb" href="<?php echo esc_url( get_permalink() ) ?>">
			<?php
			if ( has_post_thumbnail() && !is_single() ) {
				the_post_thumbnail( '380-253-thumb' );
			}
			else
			{
				the_post_thumbnail( '880-440-thumb' );
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
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a><span class="small-underline"></span></h2>' );
				}
			?>
		</header>
	
		<div class="entry-content">
			<?php
				if( is_single() ) {
					the_content();
				}
				else {
					the_excerpt();
				}

				/*
				wp_link_pages( array(
					'before' => '<div class="page-links">' . 'Pages:',
					'after'  => '</div>',
				) );
				*/
			?>
		</div><!-- .entry-content -->
		<?php
		if( !is_single() )
		{
			echo '<div class="separator prefix_1 suffix_1"><span class="grid_3"></span></div>';
		}
		?>
		<!--
		<footer class="entry-footer">
			<?php yumc_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</article><!-- #post-## -->
<?php
$post_count++;
?>