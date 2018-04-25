<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Thème
 */

get_header(); ?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<header class="page-header">
				<div class="wrap">
					<h1 class="page-title"><?php esc_html_e( 'Aïe! Cette page est inexistante.', 'theme' ); ?></h1>
				</div><!-- .wrap -->
			</header><!-- .page-header -->

			<div class="page-content">
				<div class="wrap">
					<p><?php esc_html_e( 'Le site n’a pas trouvé ce que vous recherchez. Merci d’essayez l’un des liens ci-dessous ou d’utiliser le formulaire de recherche.', 'theme' ); ?></p>

					<?php get_template_part( 'template-parts/content', 'widgets' ); ?>
				</div><!-- .wrap -->
			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
