<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Thème
 */

if ( ! function_exists( 'theme_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function theme_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Publié le %s', 'post date', 'theme' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'par %s', 'post author', 'theme' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'theme_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function theme_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'theme' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Publié dans %1$s', 'theme' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'theme' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'étiquetté %1$s', 'theme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Ajouter un commentaire <span class="screen-reader-text"> à %s</span>', 'theme' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Modifier <span class="screen-reader-text">%s</span>', 'theme' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'theme_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function theme_post_thumbnail( $classes = array(), $size = 'post-thumbnail' ) {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	$theme   = theme();
	$classes = array_merge( (array) $classes, array( 'post-thumbnail' ) );
	$classes = array_map( 'sanitize_html_class', $classes );

	if ( is_singular() ) :
		// Add a temporary filter intercept the attachment object
		add_filter( 'wp_get_attachment_image_attributes', 'theme_get_thumbnail_credit', 10, 2 );

		$thumbnail = get_the_post_thumbnail( null, $size, '' );

		if ( false !== strpos( $thumbnail, 'srcset' ) ) {
			$classes[] = 'has-srcset';
		}
		?>

		<div class="<?php echo join( ' ', $classes ); ?>">
			<?php echo $thumbnail; ?>

			<?php if ( ! empty( $theme->thumbnail_overlay ) ) : ?>
				<small class="post-thumbnail-overlay">
					<?php echo apply_filters( 'the_content', $theme->thumbnail_overlay ) ; ?>
				</small>
			<?php endif ; ?>
		</div><!-- .post-thumbnail -->

		<?php
		// Remove the filter we used to intercept the attachment object
		remove_filter( 'wp_get_attachment_image_attributes', 'thaim_get_thumbnail_credit', 10, 2 );

	else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php the_post_thumbnail( $size, array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
	</a>

	<?php endif; // End is_singular()
}
endif;

if ( ! function_exists( 'theme_dberror_message' ) ) :
/**
 * Displays the DB Error Message.
 *
 * @since  1.0.0
 */
function theme_dberror_message() {
	$t = theme();

	if ( ! empty( $t->db_error_message ) ) {
		$message = $t->db_error_message;
	} else {
		$message = get_theme_mod( 'db_error_message' );
	}

	echo esc_html( $message );
}

endif;

if ( ! function_exists( 'theme_email_logo' ) ) :
/**
 * Displays the site logo into the email.
 *
 * @since  1.0.0
 */
function theme_email_logo() {
	if ( ! has_custom_logo() ) {
		return;
	}

	// Filter just before the custom logo tag to control its size in pixels.
	add_filter( 'wp_get_attachment_image_src', 'theme_email_logo_size', 10, 1 );
	?>
	<div id="site-logo">
		<?php the_custom_logo(); ?>
	</div>
	<?php

	// Stop filtering once it's no more needed.
	remove_filter( 'wp_get_attachment_image_src', 'theme_email_logo_size', 10, 1 );
}

endif;

if ( ! function_exists( 'theme_email_sitename' ) ) :
/**
 * Displays the site name into the email.
 *
 * @since  1.0.0
 */
function theme_email_sitename() {
	$name = get_bloginfo( 'name' );

	if ( ! $name ) {
		return;
	}

	echo esc_html( $name );
}

endif;

if ( ! function_exists( 'theme_email_title_text_color' ) ) :
/**
 * Outputs the Email's title color.
 *
 * @since 1.0.0
 */
function theme_email_title_text_color() {
	echo get_theme_mod( 'email_header_text_color' );
}

endif;

if ( ! function_exists( 'theme_email_title_bg_color' ) ) :
/**
 * Outputs the Email's title background color.
 *
 * @since 1.0.0
 */
function theme_email_title_bg_color() {
	echo get_theme_mod( 'header_background_color' );
}

endif;

if ( ! function_exists( 'theme_email_separator_color' ) ) :
/**
 * Outputs the Email's header underline color.
 *
 * @since 1.0.0
 */
function theme_email_separator_color() {
	echo get_theme_mod( 'header_line_color' );
}

endif;

if ( ! function_exists( 'theme_email_body_text_color' ) ) :
/**
 * Outputs the Email's body text color.
 *
 * @since 1.0.0
 */
function theme_email_body_text_color() {
	echo get_theme_mod( 'email_body_text_color' );
}

endif;

if ( ! function_exists( 'theme_email_body_link_color' ) ) :
/**
 * Outputs the Email's link color.
 *
 * @since 1.0.0
 */
function theme_email_body_link_color() {
	echo get_theme_mod( 'email_link_text_color' );
}

endif;

if ( ! function_exists( 'theme_email_body_bg_color' ) ) :
/**
 * Outputs the Email's body background color.
 *
 * @since 1.0.0
 */
function theme_email_body_bg_color() {
	echo get_theme_mod( 'body_background_color' );
}

endif;

if ( ! function_exists( 'theme_the_maintenance_title' ) ) :
/**
 * Outputs the Maintenance page title.
 *
 * @since 1.0.0
 */
function theme_the_maintenance_title() {
	$page = get_queried_object();

	echo apply_filters( 'the_title', $page->post_title, $page->ID );
}

endif;

if ( ! function_exists( 'theme_login_document_title' ) ) :
/**
 * Outputs the login's preview screen document title.
 *
 * @since 1.0.0
 */
function theme_login_document_title() {
	$separator = '&lsaquo;';

	if ( is_rtl() ) {
		$separator = '&rsaquo;';
	}

	$title  = __( 'Connexion', 'theme' );
	$action = theme_login_get_action();

	if ( 'lostpassword' === $action ) {
		$title  = __( 'Mot de passe oublié', 'theme' );
	} elseif ( 'register' === $action ) {
		$title  = __( 'Inscription', 'theme' );
	} elseif ( 'activate' === $action ) {
		$title  = __( 'Activation', 'theme' );
	}

	return printf( '%1$s %2$s %3$s',
		get_bloginfo( 'name', 'display' ),
		$separator,
		esc_html( $title )
	);
}

endif;

if ( ! function_exists( 'theme_login_submit_title' ) ) :
/**
 * Outputs the login's preview screen submit button value.
 *
 * @since 1.0.0
 */
function theme_login_submit_title() {
	esc_attr_e( 'Se connecter', 'theme' );
}

endif;

if ( ! function_exists( 'theme_login_url' ) ) :
/**
 * Outputs the login's preview screen header url.
 *
 * @since  1.0.0
 */
function theme_login_url() {
	printf( '%s', esc_url(
		/**
		 * Filters link URL of the header logo above login form.
		 *
		 * @since WordPress 2.1.0
		 *
		 * @param string $login_header_url Login header logo URL.
		 */
		apply_filters( 'login_headerurl', __( 'https://fr.wordpress.org/', 'theme' ) )
	) );
}

endif;

if ( ! function_exists( 'theme_login_title' ) ) :
/**
 * Outputs the login's preview screen header title.
 *
 * @since  1.0.0
 */
function theme_login_title() {
	printf( '%s', esc_attr(
		/**
		 * Filters the title attribute of the header logo above login form.
		 *
		 * @since WordPress 2.1.0
		 *
		 * @param string $login_header_title Login header logo title attribute.
		 */
		apply_filters( 'login_headertitle', __( 'Propulsé par WordPress', 'theme' ) )
	) );
}

endif;

if ( ! function_exists( 'theme_login_navigation' ) ) :
/**
 * Outputs the Login navigation.
 *
 * @since  1.0.0
 */
function theme_login_navigation() {
	$navlinks = array();

	$action       = theme_login_get_action();
	$registration = get_option( 'users_can_register' );
	$register     = '';

	if ( is_customize_preview() ) {
		$url = get_permalink( get_option( 'theme_login_id' ) );
	} else {
		$url = wp_login_url();
	}

	// urls
	$login = sprintf( '<a href="%1$s">%2$s</a>',
		esc_url( $url ),
		esc_html__( 'Connexion', 'theme' )
	);

	$lostpass = sprintf( '<a href="%1$s">%2$s</a>',
		esc_url( add_query_arg( 'action', 'lostpassword', $url ) ),
		esc_html__( 'Mot de passe oublié ?', 'theme' )
	);

	if ( $registration ) {
		$register = sprintf( '<a href="%1$s">%2$s</a>',
			esc_url( add_query_arg( 'action', 'register', $url ) ),
			esc_html__( 'Inscription', 'theme' )
		);
	}

	if ( 'login' === $action ) {
		array_push( $navlinks, $lostpass );

		if ( $register ) {
			array_unshift( $navlinks, $register );
		}
	} elseif ( 'lostpassword' === $action ) {
		array_push( $navlinks, $login );

		if ( $register ) {
			array_push( $navlinks, $register );
		}
	} elseif ( 'register' === $action ) {
		$navlinks = array( $login, $lostpass );
	}

	if ( ! $navlinks ) {
		return;
	}

	echo join( ' | ', $navlinks );
}

endif;
