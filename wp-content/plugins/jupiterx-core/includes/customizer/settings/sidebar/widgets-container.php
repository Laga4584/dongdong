<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Widgets Container tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar_widgets_container';

// Typography.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-background',
	'settings'  => 'jupiterx_sidebar_widgets_container_background',
	'section'   => $section,
	'css_var'   => 'sidebar-widgets-container-background',
	'transport' => 'postMessage',
	'exclude'   => [ 'image' ],
	'output'    => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget',
		],
	],
] );

// Align.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_sidebar_widgets_container_align',
	'section'   => 'jupiterx_sidebar_widgets_container',
	'css_var'   => 'sidebar-widgets-container-align',
	'label'     => __( 'Align', 'jupiterx-core' ),
	'default'   => jupiterx_get_direction( 'left' ),
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget',
			'property' => 'text-align',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_widgets_container_border',
	'section'   => 'jupiterx_sidebar_widgets_container',
	'css_var'   => 'sidebar-widgets-container-border',
	'label'     => __( 'Border', 'jupiterx-core' ),
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
			'element'  => '.jupiterx-sidebar .jupiterx-widget',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_widgets_container_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_sidebar_widgets_container_spacing',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'sidebar-widgets-container',
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget',
		],
	],
] );
