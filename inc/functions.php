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
		'github.com' => array(
			'd' => 'M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z',
			'fill' => '#23282d',
		),
	);

	if ( ! $domain || ! isset( $icons[ $domain ] ) ) {
		return $icons;
	}

	return $icons[ $domain ];
}

/**
 * Make sure there's a version of the site icon for the login logo
 *
 * @since 1.0.0
 *
 * @param  array $icon_sizes The list of allowed icon sizes in Pixels.
 * @return array             The list of allowed icon sizes in Pixels.
 */
function theme_site_icon_size( $icon_sizes = array() ) {
	return array_merge( $icon_sizes, array( 84 ) );
}
add_filter( 'site_icon_image_sizes', 'theme_site_icon_size', 10, 1 );

/**
 * Registers a private Post Type to use for custom templates.
 *
 * @since 1.0.0
 */
function theme_register_template_post_type() {
	register_post_type( 'theme_tpl', array(
		'label'              => 'theme_template',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => false,
		'show_in_menu'       => false,
		'show_in_nav_menus'  => false,
		'query_var'          => false,
		'rewrite'            => false,
		'has_archive'        => false,
		'hierarchical'       => true,
	) );
}
add_action( 'init', 'theme_register_template_post_type' );

/**
 * Outputs a specific Theme template part.
 *
 * @since  1.0.0
 *
 * @param  string $part The part for the template to use.
 * @return string       The output for the template part.
 */
function theme_get_template_part( $part = '' ) {
	if ( ! is_singular( 'theme_tpl' ) || ! $part ) {
		return '';
	}

	$template = get_queried_object();

	if ( empty( $template->post_mime_type ) ) {
		return '';
	}

	return get_template_part( sprintf( 'template-parts/%s', $template->post_mime_type ), $part );
}

function theme_set_db_error_template( $value = '', $old_value = '' ) {
	$error_file =  WP_CONTENT_DIR . '/db-error.php';
	$exists     =  file_exists( $error_file );

	if ( $exists && ( ! $value || $value === $old_value ) ) {
		return $value;
	} else {
		if ( $exists ) {
			$is_writable = is_writeable( $error_file );
		} else {
			$is_writable = is_writable( dirname( $error_file ) ) && touch( $error_file );
		}

		if ( $is_writable ) {
			$t = theme();

			$t->db_error_message = $value;
			add_filter( 'show_admin_bar', '__return_false' );

			ob_start();
			get_template_part( 'template-parts/dberror', 'header' );
			get_template_part( 'template-parts/dberror', 'body' );
			$template = ob_get_clean();

			remove_filter( 'show_admin_bar', '__return_false' );
			unset( $t->db_error_message );

			$f = @fopen( $error_file, 'w' );
			fwrite( $f, $template );
			fclose( $f );
		}
	}

	return $value;
}
add_filter( 'pre_set_theme_mod_db_error_message', 'theme_set_db_error_template', 10, 2 );

/**
 * Upgrade the theme db version
 *
 * @since  1.0.0
 */
function theme_upgrade_theme() {
	if ( is_customize_preview() ) {
		return;
	}

	$db_version = get_option( 'theme_version', 0 );
	$version    = theme()->version;

	if ( ! version_compare( $db_version, $version, '<' ) ) {
		return;
	}

	$common_attributes = array(
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
		'post_status'    => 'private',
		'post_content'   => '',
		'post_type'      => 'theme_tpl',
	);

	$tpl_ids = array(
		'email'    => array(
			'ID'             => (int) get_option( 'theme_email_id', 0 ),
			'post_mime_type' => 'email',
			'post_title'     => __( 'Modèle d’e-mail', 'theme' ),
			'post_content'   => sprintf( '<p>%1$s</p><p>%2$s</p><p>%3$s</p>',
				__( 'Vous pouvez personnaliser le gabarit utilisé pour envoyer les e-mails de WordPress.', 'theme' ),
				__( 'Pour cela utilisez la colonne latérale pour spécifier vos préférences.', 'theme' ),
				__( 'Voici comment seront affichés les <a href="#">liens</a> contenus dans certains e-mails.', 'theme' )
			),
		),
		'login'    => array(
			'ID'             => (int) get_option( 'theme_login_id', 0 ),
			'post_mime_type' => 'login',
			'post_title'     => __( 'Formulaire de connexion', 'theme' ),
			'post_content'   => sprintf( '<p>%1s</p>',
				__( 'Cet article est utilisé pour personnaliser l’apparence du formulaire de connexion.', 'theme' )
			),
		),
		'db_error' => array(
			'ID'             => (int) get_option( 'theme_db_error_id', 0 ),
			'post_mime_type' => 'dberror',
			'post_title'     => __( 'Page d’erreur de connexion à la base de données', 'theme' ),
			'post_content'   => sprintf( '<p>%1s</p>',
				__( 'Cet article est utilisé pour personnaliser l’apparence de la page d’erreur de connexion à la base de données.', 'theme' )
			),
		),
	);

	// Install 1.0.0 if needed
	if ( (float) $db_version < 1.0 ) {
		// Init the Template ID.
		$tpl_id = 0;

		// Create the private posts.
		foreach ( $tpl_ids as $k_tpl => $v_tpl ) {
			$tpl_id = wp_insert_post( wp_parse_args( $v_tpl, $common_attributes ) );

			update_option( "theme_{$k_tpl}_id", $tpl_id );
		}
	}

	// Update version.
	update_option( 'theme_version', $version );
}
add_action( 'admin_init', 'theme_upgrade_theme', 1000 );
