<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Thème
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function theme_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Add class on front page.
	if ( is_front_page() && 'posts' !== get_option( 'show_on_front' ) ) {
		$classes[] = 'front-page';
	}

	// Add a class if there is a custom header.
	if ( has_header_image() ) {
		$classes[] = 'has-header-image';
	}

	return $classes;
}
add_filter( 'body_class', 'theme_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function theme_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'theme_pingback_header' );

function theme_get_thumbnail_credit( $attr, WP_Post $attachment ) {
	if ( ! empty( $attachment->post_content ) ) {
		theme()->thumbnail_overlay = $attachment->post_content;
	}

	return $attr;
}

function theme_front_page_hero( $partial = null, $id = 0 ) {
	if ( is_a( $partial, 'WP_Customize_Partial' ) ) {
		$id                   = str_replace( 'hero_', '', $partial->id );
		theme()->hero_counter = $id;
	}

	global $post; // Modify the global post object before setting up post data.
	if ( get_theme_mod( 'hero_' . $id ) ) {
		$post = get_post( get_theme_mod( 'hero_' . $id ) );
		setup_postdata( $post );
		set_query_var( 'hero', $id );

		get_template_part( 'template-parts/content', 'hero' );

		wp_reset_postdata();
	} elseif ( is_customize_preview() ) {
		// The output placeholder anchor.
		echo '<article class="hero-placeholder hero theme-hero" id="hero' . $id . '"><span class="theme-hero-title">' . sprintf( __( 'Section de la page d\'accueil %1$s', 'twentyseventeen' ), $id ) . '</span></article>';
	}
}

function theme_front_page_heroes() {
	/**
	 * Filter number of front page sections in Twenty Seventeen.
	 *
	 * @since Theme 1.0
	 *
	 * @param int $num_sections Number of front page sections.
	 */
	return (int) apply_filters( 'theme_front_page_heroes', 2 );
}

function theme_hero_count() {
	$hero_count = 0;

	// Create a setting and control for each of the hero sections available in the theme.
	for ( $i = 1; $i < ( 1 + theme_front_page_heroes() ); $i++ ) {
		if ( get_theme_mod( 'hero_' . $i ) ) {
			$hero_count++;
		}
	}

	return $hero_count;
}
