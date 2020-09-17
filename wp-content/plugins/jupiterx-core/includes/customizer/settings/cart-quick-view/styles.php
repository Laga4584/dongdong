<?php
/**
 * Add Jupiter settings for Product page > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section_styles = 'jupiterx_cart_quick_view_styles';

// Icon size.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_shopping_cart_icon_size',
	'css_var'     => 'header-shopping-cart-icon-size',
	'section'     => $section_styles,
	'column'      => '4',
	'icon'        => 'font-size',
	'units'       => [ 'px', 'em', 'rem' ],
	'transport'   => 'postMessage',
	'default'     => [
		'size' => 1.5,
		'unit' => 'rem',
	],
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart-icon',
			'property' => 'font-size',
		],
	],
] );

// Icon color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_icon_color',
	'css_var'   => 'header-shopping-cart-icon-color',
	'section'   => $section_styles,
	'column'    => '3',
	'icon'      => 'icon-color',
	'transport' => 'postMessage',
	'default'   => '#6c757d',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart-icon',
			'property' => 'color',
		],
	],
] );

// Text color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_text_color',
	'css_var'   => 'header-shopping-cart-text-color',
	'section'   => $section_styles,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'default'   => '#6c757d',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart',
			'property' => 'color',
		],
	],
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
	'settings'   => 'jupiterx_header_shopping_cart_label',
	'section'    => $section_styles,
] );

// Icon color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_icon_color_hover',
	'css_var'   => 'header-shopping-cart-icon-color-hover',
	'section'   => $section_styles,
	'column'    => '3',
	'icon'      => 'icon-color',
	'transport' => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart:hover .jupiterx-navbar-cart-icon',
			'property' => 'color',
		],
	],
] );

// Text color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_text_color_hover',
	'css_var'   => 'header-shopping-cart-text-color-hover',
	'section'   => $section_styles,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart:hover',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_shopping_cart_divider_2',
	'section'  => $section_styles,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_shopping_cart_spacing',
	'css_var'   => 'header-shopping-cart',
	'section'   => $section_styles,
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-navbar-cart',
		],
	],
] );
