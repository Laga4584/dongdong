<?php
/**
 * Add Jupiter settings for Site Settings > Styles > Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_site_container';

// Container box shadow label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_site_container_box_shadow_label',
	'section'  => $section,
	'label'    => __( 'Box Shadow', 'jupiterx-core' ),
] );

// Container box shadow.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-box-shadow',
	'settings'  => 'jupiterx_site_container_box_shadow',
	'section'   => $section,
	'css_var'   => 'site-container-box-shadow',
	'unit'      => 'px',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-site-container',
			'units'   => 'px',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_site_container_divider',
	'section'  => $section,
] );

// Container border label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_site_container_border_label',
	'section'  => $section,
	'label'    => __( 'Border', 'jupiterx-core' ),
] );

// Container border.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_site_container_border',
	'section'   => $section,
	'css_var'   => 'site-container-border',
	'exclude'   => [ 'style', 'size', 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'desktop' => [
			'width' => [
				'size' => 1,
				'unit' => 'px',
			],
			'color' => '#e9ecef',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-site-container',
		],
	],
] );
