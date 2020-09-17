<?php
/**
 * Add Jupiter settings for Header > Styles tab > Menu to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_menu';

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_header_menu_link_typography',
	'css_var'    => 'header-menu-link',
	'section'    => 'jupiterx_header_menu',
	'exclude'    => [ 'line_height' ],
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav .nav-link',
		],
	],
] );

// Spacing between.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_header_menu_item_spacing_between',
	'css_var'   => 'header-menu-item-spacing-between',
	'section'   => $section,
	'column'    => '4',
	'icon'      => 'space-between',
	'units'     => [ 'px', 'em', 'rem' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'       => '.jupiterx-site-navbar .navbar-nav > .nav-item',
			'property'      => 'margin-left',
			'value_pattern' => 'calc( $ / 2)',
			'media_query'   => '@media (min-width: 768px)',
		],
		[
			'element'       => '.jupiterx-site-navbar .navbar-nav > .nav-item',
			'property'      => 'margin-right',
			'value_pattern' => 'calc( $ / 2)',
			'media_query'   => '@media (min-width: 768px)',
		],
	],
] );

// Background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_background_color',
	'css_var'   => 'header-menu-link-background-color',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .nav-link',
			'property' => 'background-color',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_header_menu_link_border',
	'css_var'   => 'header-menu-link-border',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .nav-link',
		],
	],
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
	'settings'   => 'jupiterx_header_menu_label',
	'section'    => $section,
] );

// Text color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_color_hover',
	'css_var'   => 'header-menu-link-color-hover',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .nav-link:hover',
			'property' => 'color',
		],
	],
] );

// Background color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_background_color_hover',
	'css_var'   => 'header-menu-link-background-color-hover',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .nav-link:hover',
			'property' => 'background-color',
		],
	],
] );

// Active label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Active', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'blue',
	'settings'   => 'jupiterx_header_menu_label_2',
	'section'    => $section,
] );

// Text color active.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_color_active',
	'css_var'   => 'header-menu-link-color-active',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .active .nav-link',
			'property' => 'color',
		],
	],
] );

// Background color active.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_background_color_active',
	'css_var'   => 'header-menu-link-background-color-active',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .active .nav-link',
			'property' => 'background-color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_menu_divider_2',
	'section'  => $section,
] );

// Menu spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_menu_spacing',
	'css_var'   => 'header-menu',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_menu_divider_3',
	'section'  => $section,
] );

// Menu link spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_menu_link_spacing',
	'css_var'   => 'header-menu-link',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav .nav-link',
		],
	],
] );
