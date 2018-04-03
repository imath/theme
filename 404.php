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

					<?php
						get_search_form();

						the_widget( 'WP_Widget_Recent_Posts' );
					?>

					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e( 'Catégories les plus utilisées :', 'theme' ); ?></h2>
						<ul>
						<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							) );
						?>
						</ul>
					</div><!-- .widget -->

					<?php

						/* translators: %1$s: smiley */
						$archive_content = '<p>' . sprintf( esc_html__( 'Autrement, vous pouvez fouiller dans les archives du site %1$s', 'theme' ), convert_smilies( ':)' ) ) . '</p>';
						the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );

						the_widget( 'WP_Widget_Tag_Cloud' );
					?>
				</div><!-- .wrap -->
			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
