<?php
/*
 *  custom-header.php
 *  -----------------
 *  PHP: Setup custom header implementation
 */

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