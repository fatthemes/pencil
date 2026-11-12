<?php
/**
 * WooCommerce compatibility. Only loaded when WooCommerce is active.
 *
 * @link https://woocommerce.com/
 *
 * @package pencil
 */

/**
 * Declares WooCommerce support and the product gallery features.
 */
function pencil_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'pencil_woocommerce_setup' );

/**
 * Registers the Shop Sidebar widget area.
 */
function pencil_woocommerce_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'pencil' ),
			'id'            => 'pencil-shop-sidebar',
			'description'   => esc_html__( 'Appears left of the products on the shop page and product category and tag pages, and above them on small screens. Leave empty for a full width shop.', 'pencil' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'pencil_woocommerce_widgets_init' );

/**
 * Whether the current page shows the Shop Sidebar.
 *
 * @return bool
 */
function pencil_woocommerce_has_sidebar() {
	return ( is_shop() || is_product_category() || is_product_tag() ) && get_theme_mod( 'pencil_woocommerce_sidebar', 1 ) && is_active_sidebar( 'pencil-shop-sidebar' );
}

/**
 * Whether the current page is a WooCommerce page, including the cart, checkout and account pages.
 *
 * @return bool
 */
function pencil_woocommerce_is_shop_area() {
	return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Loads the WooCommerce styles after WooCommerce's own.
 */
function pencil_woocommerce_styles() {
	wp_enqueue_style( 'pencil-woocommerce', get_template_directory_uri() . '/woocommerce.css', array( 'pencil-style' ), wp_get_theme( get_template() )->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'pencil_woocommerce_styles', 20 );

/**
 * Keeps the blog's masonry grid off shop pages and the sticky sidebar for the Shop Sidebar only.
 */
function pencil_woocommerce_scripts() {
	if ( ! pencil_woocommerce_is_shop_area() ) {
		return;
	}

	// Masonry also brings in imagesLoaded as its dependency.
	wp_dequeue_script( 'masonry' );

	if ( get_theme_mod( 'sticky_sidebar', 1 ) && pencil_woocommerce_has_sidebar() ) {
		wp_enqueue_script( 'theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js', array( 'jquery' ), '1.2.2', true );
	} else {
		wp_dequeue_script( 'theia-sticky-sidebar' );
	}
}
add_action( 'wp_enqueue_scripts', 'pencil_woocommerce_scripts', 20 );

/**
 * Shows the cart, checkout and account pages without the blog sidebar.
 *
 * Only replaces the theme's default page template, so page templates and child theme templates still apply.
 *
 * @param string $template Path of the template to include.
 * @return string
 */
function pencil_woocommerce_template_include( $template ) {
	if ( ( is_cart() || is_checkout() || is_account_page() ) && locate_template( 'page.php' ) === $template ) {
		$no_sidebar = locate_template( 'page-nosidebar.php' );

		if ( $no_sidebar ) {
			return $no_sidebar;
		}
	}

	return $template;
}
add_filter( 'template_include', 'pencil_woocommerce_template_include', 20 );

if ( ! function_exists( 'pencil_woocommerce_cart_link' ) ) :

	/**
	 * Displays the header cart link with the number of items in the cart.
	 */
	function pencil_woocommerce_cart_link() {
		if ( ! WC()->cart ) {
			return;
		}

		$count = WC()->cart->get_cart_contents_count();
		?>
		<a class="pencil-cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="M1.5 3h3.2l2.6 11.5h11.2l2.4-8.5H6.2"></path><circle cx="9" cy="19.5" r="1.6"></circle><circle cx="17" cy="19.5" r="1.6"></circle></svg>
		<?php if ( $count > 0 ) : ?>
			<span class="pencil-cart-count" aria-hidden="true"><?php echo esc_html( number_format_i18n( $count ) ); ?></span>
		<?php endif; ?>
		<span class="screen-reader-text">
		<?php
		/* translators: %s: number of items in the cart. */
		echo esc_html( sprintf( _n( 'Cart, %s item', 'Cart, %s items', $count, 'pencil' ), number_format_i18n( $count ) ) );
		?>
		</span></a>
		<?php
	}

endif;
add_action( 'pencil_nav_func_end', 'pencil_woocommerce_cart_link' );

/**
 * Refreshes the header cart link after products are added to the cart with AJAX.
 *
 * @param array $fragments HTML fragments keyed by selector.
 * @return array
 */
function pencil_woocommerce_cart_link_fragment( $fragments ) {
	if ( ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	pencil_woocommerce_cart_link();
	$fragments['a.pencil-cart-link'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'pencil_woocommerce_cart_link_fragment' );

/**
 * Hides the shop page title when turned off in the Customizer.
 *
 * @param bool $show Whether to show the title.
 * @return bool
 */
function pencil_woocommerce_show_page_title( $show ) {
	return $show && get_theme_mod( 'pencil_woocommerce_show_page_title', 1 );
}
add_filter( 'woocommerce_show_page_title', 'pencil_woocommerce_show_page_title' );

/**
 * Adds the WooCommerce (Pencil) Customizer section.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function pencil_woocommerce_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'pencil_woocommerce',
		array(
			'title'       => esc_html__( 'WooCommerce (Pencil)', 'pencil' ),
			'priority'    => 1050,
			'description' => esc_html__( 'Shop layout options from the Pencil theme.', 'pencil' ),
		)
	);

	$wp_customize->add_setting(
		'pencil_woocommerce_sidebar',
		array(
			'default'           => 1,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'pencil_woocommerce_sidebar',
		array(
			'label'       => esc_html__( 'Show the Shop Sidebar', 'pencil' ),
			'description' => esc_html__( 'Shows the Shop Sidebar widget area on the shop page and product category and tag pages, when it has widgets.', 'pencil' ),
			'section'     => 'pencil_woocommerce',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'pencil_woocommerce_show_page_title',
		array(
			'default'           => 1,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'pencil_woocommerce_show_page_title',
		array(
			'label'       => esc_html__( 'Show the shop page title', 'pencil' ),
			'description' => esc_html__( 'Shows the title above the products on the shop page and product category and tag pages.', 'pencil' ),
			'section'     => 'pencil_woocommerce',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'pencil_woocommerce_customize_register' );
