<?php
/**
 * Thème Theme Customizer
 *
 * @package Thème
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function theme_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Maintenance page
	$wp_customize->add_setting( 'maintenance_mode', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'maintenance_mode', array(
		'label'       => __( 'Maintenance', 'theme' ),
		'section'     => 'theme_options',
		'type'        => 'radio',
		'choices'     => array(
			0 => __( 'Pas de maintenance.', 'theme' ),
			1 => __( 'Maintenance en cours.', 'theme' ),
		),
	) );

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'theme_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'theme_customize_partial_blogdescription',
		) );
		$wp_customize->selective_refresh->add_partial( 'maintenance_mode', array(
			'selector'         => '#maintenance-mode',
			'render_callback'  => 'theme_display_maintenance_mode_info',
			'fallback_refresh' => true,
		) );
	}

	/**
	 * Theme options.
	 */
	$wp_customize->add_section(
		'theme_options', array(
			'title'    => __( 'Options du thème', 'theme' ),
			'priority' => 130, // Before Additional CSS.
		)
	);

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + theme_front_page_heroes() ); $i++ ) {
		$wp_customize->add_setting(
			'hero_' . $i, array(
				'default'           => false,
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'hero_' . $i, array(
				/* translators: %d is the front page section number */
				'label'           => sprintf( __( 'Contenu de la %de section', 'theme' ), $i ),
				'description'     => ( 1 !== $i ? '' : __( 'Choisir la page à mettre en une depuis les listes déroulantes. Ajouter une image à une section en réglant son image à la une dans son écran d\'édition. Les sections vides ne seront pas affichées.', 'theme' ) ),
				'section'         => 'theme_options',
				'type'            => 'dropdown-pages',
				'allow_addition'  => true,
				'active_callback' => 'theme_is_static_front_page',
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'hero_' . $i, array(
				'selector'            => '#hero' . $i,
				'render_callback'     => 'theme_front_page_hero',
				'container_inclusive' => true,
			)
		);
	}

	// Makes sure the DB Error Template is available for preview
	if ( get_option( 'theme_db_error_id' ) ) {
		// Theme login section.
		$wp_customize->add_section( 'theme_db_error', array(
			'title'    => __( 'Page d’erreur BDD', 'theme' ),
			'priority' => 135, // After Theme options.
		) );

		// Allow the admin to enable the login logo
		$wp_customize->add_setting( 'db_error_message', array(
			'default'           => __( 'Erreur de connexion à la base de données', 'theme' ),
			'sanitize_callback' => 'esc_textarea',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'db_error_message', array(
			'label'       => __( 'Message d’erreur de la BDD', 'theme' ),
			'description' => __( 'Personnalisez le message d’erreur de connexion à votre base de données.', 'theme' ),
			'section'     => 'theme_db_error',
			'type'        => 'textarea'
		) );
	}

	// Makes sure the Email Template is available for preview
	if ( get_option( 'theme_email_id' ) ) {
		// Theme email section.
		$wp_customize->add_section( 'theme_email', array(
			'title'    => __( 'Modèle d’email', 'theme' ),
			'priority' => 140, // After Theme options.
		) );

		// Allow the admin to disable the email logo
		$wp_customize->add_setting( 'disable_email_logo', array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( 'disable_email_logo', array(
			'label'   => __( 'Intégrer le logo du site dans l’e-mail', 'theme' ),
			'section' => 'theme_email',
			'type'    => 'radio',
			'choices' => array(
				0 => __( 'Oui', 'theme' ),
				1 => __( 'Non', 'theme' ),
			),
			'active_callback' => 'theme_has_custom_logo',
		) );

		// Allow the admin to disable the email sitename
		$wp_customize->add_setting( 'disable_email_sitename', array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( 'disable_email_sitename', array(
			'label'   => __( 'Intégrer le nom du site dans l’e-mail', 'theme' ),
			'section' => 'theme_email',
			'type'    => 'radio',
			'choices' => array(
				0 => __( 'Oui', 'theme' ),
				1 => __( 'Non', 'theme' ),
			),
		) );

		// Allow the admin to customize the header's text color.
		$wp_customize->add_setting( 'email_header_text_color', array(
			'default'           => '#23282d',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'email_header_text_color', array(
			'label'   => __( 'Couleur du texte de l’en-tête', 'theme' ),
			'section' => 'theme_email',
		) ) );

		// Allow the admin to customize the header's background color.
		$wp_customize->add_setting( 'header_background_color', array(
			'default'           => '#FFF',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_background_color', array(
			'label'   => __( 'Couleur d’arrière plan de l’en-tête', 'theme' ),
			'section' => 'theme_email',
		) ) );

		// Allow the admin to customize the header's underline color.
		$wp_customize->add_setting( 'header_line_color', array(
			'default'           => '#23282d',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_line_color', array(
			'label'   => __( 'Couleur de soulignement de l’en-tête', 'theme' ),
			'section' => 'theme_email',
		) ) );

		// Allow the admin to customize the body's text color.
		$wp_customize->add_setting( 'email_body_text_color', array(
			'default'           => '#23282d',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'email_body_text_color', array(
			'label'   => __( 'Couleur du texte du corps du message', 'theme' ),
			'section' => 'theme_email',
		) ) );

		// Allow the admin to customize the body's background color.
		$wp_customize->add_setting( 'body_background_color', array(
			'default'           => '#FFF',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_background_color', array(
			'label'   => __( 'Couleur d’arrière plan du corps du message', 'theme' ),
			'section' => 'theme_email',
		) ) );

		// Allow the admin to customize the link's text color.
		$wp_customize->add_setting( 'email_link_text_color', array(
			'default'           => '#23282d',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'email_link_text_color', array(
			'label'   => __( 'Couleur des liens', 'theme' ),
			'section' => 'theme_email',
		) ) );
	}

	// Makes sure the Login Template is available for preview
	if ( get_option( 'theme_login_id' ) ) {
		// Theme login section.
		$wp_customize->add_section( 'theme_login', array(
			'title'    => __( 'Formulaire de connexion', 'theme' ),
			'priority' => 145, // After Theme options.
		) );

		// Allow the admin to enable the login logo
		$wp_customize->add_setting( 'enable_login_logo', array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( 'enable_login_logo', array(
			'label'           => __( 'Remplacer le logo de WordPress par l’icône du site', 'theme' ),
			'section'         => 'theme_login',
			'type'            => 'radio',
			'choices'         => array(
				0 => __( 'Non', 'theme' ),
				1 => __( 'Oui', 'theme' ),
			),
			'active_callback' => 'theme_has_site_icon',
		) );

		// Allow the admin to enable the login custom header
		$wp_customize->add_setting( 'enable_login_custom_header', array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( 'enable_login_custom_header', array(
			'label'           => __( 'Intégrer l’arrière plan du site.', 'theme' ),
			'section'         => 'theme_login',
			'type'            => 'radio',
			'choices'         => array(
				0 => __( 'Non', 'theme' ),
				1 => __( 'Oui', 'theme' ),
			)
		) );
	}
}
add_action( 'customize_register', 'theme_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function theme_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function theme_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function theme_customize_preview_js() {
	$min = theme_js_css_suffix();

	wp_enqueue_script( 'theme-customizer', get_template_directory_uri() . "/js/customizer{$min}.js", array( 'customize-preview' ), theme()->version, true );
}
add_action( 'customize_preview_init', 'theme_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function theme_customize_control_js() {
	$min = theme_js_css_suffix();

	wp_enqueue_script( 'theme-customizer-controls', get_template_directory_uri() . "/js/customizer-controls{$min}.js", array(), theme()->version, true );

	$tpl_ids = array(
		'email'    => (int) get_option( 'theme_email_id', 0 ),
		'login'    => (int) get_option( 'theme_login_id', 0 ),
		'db_error' => (int) get_option( 'theme_db_error_id', 0 ),
	);

	wp_localize_script( 'theme-customizer-controls', 'themeVars', array(
		'dbErrorlUrl' => esc_url_raw( get_permalink( $tpl_ids['db_error'] ) ),
		'emailUrl'    => esc_url_raw( get_permalink( $tpl_ids['email'] ) ),
		'loginlUrl'   => esc_url_raw( get_permalink( $tpl_ids['login'] ) ),
	) );
}
add_action( 'customize_controls_enqueue_scripts', 'theme_customize_control_js' );

function theme_is_static_front_page() {
	return ( is_front_page() && ! is_home() );
}

/**
 * Is there a custom logo for the site ?
 *
 * @since 1.0.0.
 *
 * @return bool True if a custom logo is activated. False otherwise.
 */
function theme_has_custom_logo() {
	return (bool) has_custom_logo();
}

/**
 * Is there site icon for the site ?
 *
 * @since 1.0.0.
 *
 * @return bool True if a site icon is activated. False otherwise.
 */
function theme_has_site_icon() {
	return (bool) get_site_icon_url( 84 );
}

/**
 * Adds a container to inform about the maintenance mode inside the customizer only.
 *
 * @since 1.0.0
 */
function theme_display_maintenance_mode() {
	if ( ! is_customize_preview() ) {
		return;
	}

	?>
	<div id="maintenance-mode"><?php theme_display_maintenance_mode_info(); ?></div>
	<?php
}

/**
 * Partial render callback for the maintenance mode.
 *
 * @since 1.0.0
 */
function theme_display_maintenance_mode_info() {
	if ( ! get_theme_mod( 'maintenance_mode' ) ) {
		$class = 'off';
		$icon  = theme_get_icon( theme_icons( 'maintenance.off' ) );
	} else {
		$class = 'on';
		$icon  = theme_get_icon( theme_icons( 'maintenance.on' ) );;
	}

	printf( '<div class="%1$s">%2$s</div>', $class, $icon );
}
