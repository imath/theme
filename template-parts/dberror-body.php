<?php
/**
 * Template part for displaying the database connexion error message.
 *
 * @package Thème
 */
?>

<article id="post-db-error">
	<div class="wrap">
		<h1 id="db-error-message" class="entry-title"><?php theme_dberror_message(); ?></h1>
	</div><!-- .wrap -->
</article><!-- #post-db-error -->

<?php get_footer();
