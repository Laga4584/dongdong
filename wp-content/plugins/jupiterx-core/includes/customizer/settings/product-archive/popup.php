<?php
/**
 * Add Jupiter product archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Product popup.
JupiterX_Customizer::add_section( 'jupiterx_product_archive', [
	'panel'    => 'jupiterx_shop_panel',
	'title'    => __( 'Product Archive', 'jupiterx-core' ),
	'type'     => 'popup',
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/product-archive-in-shop-customizer',
		'title' => __( 'Product Archive in Shop Customizer', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_product_archive_settings', [
	'popup' => 'jupiterx_product_archive',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Style tab.
JupiterX_Customizer::add_section( 'jupiterx_product_archive_styles', [
	'popup' => 'jupiterx_product_archive',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
