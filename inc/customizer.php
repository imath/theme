<?php
/**
 * Thème Theme Customizer
 *
 * @package Thème
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function theme_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'theme_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'theme_customize_partial_blogdescription',
		) );
	}

	/**
	 * Theme options.
	 */
	$wp_customize->add_section(
		'theme_options', array(
			'title'    => __( 'Theme Options', 'theme' ),
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
	wp_enqueue_script( 'theme-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), theme()->version, true );
}
add_action( 'customize_preview_init', 'theme_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function theme_customize_control_js() {
	wp_enqueue_script( 'theme-customizer-controls', get_template_directory_uri() . '/js/customizer-controls.js', array(), theme()->version, true );

	$tpl_ids = array(
		'email'    => (int) get_option( 'theme_email_id', 0 ),
		'login'    => (int) get_option( 'theme_login_id', 0 ),
		'db_error' => (int) get_option( 'theme_db_error_id', 0 ),
	);

	wp_localize_script( 'theme-customizer-controls', 'themeVars', array(
		'dbErrorlUrl' => esc_url_raw( get_permalink( $tpl_ids['db_error'] ) ),
		'emailUrl'    => esc_url_raw( get_permalink( $tpl_ids['email'] ) ),
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
