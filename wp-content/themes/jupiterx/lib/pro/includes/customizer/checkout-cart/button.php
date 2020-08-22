<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Button.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_checkout_cart_button_typography',
		'section'    => 'jupiterx_checkout_cart_button',
		'responsive' => true,
		'css_var'    => 'checkout-cart-button',
		'exclude'    => [ 'line_height' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button, .woocommerce-cart .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button, .woocommerce-cart .woocommerce button.button:disabled, .woocommerce-cart .woocommerce button.button:disabled[disabled]',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_button_background_color',
		'section'   => 'jupiterx_checkout_cart_button',
		'css_var'   => 'checkout-cart-button-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .woocommerce button.button, .woocommerce-cart .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button, .woocommerce-cart .woocommerce button.button:disabled, .woocommerce-cart .woocommerce button.button:disabled[disabled]',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_button_border',
		'section'   => 'jupiterx_checkout_cart_button',
		'css_var'   => 'checkout-cart-button-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button, .woocommerce-cart .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button, .woocommerce-cart .woocommerce button.button:disabled, .woocommerce-cart .woocommerce button.button:disabled[disabled]',
			],
		],
	] );

	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_checkout_cart_button_shadow',
		'section'   => 'jupiterx_checkout_cart_button',
		'css_var'   => 'checkout-cart-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button, .woocommerce-cart .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button, .woocommerce-cart .woocommerce button.button:disabled, .woocommerce-cart .woocommerce button.button:disabled[disabled]',
				'units'   => 'px',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings'   => 'jupiterx_checkout_cart_button_label_1',
		'section'    => 'jupiterx_checkout_cart_button',
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_button_text_color_hover',
		'section'   => 'jupiterx_checkout_cart_button',
		'css_var'   => 'checkout-cart-button-text-color-hover',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .woocommerce button.button:hover, .woocommerce-cart .woocommerce a.button.alt:hover, .woocommerce-checkout .woocommerce button.button:hover, .woocommerce-cart .woocommerce button.button:disabled:hover, .woocommerce-cart .woocommerce button.button:disabled[disabled]:hover',
				'property' => 'color',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_button_background_color_hover',
		'section'   => 'jupiterx_checkout_cart_button',
		'css_var'   => 'checkout-cart-button-background-color-hover',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .woocommerce button.button:hover, .woocommerce-cart .woocommerce a.button.alt:hover, .woocommerce-checkout .woocommerce button.button:hover, .woocommerce-cart .woocommerce button.button:disabled:hover, .woocommerce-cart .woocommerce button.button:disabled[disabled]:hover',
				'property' => 'background-color',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_button_border_color_hover',
		'section'   => 'jupiterx_checkout_cart_button',
		'css_var'   => 'checkout-cart-button-border-color-hover',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .woocommerce button.button:hover, .woocommerce-cart .woocommerce a.button.alt:hover, .woocommerce-checkout .woocommerce button.button:hover, .woocommerce-cart .woocommerce button.button:disabled:hover, .woocommerce-cart .woocommerce button.button:disabled[disabled]:hover',
				'property' => 'border-color',
			],
		],
	] );

	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_checkout_cart_button_shadow_hover',
		'section'   => 'jupiterx_checkout_cart_button',
		'css_var'   => 'checkout-cart-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button:hover, .woocommerce-cart .woocommerce a.button.alt:hover, .woocommerce-checkout .woocommerce button.button:hover, .woocommerce-cart .woocommerce button.button:disabled:hover, .woocommerce-cart .woocommerce button.button:disabled[disabled]:hover',
				'units'   => 'px',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_button_divider_3',
		'section'  => 'jupiterx_checkout_cart_button',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_button_spacing',
		'section'   => 'jupiterx_checkout_cart_button',
		'css_var'   => 'checkout-cart-button',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button, .woocommerce-cart .woocommerce button.button:disabled, .woocommerce-cart .woocommerce button.button:disabled[disabled], .woocommerce-cart .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button',
			],
		],
	] );
} );
