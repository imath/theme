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
