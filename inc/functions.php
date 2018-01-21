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
