<?php
/*
 *  footer.php
 *  ----------
 *  PHP: Footer included at the bottom of all pages
 */

?>
	<div class="clearfix"></div>

	<footer id="page-footer" class="site-footer" role="contentinfo">

		<div class="nav-container">
			<div class="clearfix"></div>
			<nav id="footer-menu" class="grid_16">
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container'       => '',
					)
				);
				?>
			</nav>
			<div class="clearfix"></div>
		</div>

		<small>&copy; York University Mountaineering Club.</small>
	</footer>

<?php wp_footer(); ?>

</body>
</html>
