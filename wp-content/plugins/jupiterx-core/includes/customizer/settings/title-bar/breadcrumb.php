<?php
/**
 * Add Jupiter settings for Title Bar > Styles > Breadcrumb popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_title_bar_breadcrumb';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_title_bar_breadcrumb_align',
	'section'   => $section,
	'label'     => __( 'Align', 'jupiterx-core' ),
	'choices'   => JupiterX_Customizer_Utils::get_align( 'justify-content' ),
	'css_var'   => 'title-bar-breadcrumb-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-main-header .breadcrumb',
			'property' => 'justify-content',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_title_bar_breadcrumb_typography',
	'section'   => $section,
	'exclude'   => [ 'line_height' ],
	'css_var'   => 'title-bar-breadcrumb',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-main-header .breadcrumb, .jupiterx-main-header .breadcrumb-item.active',
		],
	],
] );

// Breadcrumb divider.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-text',
	'settings'  => 'jupiterx_title_bar_breadcrumb_divider',
	'section'   => $section,
	'css_var'   => [
		'name'  => 'title-bar-breadcrumb-divider',
		'value' => '"$"',
	],
	'label'     => __( 'Breadcrumb divider', 'jupiterx-core' ),
	'column'    => '5',
	'transport' => 'postMessage',
	'default'   => '/',
	'output'    => [
		[
			'element'       => '.jupiterx-main-header .breadcrumb .breadcrumb-item + .breadcrumb-item:before',
			'property'      => 'content',
			'value_pattern' => '"$"',
		],
	],
] );

// Divider color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_title_bar_breadcrumb_divider_color',
	'section'   => $section,
	'css_var'   => 'title-bar-breadcrumb-divider-color',
	'label'     => __( 'Divider color', 'jupiterx-core' ),
	'column'    => '3',
	'transport' => 'postMessage',
	'output'     => [
		[
			'element'  => '.jupiterx-main-header .breadcrumb .breadcrumb-item + .breadcrumb-item:before',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_title_bar_breadcrumb_divider_1',
	'section'  => $section,
] );

// Links color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_title_bar_breadcrumb_links_color',
	'section'   => $section,
	'css_var'   => 'title-bar-breadcrumb-links-color',
	'label'     => __( 'Links color', 'jupiterx-core' ),
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-main-header .breadcrumb a span',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_title_bar_breadcrumb_divider_2',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_title_bar_breadcrumb_spacing',
	'section'   => $section,
	'css_var'   => 'title-bar-breadcrumb',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 0,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-main-header .breadcrumb',
		],
	],
] );
