<?php
/**
 * Theme Custom Templates singular template.
 *
 * @package Theme
 *
 * @since 1.0.0
 */
theme_get_template_part( 'header' );

while ( have_posts() ) : the_post();

	theme_get_template_part( 'body' );

endwhile; // End of the loop.
