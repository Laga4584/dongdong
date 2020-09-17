<?php
/**
 * Add Jupiter settings for Header > Styles tab > Subsubmenu to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_submenu';

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Items', 'jupiterx-core' ),
	'settings'   => 'jupiterx_header_submenu_label',
	'section'    => $section,
] );

// Items typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_header_submenu_items_typography',
	'css_var'    => 'header-submenu-items',
	'section'    => 'jupiterx_header_submenu',
	'exclude'    => [ 'line_height' ],
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav .dropdown-item',
		],
	],
] );

// Items background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_background_color',
	'css_var'   => 'header-submenu-items-background-color',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-item',
			'property' => 'background-color',
		],
	],
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
	'settings'   => 'jupiterx_header_submenu_label_2',
	'section'    => $section,
] );

// Items text color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_color_hover',
	'css_var'   => 'header-submenu-items-color-hover',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-item:hover',
			'property' => 'color',
		],
	],
] );

// Items background color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_background_color_hover',
	'css_var'   => 'header-submenu-items-background-color-hover',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-item:hover',
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
	'settings'   => 'jupiterx_header_submenu_label_3',
	'section'    => $section,
] );

// Items text color active.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_color_active',
	'css_var'   => 'header-submenu-items-color-active',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-menu .active .dropdown-item',
			'property' => 'color',
		],
	],
] );

// Items background color active.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_background_color_active',
	'css_var'   => 'header-submenu-items-background-color-active',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-menu .active .dropdown-item',
			'property' => 'background-color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_submenu_divider_2',
	'section'  => $section,
] );

// Items spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_submenu_items_spacing',
	'css_var'   => 'header-submenu-items',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav .dropdown-item',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_submenu_divider_3',
	'section'  => $section,
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Container', 'jupiterx-core' ),
	'settings'   => 'jupiterx_header_submenu_label_4',
	'section'    => $section,
] );

// Container Background Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_container_background_color',
	'css_var'   => 'header-submenu-container-background-color',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-menu',
			'property' => 'background-color',
		],
	],
] );

// Container Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_header_submenu_container_border',
	'css_var'   => 'header-submenu-container-border',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-menu',
		],
	],
] );
