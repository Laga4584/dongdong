<?php
/**
 * Add Jupiter Product page popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Product page popup.
JupiterX_Customizer::add_section( 'jupiterx_product_page', [
	'panel'   => 'jupiterx_shop_panel',
	'title'   => __( 'Product Page', 'jupiterx-core' ),
	'type'    => 'popup',
	'tabs'    => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => [
			'label' => __( 'Styles', 'jupiterx-core' ),
			'pro'   => true,
		],
	],
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/product-page-in-shop-customizer',
		'title' => __( 'Product Page in Shop Customizer', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_product_page_settings', [
	'popup' => 'jupiterx_product_page',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_product_page_styles', [
	'popup' => 'jupiterx_product_page',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-pro-box',
	'settings' => 'jupiterx_product_page_styles_pro_box',
	'section'  => 'jupiterx_product_page_styles',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
