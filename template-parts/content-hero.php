<?php
/**
 * The template for displaying the hero sections.
 *
 * @package Thème
 */
?>
<article id="hero<?php echo theme()->hero_counter; ?>" <?php post_class( 'theme-hero ' ); ?> >

	<?php if ( has_post_thumbnail() ) : ?>

		<div class="hero-image wrap">
				<?php theme_post_thumbnail(); ?>
		</div><!-- .hero-image -->

	<?php endif; ?>

	<div class="hero-content">
		<div class="wrap">
			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
					/* translators: %s: Name of current post */
					the_content(
						sprintf(
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'theme' ),
							get_the_title()
						)
					);
				?>
			</div><!-- .entry-content -->
		</div><!-- .wrap -->
	</div><!-- .hero-content -->

</article><!-- #hero<?php echo theme()->hero_counter; ?> -->
