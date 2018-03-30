<?php
/**
 * Thème functions
 *
 * @package Thème\inc
 *
 * @since  1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Gets the min suffix for CSS & JS.
 *
 * @since 1.0.0
 *
 * @return string The min suffix for CSS & JS.
 */
function theme_js_css_suffix() {
	$min = '.min';

	if ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG )  {
		$min = '';
	}

	/**
	 * Filter here to edit the min suffix.
	 *
	 * @since 1.0.0
	 *
	 * @param string $min The min suffix for CSS & JS.
	 */
	return apply_filters( 'theme_js_css_suffix', $min );
}

/**
 * Register Google Fonts
 *
 * @since 1.0.0
 *
 * @return string The fonts URL.
 */
function theme_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
	 * supported by Karla, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$notoserif = esc_html_x( 'on', 'Noto Serif font: on or off', 'theme' );

	if ( 'off' !== $notoserif ) {
		$font_families = array();
		$font_families[] = 'Noto Serif:400,400italic,700,700italic';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Enqueues Thème's specific CSS.
 *
 * @since 1.0.0
 */
function theme_styles() {
	$min = theme_js_css_suffix();
	$t   = theme();

	wp_register_style(
		'main-style',
		get_template_directory_uri() . "/assets/css/main{$min}.css",
		array(),
		$t->version
	);

	wp_register_style(
		'main-fonts',
		theme_fonts_url(),
		array(),
		null
	);

	// Enqueue the stylesheets.
	wp_enqueue_style(
		'main-blocks',
		get_template_directory_uri() . "/assets/css/blocks{$min}.css",
		array( 'main-fonts', 'main-style' ),
		$t->version
	);
}
add_action( 'wp_enqueue_scripts', 'theme_styles' );

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function theme_scripts() {
	$t = theme();

	wp_enqueue_script(
		'theme-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array(),
		$t->version,
		true
	);

	wp_enqueue_script(
		'theme-skip-link-focus-fix',
		get_template_directory_uri() . '/js/skip-link-focus-fix.js',
		array(),
		$t->version,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_scripts', 11 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Pied de page', 'theme' ),
		'id'            => 'footerbar',
		'description'   => esc_html__( 'Ajouter les widgets ici.', 'theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'theme_widgets_init' );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $template front-page.php.
 *
 * @return string The template to be used: blank if is_home() is true (defaults to index.php), else $template.
 */
function theme_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template', 'theme_front_page_template' );

/**
 * Get SVG icons attributes or a specific one.
 *
 * @param string $domain The supported service domain.
 * @return string|array  The requested icon or the full list.
 */
function theme_icons( $domain = '' ) {
	$icons = array(
		'twitter.com' => array(
			'd'    => 'M18.94 4.46c-0.49 0.73-1.11 1.38-1.83 1.9 0.010 0.15 0.010 0.31 0.010 0.47 0 4.85-3.69 10.44-10.43 10.44-2.070 0-4-0.61-5.63-1.65 0.29 0.030 0.58 0.050 0.88 0.050 1.72 0 3.3-0.59 4.55-1.57-1.6-0.030-2.95-1.090-3.42-2.55 0.22 0.040 0.45 0.070 0.69 0.070 0.33 0 0.66-0.050 0.96-0.13-1.67-0.34-2.94-1.82-2.94-3.6v-0.040c0.5 0.27 1.060 0.44 1.66 0.46-0.98-0.66-1.63-1.78-1.63-3.060 0-0.67 0.18-1.3 0.5-1.84 1.81 2.22 4.51 3.68 7.56 3.83-0.060-0.27-0.1-0.55-0.1-0.84 0-2.020 1.65-3.66 3.67-3.66 1.060 0 2.010 0.44 2.68 1.16 0.83-0.17 1.62-0.47 2.33-0.89-0.28 0.85-0.86 1.57-1.62 2.020 0.75-0.080 1.45-0.28 2.11-0.57z',
			'fill' => 'rgb(29, 161, 242)',
		),
		'paypal.me' => array(
			'paths' => array(
				'd'    => 'M 14.635 1.951 C 13.765 0.958 12.196 0.534 10.186 0.534 L 4.355 0.534 C 3.943 0.534 3.593 0.832 3.527 1.238 L 1.101 16.639 C 1.052 16.944 1.288 17.218 1.596 17.218 L 5.197 17.218 L 6.101 11.484 L 6.071 11.662 C 6.136 11.256 6.484 10.957 6.894 10.957 L 8.605 10.957 C 11.967 10.957 14.597 9.593 15.367 5.643 C 15.389 5.526 15.41 5.412 15.428 5.302 C 15.33 5.25 15.33 5.25 15.428 5.302 C 15.654 3.841 15.423 2.847 14.635 1.951',
				'fill' => '#27346A',
			),
			array(
				'd'    => 'M 7.478 4.775 C 7.577 4.728 7.683 4.705 7.792 4.705 L 12.366 4.705 C 12.906 4.705 13.41 4.739 13.875 4.813 C 14.003 4.836 14.131 4.859 14.258 4.889 C 14.44 4.928 14.618 4.976 14.794 5.035 C 15.02 5.11 15.232 5.2 15.428 5.302 C 15.654 3.841 15.423 2.847 14.635 1.951 C 13.765 0.958 12.196 0.534 10.186 0.534 L 4.355 0.534 C 3.943 0.534 3.593 0.834 3.527 1.238 L 1.101 16.639 C 1.052 16.944 1.288 17.218 1.596 17.218 L 5.197 17.218 L 7.072 5.321 C 7.109 5.084 7.26 4.879 7.478 4.775 Z',
				'fill' => '#27346A',
			),
			array(
				'd'    => 'M 15.367 5.643 C 14.597 9.593 11.967 10.957 8.605 10.957 L 6.894 10.957 C 6.483 10.957 6.136 11.256 6.071 11.662 L 4.947 18.794 C 4.906 19.059 5.111 19.3 5.38 19.3 L 8.414 19.3 C 8.773 19.3 9.08 19.039 9.135 18.684 L 9.165 18.529 L 9.738 14.905 L 9.775 14.704 C 9.829 14.35 10.136 14.087 10.494 14.087 L 10.949 14.087 C 13.89 14.087 16.191 12.893 16.864 9.438 C 17.146 7.995 17.001 6.789 16.257 5.943 C 16.032 5.687 15.751 5.475 15.428 5.302 C 15.41 5.412 15.389 5.526 15.367 5.643 Z',
				'fill' => '#2790C3',
			),
			array(
				'd'    => 'M 14.622 4.98 C 14.501 4.945 14.38 4.915 14.258 4.889 C 14.131 4.861 14.004 4.837 13.875 4.816 C 13.41 4.739 12.906 4.705 12.366 4.705 L 7.794 4.705 C 7.683 4.705 7.577 4.728 7.478 4.777 C 7.26 4.879 7.109 5.085 7.073 5.323 L 6.101 11.484 L 6.071 11.662 C 6.136 11.256 6.484 10.957 6.894 10.957 L 8.605 10.957 C 11.967 10.957 14.597 9.593 15.367 5.643 C 15.389 5.526 15.41 5.412 15.428 5.302 C 15.232 5.201 15.021 5.11 14.796 5.035 C 14.736 5.017 14.679 4.999 14.622 4.98',
				'fill' => '#1F264F',
			),
		),
	);

	if ( ! $domain || ! isset( $icons[ $domain ] ) ) {
		return $icons;
	}

	return $icons[ $domain ];
}
