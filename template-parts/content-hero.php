<?php
/**
 * The template for displaying the hero sections.
 *
 * @package Thème
 */
?>
<article id="hero<?php echo theme()->hero_counter; ?>" <?php post_class( 'theme-hero ' ); ?> >
	<div class="wrap">

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="hero-image">
					<?php theme_post_thumbnail( array( 'hero' ), 'thumbnail' ); ?>
			</div><!-- .hero-image -->
		<?php endif; ?>

		<div class="hero-content">
			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
					/* translators: %s: Name of current post */
					the_content(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Poursuivre la lecture<span class="screen-reader-text"> de "%s"</span>', 'theme' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					);
				?>
			</div><!-- .entry-content -->
		</div><!-- .hero-content -->
	</div><!-- .wrap -->

</article><!-- #hero<?php echo theme()->hero_counter; ?> -->
