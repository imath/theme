<?php
/**
 * Thème functions and definitions
 *
 * @package Thème
 *
 * @since  1.0.0
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Theme Bootstrap class
 *
 * @since  1.0.0
 */
final class Theme {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the theme
	 *
	 * @since  1.0.0
	 */
	private function __construct() {
		$this->supports();
		$this->inc();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since  1.0.0
	 */
	public static function start() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Set some globals for the theme
	 *
	 * @since  1.0.0
	 */
	private function supports() {
		$this->version = '1.0.0';
		$GLOBALS['content_width'] = 640;

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 800, 400, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'navigation-top'    => esc_html__( 'Navigation Supérieure', 'theme' ),
			'navigation-social' => esc_html__( 'Profils Sociaux', 'theme' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Gutenberg Theme features
		add_theme_support( 'editor-color-palette',
			array(
				'name'  => 'very dark blue',
				'color' => '#23282d',
			),
			array(
				'name'  => 'very light gray',
				'color' => '#eee',
			),
			array(
				'name'  => 'very dark gray',
				'color' => '#444',
			),
			array(
				'name'  => 'very dark cyan',
				'color' => '#044f51',
			)
		);

		add_theme_support( 'align-wide' );
	}

	/**
	 * Include required files
	 *
	 * @since  1.0.0
	 */
	private function inc() {
		// Custom functions
		require_once get_theme_file_path( '/inc/functions.php' );

		// Implement the Custom Header feature.
		require_once get_theme_file_path( '/inc/custom-header.php' );

		// Custom template tags for this theme.
		require_once get_theme_file_path( '/inc/template-tags.php' );

		// Functions which enhance the theme by hooking into WordPress.
		require_once get_theme_file_path( '/inc/template-functions.php' );

		// Customizer additions.
		require_once get_theme_file_path( '/inc/customizer.php' );

		// Translations
		load_theme_textdomain( 'theme', get_theme_file_path( '/languages' ) );
	}
}
/**
 * Start Thème
 *
 * @since  1.0.0
 *
 * @return The Theme main instance.
 */
function theme() {
	return Theme::start();
}
add_action( 'after_setup_theme', 'theme' );
