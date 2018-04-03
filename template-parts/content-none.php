<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Thème
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<div class="wrap">
			<h1 class="page-title"><?php esc_html_e( 'Aucun contenu n’est disponible pour le moment', 'theme' ); ?></h1>
		</div><!-- .wrap -->
	</header><!-- .page-header -->

	<div class="page-content">
		<div class="wrap">
			<?php
			if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

				<p><?php
					printf(
						wp_kses(
							/* translators: 1: link to WP admin new post page. */
							__( 'Prêt à publier votre premier article ? <a href="%1$s">Commencez en cliquant ici</a>.', 'theme' ),
							array(
								'a' => array(
									'href' => array(),
								),
							)
						),
						esc_url( admin_url( 'post-new.php' ) )
					);
				?></p>

			<?php elseif ( is_search() ) : ?>

				<p><?php esc_html_e( 'Désolé, aucun contenu ne correspond à votre recherche. Merci de réessayer avec d’autres mots clés.', 'theme' ); ?></p>
				<?php
					get_search_form();

			else : ?>

				<p><?php esc_html_e( 'Le site n’a pas trouvé ce que vous recherchez. Merci d’utilisez le formulaire de recherche.', 'theme' ); ?></p>
				<?php
					get_search_form();

			endif; ?>
		</div><!-- .wrap -->
	</div><!-- .page-content -->
</section><!-- .no-results -->
