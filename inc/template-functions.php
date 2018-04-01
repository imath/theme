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
	} else {
		$classes[] = get_post_field( 'post_name' );
	}

	// Add class if we're viewing the Customizer for easier styling of theme options.
	if ( is_customize_preview() ) {
		$classes[] = 'theme-customizer';
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

/**
 * Get the output for a SVG icon.
 *
 * @param array  $attrs The SVG icon attributes.
 * @param string $width The SVG icon width in pixels.
 * @param string $height The SVG icon height in pixels.
 * @return string HTML Output.
 */
function theme_get_icon( $attrs = array(), $width = 20, $height = 20 ) {
	if ( ! isset( $attrs['paths'] ) ) {
		$attrs = array( $attrs );
	}

	$svg = "\n";
	foreach ( $attrs as $path ) {
		$attributes = '';
		foreach( $path as $att => $v ) {
			$attributes .= sprintf( ' %1$s="%2$s"', sanitize_key( $att ), esc_attr( $v ) );
		}

		$svg .= sprintf( '<path%1$s/>%2$s', $attributes, "\n" );
	}


	return sprintf( '<svg class="icon" role="img" width="%1$dpx" height="%2$dpx">%3$s</svg>',
		intval( $width ),
		intval( $height ),
		$svg
	);
}

/**
 * Get the Twitter action link for the current page.
 *
 * @param string $url The matching href attribute's value.
 * @return string The Twitter action link.
 */
function theme_get_twitter_link( $url = '' ) {
	return sprintf( 'https://twitter.com/intent/tweet?original_referer=%1$s&amp;source=tweetbutton&amp;text=%2$s&amp;url=%1$s&amp;via=%3$s',
		urlencode( get_permalink() ),
		urlencode( get_the_title() ),
		esc_attr( get_bloginfo( 'name' ) )
	);
}

/**
 * Display SVG icons in menu.
 *
 * @param  string  $item_output The menu item output.
 * @param  WP_Post $item        Menu item object.
 * @param  int     $depth       Depth of the menu.
 * @param  array   $args        wp_nav_menu() arguments.
 * @return string  $item_output The menu item output with social icon.
 */
function theme_navigation_menu_icons( $item_output, $item, $depth, $args ) {
	// Get supported icons.
	$icons = theme_icons();

	// Change SVG icon inside menu if there is supported URL.
	if ( 'navigation-top' === $args->theme_location ) {
		foreach ( $icons as $key => $attrs ) {
			if ( false !== strpos( $item_output, $key ) ) {
				if ( 'twitter.com' === $key ) {
					$item_output = preg_replace_callback( '/(?<=href=\").+(?=\")/', 'theme_get_twitter_link', $item_output );
				}

				$item_output = str_replace(
					$item->title,
					theme_get_icon( $attrs ) . '<span class="screen-reader-text">' . $item->title . '</span>',
					$item_output
				);
			}
		}
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'theme_navigation_menu_icons', 10, 4 );
