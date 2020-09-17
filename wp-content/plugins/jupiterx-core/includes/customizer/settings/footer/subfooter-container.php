<?php
/**
 * Add Jupiter settings for Footer > Sub Footer > Styles > Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer_sub_container';

// Background.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-background',
	'settings'  => 'jupiterx_footer_sub_container_background',
	'section'   => $section,
	'css_var'   => 'subfooter-container-background',
	'transport' => 'postMessage',
	'default'   => [
		'color' => '#343a40',
	],
	'output'    => [
		[
			'element' => '.jupiterx-subfooter',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_sub_container_border',
	'section'   => $section,
	'css_var'   => 'subfooter-container-border',
	'exclude'   => [ 'style', 'size', 'radius' ],
	'label'     => __( 'Border', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => 1,
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-subfooter',
			'property' => 'border-top',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_sub_container_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_sub_container_spacing',
	'section'   => $section,
	'css_var'   => 'subfooter-container',
	'transport' => 'postMessage',
	'default'   => [
		'desktop' => [
			'padding_top' => 1.5,
			'padding_bottom' => 1.5,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-subfooter',
		],
	],
] );
