<?php
/**
 * Login body's customizer template
 *
 * @package Thème
 *
 * @since 1.0.0
 */
	 	switch ( theme_login_get_action() ) :

	 		case 'lostpassword' :
	 			get_template_part( 'template-parts/login', 'lostpassword' );
	 			break;

	 		case 'register' :
	 			if ( is_multisite() && theme_is_main_site() ) {
	 				get_template_part( 'template-parts/login', 'msregister' );
	 			} else {
	 				get_template_part( 'template-parts/login', 'register' );
	 			}
	 			break;

	 		case 'login' :
	 		default      :
				get_template_part( 'template-parts/login', 'login' );
				break;

		endswitch ;

		get_template_part( 'template-parts/login', 'footer' );
