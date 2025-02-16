<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Thème
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<div class="wrap">
		<?php
		// You can start editing here -- including this comment!
		if ( have_comments() ) : ?>
			<h2 class="comments-title">
				<?php
				$comment_count = get_comments_number();
				if ( 1 === $comment_count ) {
					printf(
						/* translators: 1: title. */
						esc_html_e( 'Un commentaire sur &ldquo;%1$s&rdquo;', 'theme' ),
						'<span>' . get_the_title() . '</span>'
					);
				} else {
					printf( // WPCS: XSS OK.
						/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( '%1$s commentaire sur &ldquo;%2$s&rdquo;', '%1$s commentaires sur &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'theme' ) ),
						number_format_i18n( $comment_count ),
						'<span>' . get_the_title() . '</span>'
					);
				}
				?>
			</h2><!-- .comments-title -->

			<?php the_comments_navigation(); ?>

			<ol class="comment-list">
				<?php
					wp_list_comments( array(
						'avatar_size' => 100,
						'style'       => 'ol',
						'short_ping'  => true,
					) );
				?>
			</ol><!-- .comment-list -->

			<?php the_comments_navigation();

			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() ) : ?>
				<p class="no-comments"><?php esc_html_e( 'Les commentaires sont fermés.', 'theme' ); ?></p>
			<?php
			endif;

		endif; // Check for have_comments().

		$comment_fields = array();

		if ( is_single() ) {
			$comment_fields = array(
				'comment_notes_before' => sprintf( '<p class="description">%1$s <a href="%2$s" class="comment-rss-link"><span class="screen-reader-text">%3$s</span>%4$s</a></p>',
					esc_html__( 'Restez informé·e des évolutions de la discussion en vous abonnant à son flux :', 'theme' ),
					get_post_comments_feed_link(),
					esc_html__( 'Flux RSS des commentaires', 'theme' ),
					theme_get_icon( theme_icons( home_url( 'feed' ) ) )
				),
			);
		}

		if ( is_singular() ) {
			$comment_fields['id_form'] = 'theme-comment-form';

			if ( ! is_user_logged_in() ) {
				$comment_fields['class_submit'] = 'submit g-recaptcha';
			}
		}

		comment_form( $comment_fields );
		?>
	</div><!-- .wrap -->
</div><!-- #comments -->
