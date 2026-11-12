<?php
/**
 * The sidebar containing the shop widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package pencil
 */

if ( ! is_active_sidebar( 'pencil-shop-sidebar' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area pencil-shop-sidebar col-md-4" role="complementary">
	<div class="theiaStickySidebar">
	<?php dynamic_sidebar( 'pencil-shop-sidebar' ); ?>
	</div>
</div><!-- #secondary -->
