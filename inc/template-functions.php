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

/**
 * Makes sure the Entrepôt icon is colored in black.
 *
 * @since 1.0.0
 */
function theme_print_entrepot_embed_styles() {
	$post = get_post();

	if ( empty( $post->post_name ) || 'entrepot' !== $post->post_name ) {
		return;
	}
	?>
	<style type="text/css">
		body.entrepot .wp-embed-featured-image img {
			-webkit-filter: invert(100%);
			filter: invert(100%);
		}
	</style>
	<?php
}
add_action( 'embed_head', 'theme_print_entrepot_embed_styles', 20 );

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
		echo '<article class="hero-placeholder hero theme-hero" id="hero' . $id . '"><span class="theme-hero-title">' . sprintf( __( 'Section de la page d\'accueil %1$s', 'theme' ), $id ) . '</span></article>';
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
 * @param array $url The matching href attribute's value.
 * @return string The Twitter action link.
 */
function theme_get_twitter_link( $url = array() ) {
	$twitter_username = str_replace( 'https://twitter.com/', '', reset( $url ) );
	$link             = '';
	$title            = '';
	$queried_object   = get_queried_object();

	if ( is_a( $queried_object,'WP_Term' ) ) {
		$link = get_term_link( $queried_object );
		$title = get_term_field( 'name', $queried_object );
	} elseif( is_a( $queried_object,'WP_Post' ) ) {
		$link  = get_permalink( $queried_object );
		$title = get_the_title( $queried_object );
	} else {
		$link  = $_SERVER['REQUEST_URI'];
		$title_parts = explode( wptexturize( apply_filters( 'document_title_separator', '-' ) ), wp_get_document_title() );
		$title = trim( reset( $title_parts ), ' ' );
	}

	return sprintf( 'https://twitter.com/intent/tweet?original_referer=%1$s&amp;source=tweetbutton&amp;text=%2$s&amp;url=%1$s&amp;via=%3$s',
		urlencode( $link ),
		urlencode( $title ),
		esc_attr( $twitter_username )
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
	foreach ( $icons as $key => $attrs ) {
		if ( false !== strpos( $item_output, $key ) ) {
			if ( 'twitter.com' === $key && 'navigation-top' === $args->theme_location ) {
				$item_output = preg_replace_callback( '/(?<=href=\").+(?=\")/', 'theme_get_twitter_link', $item_output );
			}

			$item_output = str_replace(
				$item->title,
				theme_get_icon( $attrs ) . '<span class="screen-reader-text">' . $item->title . '</span>',
				$item_output
			);
		}
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'theme_navigation_menu_icons', 10, 4 );

/**
 * Outputs the login's preview screen body classes.
 *
 * @since  1.0.0
 */
function theme_login_classes() {
	$action  = theme_login_get_action();
	$classes = array(
		'login-action-' . $action,
		'wp-core-ui',
		'rtl',
		'locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) ),
	);

	if ( ! is_rtl() ) {
		unset( $classes[2] );
	}

	if ( isset( $_POST['stage'] ) ) {
		array_push( $classes, sanitize_html_class( $_POST['stage'] ) );
	}

	/**
	 * Filters the login page body classes.
	 *
	 * @since WordPress 3.5.0
	 *
	 * @param array  $classes An array of body classes.
	 * @param string $action  The action that brought the visitor to the login page.
	 */
	$classes = apply_filters( 'login_body_class', $classes, $action );

	echo 'login ' . join( ' ', $classes );
}

/**
 * Apply the signup/activate header hooks when requested.
 *
 * @since 1.0.0
 *
 * @param  string $hook The name of the hook to call.
 */
function theme_ms_register_header( $hook = 'do_signup_header' ) {
	add_action( 'login_head', $hook );
	add_action( 'login_head', 'wp_no_robots' );
	?>
	<meta name="viewport" content="width=device-width" />
	<?php
}

/**
 * Add Sharing cards to single posts/pages
 *
 * @since 1.0.0
 *
 * @return string Facbook OG & Twitter meta tags.
 */
function theme_sharing_cards() {
	if ( ( ! is_category() && ! is_tag() && ! is_singular() ) || is_customize_preview() ) {
		return;
	}

	$metas = array(
		'twitter:card'        => 'summary_large_image',
		'twitter:site'        => '@imath',
		'twitter:title'       => '',
		'twitter:description' => '',
		'og:url'              => '',
		'og:type'             => '',
		'og:title'            => '',
		'og:description'      => '',
	);

	if ( is_tag() || is_category() ) {
		$object = get_queried_object();

		$title       = wp_strip_all_tags( $object->name );
		$description = wp_strip_all_tags( $object->description );
		$taxonomy    = get_taxonomy( $object->taxonomy );

		$metas = array_merge( $metas, array(
			'twitter:title'       => $title,
			'twitter:description' => $description,
			'og:url'              => esc_url_raw( get_term_link( $object ) ),
			'og:type'             => wp_strip_all_tags( $taxonomy->labels->singular_name ),
			'og:title'            => $title,
			'og:description'      => $description,
		) );

		if ( file_exists( get_parent_theme_file_path( '/assets/images/' . $object->slug . '.jpg' ) ) ) {
			$thumbnail = get_parent_theme_file_uri( '/assets/images/' . $object->slug . '.jpg' );
		}

	} else {
		$post = get_post();

		if ( ! empty( $post->post_excerpt ) ) {
			$description = strip_shortcodes( $post->post_excerpt );
		} elseif ( ! empty( $post->post_content ) ) {
			$description = strip_shortcodes( $post->post_content );
		} else {
			$description = get_bloginfo( 'description' );
		}

		$title       = wp_strip_all_tags( apply_filters( 'the_title', $post->post_title, $post->ID ) );
		$description = wp_strip_all_tags( apply_filters( 'the_excerpt', wp_trim_words( $description, 40, '...' ) ) );

		$metas = array(
			'twitter:card'        => 'summary_large_image',
			'twitter:site'        => '@imath',
			'twitter:title'       => $title,
			'twitter:description' => $description,
			'og:url'              => esc_url_raw( get_permalink( $post ) ),
			'og:type'             => esc_attr( get_post_type( $post ) ),
			'og:title'            => $title,
			'og:description'      => $description,
		);

		$thumbnail = get_the_post_thumbnail_url( $post );

		// No thumbnail ? Try to pick the first image in the content.
		if ( ! $thumbnail ) {
			preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $images );

			if ( isset( $images[1] ) ) {
				if ( is_array( $images[1] ) ) {
					$thumbnail = reset( $images[1] );
				} else {
					$thumbnail = $images[1];
				}
			}
		}
	}

	if ( empty( $metas['twitter:title'] ) || empty( $metas['twitter:description'] ) ) {
		return;
	}

	if ( empty( $thumbnail ) && empty( $metas['twitter:image'] ) ) {
		$metas['twitter:card'] = 'summary';

		$small_image = get_site_icon_url( 120 );

		if ( ! empty( $small_image ) ) {
			$metas['twitter:image'] = esc_url_raw( $small_image );
			$metas['og:image']      = esc_url_raw( $small_image );
		}
	} elseif ( empty( $metas['twitter:image'] ) ) {
		$metas['twitter:image'] = esc_url_raw( $thumbnail );
		$metas['og:image']      = esc_url_raw( $thumbnail );
	}

	foreach ( $metas as $meta_name => $meta_content ) {
		printf( '<meta name="%1$s" content="%2$s">' . "\n", esc_attr( $meta_name ), esc_attr( $meta_content ) );
	}
}
add_action( 'wp_head', 'theme_sharing_cards', 20 );

/**
 * Get The RSS svg for the TuttoGut tag
 *
 * @since 1.0.2
 */
function tuttogut_get_rss_svg() {
    $icons = theme_icons();
    return theme_get_icon( $icons[ home_url( 'feed' ) ] );
}

 /**
 * Filter to only keep the tag title
 *
 * @since 1.0.2
 */
function tuttogut_get_tag_title( $title = '' ) {
    $title = single_tag_title( '', false );
    $rss = sprintf( __( '<a href="%1$s" title="Abonnez vous à %2$s" class="tag-rss-header">%3$s</a>', 'theme' ),
        esc_url( get_tag_feed_link( get_queried_object()->term_id ) ),
        $title,
        tuttogut_get_rss_svg()
    );

    return $title . ' ' . $rss;
}
