<?php
/**
 * The template for displaying the static front page.
 *
 * @package Thème
 */

get_header(); ?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.

		if ( 0 !== theme_hero_count() || is_customize_preview() ) : // If we have pages to show.
			theme()->hero_counter = 0;

			// Create a setting and control for each of the sections available in the theme.
			for ( $i = 1; $i < ( 1 + theme_front_page_heroes() ); $i++ ) {
				theme()->hero_counter = $i;
				theme_front_page_hero( null, $i );
			}

		endif; // The if ( 0 !== twentyseventeen_panel_count() ) ends here.
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
