<?php
/**
 * Template part for the social navigation
 *
 * @package Thème
 */
?>
		<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Profils sociaux', 'theme' ); ?>">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'navigation-social',
					'menu_class'     => 'social-links-menu',
				) );
			?>
		</nav><!-- .social-navigation -->
