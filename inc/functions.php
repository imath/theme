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
	$min = theme_js_css_suffix();
	$t = theme();

	wp_enqueue_script(
		'theme-navigation',
		get_template_directory_uri() . "/js/navigation{$min}.js",
		array(),
		$t->version,
		true
	);

	wp_enqueue_script(
		'theme-skip-link-focus-fix',
		get_template_directory_uri() . "/js/skip-link-focus-fix{$min}.js",
		array(),
		$t->version,
		true
	);

	if ( is_singular() && comments_open() ) {
		if ( ! is_user_logged_in() ) {
			wp_enqueue_script(
				'theme-recaptcha',
				sprintf( 'https://www.google.com/recaptcha/api.js?onload=themeCommentonSubmit&render=%s', G_RECAPTCHA_KEY ),
				'array',
				3,
				true
			);

			wp_add_inline_script(
				'theme-recaptcha',
				sprintf(
					'( function() {
						window.themeCommentonSubmit = function() {
							grecaptcha.ready( function() {
								grecaptcha.execute( \'%s\', {
									action: \'submit\'
								} ).then( function( token ) {
									// Add the token to the "primary" form
									var input = document.createElement( \'input\' );
									input.setAttribute( \'type\', \'hidden\' );
									input.setAttribute( \'name\', \'_themeCaptcha_v3_token\' );
									input.setAttribute( \'value\', token );
									document.getElementById( \'theme-comment-form\' ).appendChild( input );
								} );
							} );
						}
					} )();',
					G_RECAPTCHA_KEY
				)
			);
		}

		if ( get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'theme_scripts', 11 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function theme_widgets_init() {
	$sidebars = array(
		'sidebar-left' => array(
			'name' => esc_html__( 'Pied de page (gauche)', 'theme' ),
			'id'   => 'sidebar-left',
		),
		'sidebar-center' => array(
			'name' => esc_html__( 'Pied de page (centre)', 'theme' ),
			'id'   => 'sidebar-center',
		),
		'sidebar-right' => array(
			'name' => esc_html__( 'Pied de page (droite)', 'theme' ),
			'id'   => 'sidebar-right',
		),
	);

	foreach ( $sidebars as $sidebar ) {
		register_sidebar( array_merge( $sidebar, array(
			'description'   => esc_html__( 'Ajouter les widgets ici.', 'theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) ) );
	}
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
			'd'    => 'M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z',
			'fill' => '#23282d',
		),
		'maintenance.off' => array(
			'd'    => 'M5 4l10 6-10 6v-12z',
			'fill' => '#FFF',
		),
		'maintenance.on' => array(
			'd'    => 'M5 16v-12h3v12h-3zM12 4h3v12h-3v-12z',
			'fill' => '#FFF',
		),
		'maintenance.wp' => array(
			'd'    => 'M20 10c0-5.52-4.48-10-10-10s-10 4.48-10 10 4.48 10 10 10 10-4.48 10-10zM10 1.010c4.97 0 8.99 4.020 8.99 8.99s-4.020 8.99-8.99 8.99-8.99-4.020-8.99-8.99 4.020-8.99 8.99-8.99zM8.010 14.82l-3.050-8.21c0.49-0.030 1.050-0.080 1.050-0.080 0.43-0.050 0.38-1.010-0.060-0.99 0 0-1.29 0.1-2.13 0.1-0.15 0-0.33 0-0.52-0.010 1.44-2.17 3.9-3.6 6.7-3.6 2.090 0 3.99 0.79 5.41 2.090-0.6-0.080-1.45 0.35-1.45 1.42 0 0.66 0.38 1.22 0.79 1.88 0.31 0.54 0.5 1.22 0.5 2.21 0 1.34-1.27 4.48-1.27 4.48l-2.71-7.5c0.48-0.030 0.75-0.16 0.75-0.16 0.43-0.050 0.38-1.1-0.050-1.080 0 0-1.3 0.11-2.14 0.11-0.78 0-2.11-0.11-2.11-0.11-0.43-0.020-0.48 1.060-0.050 1.080l0.84 0.080 1.12 3.040zM14.030 16.97l2.61-6.97s0.67-1.69 0.39-3.81c0.63 1.14 0.94 2.42 0.94 3.81 0 2.96-1.56 5.58-3.94 6.97zM2.68 6.77l3.82 10.48c-2.67-1.3-4.47-4.080-4.47-7.25 0-1.16 0.2-2.23 0.65-3.23zM10.13 11.3l2.29 6.25c-0.75 0.27-1.57 0.42-2.42 0.42-0.72 0-1.41-0.11-2.060-0.3',
			'fill' => '#767676',
		),
		home_url( 'feed' ) => array(
			'd'    => 'M14.92 18h3.080c0-8.68-7.18-15.75-16-15.75v3.020c7.12 0 12.92 5.71 12.92 12.73zM9.48 18h3.080c0-5.73-4.74-10.4-10.56-10.4v3.020c2 0 3.87 0.77 5.29 2.16 1.41 1.39 2.19 3.25 2.19 5.22zM4.13 17.98c1.17 0 2.13-0.93 2.13-2.090 0-1.15-0.96-2.090-2.13-2.090-1.18 0-2.13 0.94-2.13 2.090 0 1.16 0.95 2.090 2.13 2.090z',
			'fill' => '#d98500',
		),
	);

	if ( ! $domain || ! isset( $icons[ $domain ] ) ) {
		return $icons;
	}

	return $icons[ $domain ];
}

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
 * Checks if the email logo should be used.
 *
 * @since  1.0.0
 *
 * @return boolean True if the email logo should be used. False otherwise.
 */
function theme_use_email_logo() {
	return (bool) has_site_icon() && ! get_theme_mod( 'disable_email_logo' );
}

/**
 * Prints the content of the CSS file used to style the emails.
 *
 * @since 1.0.0
 */
function theme_email_print_css() {
	/**
	 * Filter here to replace the base email CSS rules.
	 *
	 * @since 1.0.0
	 *
	 * @param string Absolute file to the css file.
	 */
	$css = apply_filters( 'theme_email_get_css', sprintf( '%1$semail%2$s.css',
		get_theme_file_path( 'assets/css/' ),
		theme_js_css_suffix()
	) );

	// Directly insert it into the email template.
	if ( $css && file_exists( $css ) ) {
		include( $css );
	}

	$link_color = get_theme_mod( 'email_link_text_color' );

	// Add css overrides for the links
	if ( '#23282d' !== $link_color ) {
		printf( '
			a,
			a:hover,
			a:visited,
			a:active {
				color: %s;
			}
		', esc_attr( $link_color ) );
	}

	// Add css overrides for the text color of the header
	if ( is_customize_preview() ) {
		echo '
			tr { border-bottom: none; }
			table { margin: 0; }
			a { text-decoration: underline !important; }
			.container-padding.header a.custom-logo-link { padding: 0; }
		';
	}
}

/**
 * Uses a template to render emails
 *
 * @since  1.0.0
 *
 * @param  string       $text The text of the email.
 * @return string|false       The html text for the email, or false.
 */
function theme_email_set_html_content( $text ) {
	if ( empty( $text ) ) {
		return false;
	}

	ob_start();
	get_template_part( 'email' );
	$email_template = ob_get_clean();

	if ( empty( $email_template ) ) {
		return false;
	}

	// Make sure the link to set or reset the password
	// will be clickable in text/html
	if ( did_action( 'retrieve_password_key' ) ) {
		preg_match( '/<(.+?)>/', $text, $match );

		if ( ! empty( $match[1] ) ) {

			$login_url = wp_login_url();
			$link      = "\n" . '<a href="' . $match[1] . '">' . $login_url . '</a>';

			if ( preg_match( '/[^<]' . addcslashes( $login_url, '/' ) . '/', $text ) ) {
				$text = preg_replace( '/[^<]' . addcslashes( $login_url, '/' ) . '/', $link, $text );
			} else {
				$text .= $link;
			}

			$text = str_replace( $match[0], '', $text );
		}
	}

	// Make sure the Post won't be embed.
	add_filter( 'pre_oembed_result', '__return_false' );

	$pagetitle = esc_attr( get_bloginfo( 'name', 'display' ) );
	$content   = apply_filters( 'the_content', $text );

	remove_filter( 'pre_oembed_result', '__return_false' );

	// Make links clickable
	$content = make_clickable( $content );

	$email = str_replace( '{{pagetitle}}', $pagetitle, $email_template );
	$email = str_replace( '{{content}}',   $content,   $email          );

	return $email;
}

/**
 * Uses a multipart/alternate email.
 *
 * NB: follow the progress made on
 * https://core.trac.wordpress.org/ticket/15448
 *
 * @since 1.0.0
 *
 * @param object $phpmailer The Mailer class.
 */
function theme_mailer_init( $phpmailer = null ) {
	if ( empty( $phpmailer->Body ) ) {
		return;
	}

	$html_content = theme_email_set_html_content( $phpmailer->Body );

	if ( $html_content ) {
		$phpmailer->AltBody = $phpmailer->Body;
		$phpmailer->Body    = $html_content;
	}
}
add_action( 'phpmailer_init', 'theme_mailer_init', 10, 1 );

/**
 * Returns a 'none' string.
 *
 * @since 1.0.0
 *
 * @return string 'none'.
 */
function theme__return_none() {
	return 'none';
}

/**
 * Checks if the theme is activated on the main site.
 *
 * @since 1.0.0
 *
 * @return boolean True if the theme is activated on the main site.
 *                 False otherwise.
 */
function theme_is_main_site() {
	return (int) get_current_network_id() === (int) get_current_blog_id();
}

/**
 * Makes sure the Posts query only contains the Maintenance page.
 *
 * @since 1.0.0
 *
 * @param  null   $return   A null value to use the regular WP Query.
 * @param  WP_Query $wq     The WP Query object.
 * @return null|array       Null if not on front end.
 *                          An array containing a Maintenance Post otherwise.
 */
function theme_maintenance_posts_pre_query( $return = null, WP_Query $wq ) {
	global $post;

	if ( ! $wq->is_main_query() || true === $wq->get( 'suppress_filters' ) || is_admin() ) {
		return $return;
	}

	// Set the queried object to avoid notices
	$wq->queried_object = get_post( (object) array(
		'ID'             => 0,
		'comment_status' => 'closed',
		'comment_count'  => 0,
		'post_type'      => 'maintenance',
		'post_title'     => __( 'Site en cours de maintenance', 'theme' ),
	) );

	$wq->queried_object_id = $wq->queried_object->ID;

	// Set the Posts list to be limited to our custom post.
	$posts = array( $wq->queried_object );

	// Reset some WP Query properties
	$wq->found_posts   = 1;
	$wq->max_num_pages = 1;
	$wq->posts         = $posts;
	$wq->post          = $wq->queried_object;
	$wq->post_count    = 1;

	foreach ( array(
		'is_home'       => true,
		'is_page'       => true,
		'is_single'     => false,
		'is_archive'    => false,
		'is_tax'        => false,
	) as $key => $conditional_tag ) {
		$wq->{$key} = (bool) $conditional_tag;
	}

	// Prevent a notice error.
	$post = $wq->queried_object;

	return $wq->posts;
}

/**
 * Gets the maintenance template file path.
 *
 * @since  1.0.0
 *
 * @return string The maintenance template file path.
 */
function theme_get_maintenance_template() {
	return get_theme_file_path( 'page-maintenance.php' );
}

/**
 * Put the site in Maintenance mode if needed.
 *
 * @since 1.0.0
 */
function theme_maintenance_init() {
	if ( is_admin() || current_user_can( 'maintenance_mode' ) ) {
		return;
	}

	if ( ! get_theme_mod( 'maintenance_mode' ) ) {
		return;
	}

	// Neutralize signups.
	add_filter( 'option_users_can_register', '__return_zero' );

	// Neutralize Multisite signups.
	if ( is_multisite() && theme_is_main_site() ) {
		add_filter( 'site_option_registration', 'theme__return_none' );
	}

	// Use the maintenance template
	add_filter( 'template_include', 'theme_get_maintenance_template', 12 );

	// Make sure nobody is filtering this anymore.
	remove_all_filters( 'posts_pre_query' );

	// Set the maintenance post.
	add_filter( 'posts_pre_query', 'theme_maintenance_posts_pre_query', 10, 2 );
}
add_action( 'after_setup_theme', 'theme_maintenance_init', 20 );

/**
 * Maps The maintenance mode capability.
 *
 * Allow the admin to set the 'maintenance_mode' cap to some users or roles
 * in case he wants to get feedbacks from them.
 *
 * @since 1.0.0
 *
 * @param  array   $caps    Capabilities for meta capability.
 * @param  string  $cap     Capability name.
 * @param  integer $user_id User id.
 * @param  mixed   $args    Arguments.
 * @return array            Actual capabilities for meta capability.
 */
function theme_map_meta_caps( $caps = array(), $cap = '', $user_id = 0, $args = array() ) {
	if ( 'maintenance_mode' !== $cap ) {
		return $caps;

	// Fallback to Admin only if the current user does not have the maintenance mode cap.
	} elseif ( empty( wp_get_current_user()->allcaps['maintenance_mode'] ) ) {
		$caps = array( 'manage_options' );
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'theme_map_meta_caps', 10, 4 );

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
 * Gets the active type of signups.
 *
 * @since  1.0.0
 *
 * @return string The active type of signups.
 */
function theme_active_signup() {
	/**
	 * Filters the type of site sign-up.
	 *
	 * @since WordPress 3.0.0
	 *
	 * @param string $active_signup String that returns registration type. The value can be
	 *                              'all', 'none', 'blog', or 'user'.
	 */
	return apply_filters( 'wpmu_active_signup', get_site_option( 'registration', 'none' ) );
}

/**
 * Use the Site's url for the login logo.
 *
 * @since  1.0.0
 *
 * @param  string $url URL of the login logo link.
 * @return string      URL of the login logo link.
 */
function theme_login_logo_url( $url = '' ) {
	if ( ! theme_is_main_site() ) {
		return $url;
	}

	return home_url( '/' );
}
add_filter( 'login_headerurl', 'theme_login_logo_url' );

/**
 * Gets the login's preview screen action.
 *
 * @since  1.0.0
 *
 * @return string The form action type.
 */
function theme_login_get_action() {
	$action = 'login';

	if ( isset( $_REQUEST['action'] ) ) {
		$action = $_REQUEST['action'];
	} else {
		$url_parts = explode( '/', wp_parse_url( $_SERVER['REQUEST_URI'] )['path'] );

		if ( 'wp-signup.php' === end( $url_parts ) ) {
			$action = 'register';
		} elseif ( 'wp-activate.php' === end( $url_parts ) ) {
			$action = 'activate';
		}
	}

	return apply_filters( 'theme_login_get_action', $action );
}

/**
 * Checks if the login logo should be used.
 *
 * @since  1.0.0
 *
 * @return boolean True if the login logo should be used. False otherwise.
 */
function theme_use_login_logo() {
	return has_site_icon() && (bool) get_theme_mod( 'enable_login_logo' );
}

/**
 * Customize the login screen look and feel.
 *
 * @since 1.0.0
 *
 * @return string CSS Outut.
 */
function theme_login_style() {
	$logo_rules = '';

	if ( theme_use_login_logo() ) {
		$logo_rules = sprintf( '
			#login h1 a {
				background-image: none, url(%s);
			}
		', esc_url_raw( get_site_icon_url( 84 ) ) );
	}

	$color_rules = file_get_contents( sprintf( '%1$slogin%2$s.css',
		get_theme_file_path( 'assets/css/' ),
		theme_js_css_suffix()
	) );

	$custom_header_rules = '';

	if ( get_theme_mod( 'enable_login_custom_header' ) ) {
		$custom_header = get_custom_header();

		$custom_header_rules = sprintf( '
			body.login {
				background-image: url( %s );
				background-size: cover;
				background-repeat: no-repeat;
			}

			body.login p#nav, body.login p#backtoblog {
				background: #FFF;
				-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
				padding: 8px 24px;
				margin-top: 0;
			}

			body.login p#nav a, body.login p#backtoblog a {
				color: #23282d;
			}

			body.login .privacy-policy-link,
			body.login .privacy-policy-link:hover,
			body.login .privacy-policy-link:visited,
			body.login .privacy-policy-link:focus {
				color: #fff;
				text-shadow: 1px 1px 2px rgba(0, 0, 0, 1);
			}
		', $custom_header->url );
	}

	$ms_rules = '';

	if ( ( did_action( 'before_signup_header' ) || did_action( 'activate_header' ) ) && theme_is_main_site() ) {
		$ms_rules = file_get_contents( sprintf( '%1$sms-register%2$s.css',
			get_theme_file_path( 'assets/css/' ),
			theme_js_css_suffix()
		) );
	}

	wp_add_inline_style( 'login', sprintf( '
		%1$s

		%2$s

		%3$s

		%4$s
	', $logo_rules, $color_rules, $custom_header_rules, $ms_rules ) );
}
add_action( 'login_enqueue_scripts', 'theme_login_style', 9 );

/**
 * Enqueues a specific script to improve the Blog registration form.
 *
 * @since 1.0.0
 */
function theme_signup_form_enqueue_js() {
	if ( ! theme_is_main_site() ) {
		return;
	}

	$min = theme_js_css_suffix();
	$t   = theme();

	wp_enqueue_script( 'theme-signup-form', get_stylesheet_directory_uri() . "/js/signup-form{$min}.js", array(), $t->version, true );
}
add_action( 'signup_blogform',     'theme_signup_form_enqueue_js' );
add_action( 'signup_extra_fields', 'theme_signup_form_enqueue_js' );

/**
 * Force embed tweets to be centered & vimeo videos size.
 *
 * @since  1.0.0
 */
function theme_oembed_fetch_url( $provider = '' ) {
	if ( false !== strpos( $provider, 'https://publish.twitter.com/oembed' ) ) {
		$provider = add_query_arg( 'align', 'center', $provider );
	} elseif ( false !== strpos( $provider, 'https://vimeo.com' ) ) {
		$provider = add_query_arg( array(
			'width'  => 740,
			'height' => 415,
		), $provider );
	}

	return $provider;
}
add_filter( 'oembed_fetch_url', 'theme_oembed_fetch_url', 10, 1 );

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

/**
 * Reverse the order when the TuttoGuts are displayed.
 *
 * @since 1.0.2
 */
function theme_tuttogut_reverse_order( WP_Query $wp_query ) {
	if ( 'tuttogut' !== $wp_query->get( 'tag' ) ) {
		return;
	}

	$wp_query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'theme_tuttogut_reverse_order', 10, 1 );

/**
 * Check the reCaptcha score before inserting the comment.
 *
 * @since 1.1.0
 *
 * @param array $comment_data The comment data.
 * @param array The comment data.
 */
function theme_preprocess_comment( $comment_data = array() ) {
	if ( is_user_logged_in() ) {
		return $comment_data;
	}

	if ( isset( $_POST['_themeCaptcha_v3_token'] ) ) {
		$verify = array(
			'secret'   => G_RECAPTCHA_SECRET,
			'remoteip' => $_SERVER['REMOTE_ADDR'],
			'response' => wp_unslash( $_POST['_themeCaptcha_v3_token'] ),
		);

		$resp = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array( 'body' => $verify ) );
		if ( is_wp_error( $resp ) || 200 != wp_remote_retrieve_response_code( $resp ) ) {
			$comment_data = array();
		}

		$result = json_decode( wp_remote_retrieve_body( $resp ), true );

		if ( ! isset( $result['success'] ) || true !== $result['success'] || ! isset( $result['score'] ) || 0.8 > $result['score'] ) {
			$comment_data = array();
		} else {
			$comment_data['comment_meta'] = array(
				'_recaptcha_score' => $result['score'],
			);
		}
	}

	if ( ! $comment_data ) {
		wp_die(
			'<h1>' . esc_html__( 'La vérification de votre commentaire a échoué.', 'theme' ) . '</h1>' .
			'<p>' . esc_html__( 'Assurez-vous que JavaScript soit bien activé sur votre navigateur avant se soumettre à nouveau votre commentaire. Si vous êtes un spammeur, merci de vous abstenir !', 'theme' ) . '</p>',
			__( 'Ouch!', 'theme' ),
			array(
				'response'          => 403,
				'back_link'         => 1,
			)
		);
	}

	return $comment_data;
}
add_action( 'preprocess_comment', 'theme_preprocess_comment', 0, 1 );
