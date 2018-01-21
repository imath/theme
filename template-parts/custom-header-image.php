<?php
/**
 * Template part for the custom header image
 *
 * @package Thème
 */

?>
<div class="custom-header">

		<div class="custom-header-media">
			<?php the_custom_header_markup(); ?>
		</div>

	<?php get_template_part( 'template-parts/custom-header', 'branding' ); ?>

</div><!-- .custom-header -->
