<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Thème
 */

?>

		<footer id="colophon" class="site-footer">
			<div class="wrap">

				<?php if ( has_nav_menu( 'navigation-social' ) ) :
					get_template_part( 'template-parts/navigation', 'social' );
				endif; ?>

				<div class="site-info">
					<a href="<?php echo esc_url( __( 'https://fr.wordpress.org/', 'theme' ) ); ?>"><?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( 'Propulsé par %s', 'theme' ), 'WordPress' );
					?></a>
					<span class="sep"> | </span>
					<?php
						/* translators: 1: Theme name, 2: Theme author. */
						printf( esc_html__( 'Thème: %1$s de %2$s.', 'theme' ), '<i>Thème</i>', '<a href="https://imathi.eu/">imath</a>' );

						if ( function_exists( 'the_privacy_policy_link' ) ) {
							the_privacy_policy_link( '<span class="sep"> | </span>', '' );
						}
					?>
				</div><!-- .site-info -->
			</div><!-- .wrap -->
		</footer><!-- #colophon -->
	</div><!-- .site-content -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
