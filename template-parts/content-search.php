<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Thème
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="wrap">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php theme_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</div><!-- .wrap -->
	</header><!-- .entry-header -->

	<?php theme_post_thumbnail(); ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<div class="wrap">
			<?php theme_entry_footer(); ?>
		</div><!-- .wrap -->
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
