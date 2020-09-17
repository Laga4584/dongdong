<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Widgets Container tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar_divider';

// Sidebar.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_divider_sidebar',
	'section'   => $section,
	'css_var'   => 'sidebar-divider-sidebar',
	'label'     => __( 'Sidebar', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'exclude'   => [ 'size', 'radius' ],
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'     => '.jupiterx-sidebar:not(.order-lg-first):not(.elementor-widget), .jupiterx-sidebar.order-lg-last',
			'property'    => 'border-left',
			'media_query' => '@media (min-width: 992px)',
		],
		[
			'element'     => '.jupiterx-sidebar.order-lg-first, .jupiterx-primary.order-lg-last ~ .jupiterx-sidebar',
			'property'    => 'border-right',
			'media_query' => '@media (min-width: 992px)',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_divider_line',
	'section'  => $section,
] );

// Widgets.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_divider_widgets',
	'section'   => $section,
	'css_var'   => 'sidebar-divider-widgets',
	'label'     => __( 'Widgets', 'jupiterx-core' ),
	'exclude'   => [ 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget-divider',
			'property' => 'border-top',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_divider_line_2',
	'section'  => $section,
] );

// Items.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_divider_items',
	'section'   => $section,
	'css_var'   => 'sidebar-divider-items',
	'label'     => esc_html__( 'Items', 'jupiterx-core' ),
	'exclude'   => [ 'size', 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget ul li, .jupiterx-sidebar .jupiterx-widget .jupiterx-widget-posts-item',
			'property' => 'border-bottom',
		],
	],
] );

// Items spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_sidebar_divider_items_spacing',
	'section'   => $section,
	'css_var'   => 'sidebar-divider-items',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget ul li, .jupiterx-sidebar .jupiterx-widget .jupiterx-widget-posts-item',
		],
	],
] );
