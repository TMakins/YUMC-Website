<?php
/*
 *  custom-header.php
 *  -----------------
 *  PHP: Setup custom header implementation
 */

<?php if ( get_header_image() ) : ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="">
	</a>
<?php endif; // End header image check. ?>

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses yumc_header_style()
 */
function yumc_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'yumc_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/images/header-image.jpg',
		'default-text-color'     => '000000',
		'width'                  => 1200,
		'height'                 => 420,
		'flex-height'            => false,
	) ) );
}
add_action( 'after_setup_theme', 'yumc_custom_header_setup' );