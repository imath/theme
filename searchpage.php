<?php
/**
 * Template Name: Page de recherche
 * 
 * The template for displaying the search page
 *
 * @package Thème
 */

get_header(); ?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

		<div class="page-content">
			<div class="wrap">
				
				<?php get_template_part( 'template-parts/content', 'widgets' ); ?>
				
			</div><!-- .wrap -->
		</div><!-- .page-content -->

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
