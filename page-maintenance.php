<?php
/**
 * Maintenance page
 *
 * @package Thème
 *
 * @since 1.0.0
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'front-page' ); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'theme' ); ?></a>

	<header id="masthead" class="site-header">
        <?php get_template_part( 'template-parts/custom-header', 'image' ); ?>

        <div class="navigation-top">
            <div class="wrap">

                <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Menu supérieur', 'theme' ); ?>">

                    <button class="menu-toggle"></button>

                    <h1><?php theme_the_maintenance_title(); ?></h1>

                    <a href="<?php echo esc_url( wp_login_url() ); ?>" class="login-link">
                        <?php echo theme_get_icon( theme_icons( 'maintenance.wp') ); ?>
                        <span class="screen-reader-text">
                            <?php esc_html_e( 'Se connecter', 'theme' ); ?>
                        </span>
                    </a>
                </nav>
            </div><!-- .wrap -->
        </div><!-- .navigation-top -->
	</header><!-- #masthead -->

	<div class="site-content">

<?php get_footer();
