<?php
/*
 *  header.php
 *  ----------
 *  PHP: Header included at the top of all site pages
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<?php wp_head(); ?>
	</head>
	<body <?php body_class() ?>>
		<header id="masthead" class="site-header" role="banner">
			<?php if ( get_header_image() ) : ?>
					<img id="header-image" src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="">
			<?php else: ?>
				<img id="header-image" src="<?php echo get_template_directory_uri(); ?>/images/header-image.jpg" alt="Some mountains somewhere" />
			<?php endif; ?>
            <a href="<?php echo site_url(); ?>" title="YUMC Home">
                <h1 id="logo"><?php bloginfo( "name" ); ?></h1>
            </a>
			<div class="nav-container">
				<div class="clearfix"></div>
				<nav id="primary-menu" class="grid_16 prefix_4">
	                <?php
	                    wp_nav_menu(
	                        array(
	                            'theme_location'  => 'primary',
	                            'container'       => false,
	                        )
	                    );
	                ?>
				</nav>
				<div class="clearfix"></div>
			</div>
		</header>
