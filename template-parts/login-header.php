<?php
/**
 * Login header's template
 *
 * @package Thème\template-parts
 *
 * @since 1.0.0
 */

if ( is_customize_preview() && is_multisite() && theme_is_main_site() && 'register' === theme_login_get_action() ) {
	/**
	 * Fires before the site sign-up form.
	 *
	 * @since WordPress 3.0.0
	 */
	do_action( 'before_signup_header' );
}
?>
<!DOCTYPE html>
	<!--[if IE 8]>
		<html xmlns="http://www.w3.org/1999/xhtml" class="ie8" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if !(IE 8) ]><!-->
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php theme_login_document_title(); ?></title>
	<?php

	wp_enqueue_style( 'login' );

	/**
	 * Enqueue scripts and styles for the login page.
	 *
	 * @since WordPress 3.1.0
	 */
	do_action( 'login_enqueue_scripts' );

	/**
	 * Fires in the login page header after scripts are enqueued.
	 *
	 * @since WordPress 2.1.0
	 */
	do_action( 'login_head' ); ?>

	</head>
	<body class="<?php theme_login_classes(); ?>">
	<?php
	/**
	 * Fires in the login page header after the body tag is opened.
	 *
	 * @since 4.6.0
	 */
	do_action( 'login_header' ); ?>

	<div id="login">
		<h1>
			<a href="<?php theme_login_url(); ?>" title="<?php theme_login_title(); ?>" tabindex="-1"><?php bloginfo( 'name' ); ?></a>
		</h1>

		<?php if ( 'lostpassword' === theme_login_get_action() ) : ?>
			<p class="message">
				<?php esc_html_e( 'Veuillez saisir votre identifiant ou votre adresse de messagerie. Un lien permettant de créer un nouveau mot de passe vous sera envoyé par e-mail.', 'theme' ); ?>
			</p>
		<?php elseif ( 'register' === theme_login_get_action() && ! is_multisite() ) : ?>
			<p class="message">
				<?php esc_html_e( 'S’inscrire sur ce site', 'theme' ); ?>
			</p>
		<?php endif ; ?>
