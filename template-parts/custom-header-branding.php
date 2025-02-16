<?php
/**
 * Template part for the custom header site branding
 *
 * @package Thème
 */

?>
		<div class="site-branding">

			<div class="wrap">

				<?php the_custom_logo(); ?>

				<div class="site-branding-text">

					<?php if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php
					endif;

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
					<?php
					endif; ?>

				</div><!-- .site-branding-text -->
			</div><!-- .wrap -->
		</div><!-- .site-branding -->
