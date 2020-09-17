<?php
/**
 * Add Jupiter settings for Header > Styles tab > Container to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_container';

// Background color.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-color',
	'settings'   => 'jupiterx_header_container_background_color',
	'css_var'    => 'header-container-background',
	'section'    => $section,
	'column'     => '3',
	'icon'       => 'background-color',
	'responsive' => true,
	'transport'  => 'postMessage',
	'default'    => [
		'desktop' => '#fff',
	],
	'output'     => [
		[
			'element'  => '.jupiterx-site-navbar',
			'property' => 'background-color',
		],
	],
] );

// Border.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-border',
	'settings'   => 'jupiterx_header_container_border',
	'css_var'    => 'header-container-border',
	'section'    => $section,
	'exclude'    => [ 'style', 'size', 'radius' ],
	'transport'  => 'postMessage',
	'default'    => [
		'desktop' => [
			'width' => [
				'size' => 1,
				'unit' => 'px',
			],
			'color' => '#e9ecef',
		],
	],
	'output'     => [
		[
			'element'  => '.jupiterx-site-navbar',
			'property' => 'border-bottom',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_container_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_container_spacing',
	'css_var'   => 'header-container',
	'section'   => $section,
	'exclude'   => [ 'margin' ],
	'transport' => 'postMessage',
	'default'    => [
		'desktop' => [
			'padding_top'    => 1.75,
			'padding_bottom' => 1.75,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar',
		],
	],
] );
