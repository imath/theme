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
	);

	if ( ! $domain || ! isset( $icons[ $domain ] ) ) {
		return $icons;
	}

	return $icons[ $domain ];
}
