<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Thème
 */

if ( ! is_active_sidebar( 'sidebar-left' ) || ! is_active_sidebar( 'sidebar-center' ) || ! is_active_sidebar( 'sidebar-right' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<div class="wrap">

		<div class="widget-column left-widgets">
			<?php dynamic_sidebar( 'sidebar-left' ); ?>
		</div>

		<div class="widget-column center-widgets">
			<?php dynamic_sidebar( 'sidebar-center' ); ?>
		</div>

		<div class="widget-column right-widgets">
			<?php dynamic_sidebar( 'sidebar-right' ); ?>
		</div>

	</div><!-- #wrap -->
</aside><!-- #secondary -->
