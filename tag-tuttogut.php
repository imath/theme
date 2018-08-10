<?php
/**
 * The template for the TuttoGut tag
 *
 * @since 1.0.2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Thème
 */

get_header(); ?>

	<main id="primary" class="site-main">

	<?php
	if ( have_posts() ) : ?>

		<header class="page-header">
			<div class="wrap">
                <img alt="logo Gutenberg" src="<?php echo esc_url( get_parent_theme_file_uri( '/assets/images/g-w.svg' ) ); ?>" class="tag-header-logo" />

                <?php
                    add_filter( 'get_the_archive_title', 'tuttogut_get_tag_title', 10, 1 );
                    the_archive_title( '<h1 class="page-title">', '</h1>' );
                    remove_filter( 'get_the_archive_title', 'tuttogut_get_tag_title', 10, 1 );

					the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</div>
		</header><!-- .page-header -->

		<?php
		/* Start the Loop */
		while ( have_posts() ) : the_post();

			/*
				* Include the Post-Format-specific template for the content.
				* If you want to override this in a child theme, then include a file
				* called content-___.php (where ___ is the Post Format name) and that will be used instead.
				*/
			get_template_part( 'template-parts/content', get_post_format() );

		endwhile;

		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif; ?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
