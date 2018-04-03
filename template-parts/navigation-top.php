<?php
/**
 * Template part for the main navigation
 *
 * @package Thème
 */

?>
		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'theme' ); ?></button>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'navigation-top',
					'menu_id'        => 'main-navigation',
				) );
			?>
		</nav><!-- #site-navigation -->
