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
	wp_enqueue_script( 'theme-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'theme_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function theme_customize_hero_js() {
	wp_enqueue_script( 'theme-customizer-controls', get_template_directory_uri() . '/js/customizer-controls.js', array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'theme_customize_hero_js' );

function theme_is_static_front_page() {
	return ( is_front_page() && ! is_home() );
}
