<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Thème
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! is_front_page() ) : ?>
		<header class="entry-header">
			<div class="wrap">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</div><!-- .wrap -->
		</header><!-- .entry-header -->
	<?php endif ; ?>

	<?php theme_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'theme' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<div class="wrap">
				<?php
					edit_post_link(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Modifier <span class="screen-reader-text">%s</span>', 'theme' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							get_the_title()
						),
						'<span class="edit-link">',
						'</span>'
					);
				?>
			</div><!-- .wrap -->
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
