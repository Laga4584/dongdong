<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Field.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_checkout_cart_field_typography',
		'section'    => 'jupiterx_checkout_cart_field',
		'responsive' => true,
		'css_var'    => 'checkout-cart-field',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element' => '.woocommerce-cart table.cart td.actions .coupon .input-text',
			],
			[
				'element' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__rendered',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_field_background_color',
		'section'   => 'jupiterx_checkout_cart_field',
		'css_var'   => 'checkout-cart-field-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__rendered',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_field_border',
		'section'   => 'jupiterx_checkout_cart_field',
		'css_var'   => 'checkout-cart-field-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__rendered',
			],
		],
	] );

	// Focus label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Focus', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'blue',
		'settings'   => 'jupiterx_checkout_cart_field_label_focus',
		'section'    => 'jupiterx_checkout_cart_field',
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_field_text_color_focus',
		'section'   => 'jupiterx_checkout_cart_field',
		'css_var'   => 'checkout-cart-field-color-focus',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart table.cart td.actions .coupon .input-text:focus',
				'property' => 'color',
			],
			[
				'element'  => '.woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus',
				'property' => 'color',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_field_background_color_focus',
		'section'   => 'jupiterx_checkout_cart_field',
		'css_var'   => 'checkout-cart-field-background-color-focus',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart table.cart td.actions .coupon .input-text:focus',
				'property' => 'background-color',
			],
			[
				'element'  => '.woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus',
				'property' => 'background-color',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_field_border_color_focus',
		'section'   => 'jupiterx_checkout_cart_field',
		'css_var'   => 'checkout-cart-field-border-color-focus',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart table.cart td.actions .coupon .input-text:focus',
				'property' => 'border-color',
			],
			[
				'element'  => '.woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus',
				'property' => 'border-color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_field_divider',
		'section'  => 'jupiterx_checkout_cart_field',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_field_spacing',
		'section'   => 'jupiterx_checkout_cart_field',
		'css_var'   => 'checkout-cart-field',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart table.cart td.actions .coupon .input-text',
			],
			[
				'element' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__rendered',
			],
		],
	] );
} );
