<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Remove Icon.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	$section = 'jupiterx_checkout_cart_remove_icon';

	// Size.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-input',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_size',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-size',
		'column'    => '4',
		'icon'      => 'font-size',
		'units'     => [ 'px' ],
		'input_attrs' => [ 'placeholder' => '16' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a',
				'property' => 'font-size',
			],
		],
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_color',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-color',
		'column'    => '3',
		'icon'      => 'icon-color',
		'default'   => '#fff',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:before',
				'property' => 'color',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_background_color',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-background-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'default'   => '#d1d3d6',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:before',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_border',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-border',
		'default'   => [
			'radius' => [
				'size' => 20,
				'unit' => 'px',
			],
		],
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .product-remove a:before',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings'   => 'jupiterx_checkout_cart_remove_icon_label',
		'section'    => $section,
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_color_hover',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-color-hover',
		'column'    => '3',
		'icon'      => 'icon-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:hover:before',
				'property' => 'color',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_background_color_hover',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-background-color-hover',
		'column'    => '3',
		'icon'      => 'background-color',
		'default'   => '#6c757d',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:hover:before',
				'property' => 'background-color',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_border_color_hover',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-border-color-hover',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:hover:before',
				'property' => 'border-color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_remove_icon_divider',
		'section'  => $section,
	] );

	// Margin.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_margin',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-margin',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .product-remove a',
			],
		],
	] );

	// Padding.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_padding',
		'section'   => $section,
		'css_var'   => 'checkout-cart-remove-icon-padding',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .product-remove a:before',
			],
		],
	] );
} );
