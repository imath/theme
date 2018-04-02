<?php
/**
 * The header for Theme's DB error template
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Thème
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'db-error' ); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'theme' ); ?></a>

	<header id="masthead" class="site-header">
		<?php get_template_part( 'template-parts/custom-header', 'image' ); ?>

		<?php if ( has_nav_menu( 'social-navigation' ) ) : ?>
			<div class="social-navigation-top">
				<div class="wrap">
					<nav class="social-navigation">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'theme' ); ?></button>
						<?php
							wp_nav_menu( array(
								'theme_location' => 'social-navigation',
								'menu_id'        => 'social-navigation-db-error',
							) );
						?>
					</nav><!-- .social-navigation -->
				</div><!-- .wrap -->
			</div><!-- .social-navigation-top -->
		<?php endif; ?>
	</header><!-- #masthead -->

	<div class="site-content">
