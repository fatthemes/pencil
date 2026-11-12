<?php
/**
 * The template for displaying WooCommerce shop, product archive and product pages.
 *
 * @link https://woocommerce.com/document/woocommerce-theme-developer-handbook/
 *
 * @package pencil
 */

get_header();

$pencil_shop_sidebar = pencil_woocommerce_has_sidebar();
?>
<div class="row">
	<?php
	// The Shop Sidebar comes first: left of the products on desktop, above them on small screens.
	if ( $pencil_shop_sidebar ) {
		get_sidebar( 'shop' );
	}
	?>
	<div id="primary" class="content-area <?php echo esc_attr( $pencil_shop_sidebar ? 'col-md-8' : 'col-md-12' ); ?>">
	<main id="main" class="site-main" role="main">

		<?php woocommerce_content(); ?>

	</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .row -->
<?php
get_footer();
