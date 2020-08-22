<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Table.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_table_background_color',
		'section'   => 'jupiterx_checkout_cart_table',
		'css_var'   => 'checkout-cart-table-bg-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce table.shop_table',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_table_border',
		'section'   => 'jupiterx_checkout_cart_table',
		'css_var'   => 'checkout-cart-table-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element'  => '.woocommerce table.shop_table',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings'   => 'jupiterx_checkout_cart_table_label_1',
		'section'    => 'jupiterx_checkout_cart_table',
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_table_background_color_hover',
		'section'   => 'jupiterx_checkout_cart_table',
		'css_var'   => 'checkout-cart-table-bg-color-hover',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce table.shop_table tr:hover',
				'property' => 'background-color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_table_divider',
		'section'  => 'jupiterx_checkout_cart_table',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'       => 'jupiterx-box-model',
		'settings'   => 'jupiterx_checkout_cart_table_spacing',
		'section'    => 'jupiterx_checkout_cart_table',
		'css_var'    => 'checkout-cart-table-spacing',
		'transport'  => 'postMessage',
		'exclude'    => [ 'margin' ],
		'output'     => [
			[
				'element' => '.woocommerce table.shop_table, .woocommerce-cart .cart-collaterals .cart_totals table',
			],
		],
	] );

} );
