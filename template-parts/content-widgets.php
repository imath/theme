<?php
/**
 * The template for displaying 404 or Search page widgets
 *
 * @package Thème
 */

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
