<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Elements popup.
JupiterX_Customizer::add_section( 'jupiterx_cart_quick_view', [
	'panel'   => 'jupiterx_shop_panel',
	'title'   => __( 'Cart Quick View', 'jupiterx-core' ),
	'type'    => 'popup',
	'tabs'    => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles' => __( 'Styles', 'jupiterx-core' ),
	],
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/checkout-cart-pages-in-shop-customizer',
		'title' => __( 'Checkout & Cart Pages in Shop Customizer', 'jupiterx-core' ),
	],
] );

// Setting tab.
JupiterX_Customizer::add_section( 'jupiterx_cart_quick_view_styles', [
	'popup' => 'jupiterx_cart_quick_view',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_cart_quick_view_settings', [
	'popup' => 'jupiterx_cart_quick_view',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

	// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
