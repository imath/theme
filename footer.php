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
			<div class="site-info">
				<div class="wrap">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'theme' ) ); ?>"><?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( 'Fièrement propulsé par %s', 'theme' ), 'WordPress' );
					?></a>
					<span class="sep"> | </span>
					<?php
						/* translators: 1: Theme name, 2: Theme author. */
						printf( esc_html__( 'Thème: %1$s de %2$s.', 'theme' ), '<i>Thème</i>', '<a href="https://imathi.eu/">imath</a>' );
					?>
				</div><!-- .wrap -->
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- .site-content -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
