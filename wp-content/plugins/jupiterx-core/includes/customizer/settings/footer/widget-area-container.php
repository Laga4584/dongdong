<?php
/**
 * Add Jupiter settings for Layout > Styles > Footer Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer_widget_area_container';

// Background.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-background',
	'settings'  => 'jupiterx_footer_widget_area_container_background',
	'section'   => $section,
	'transport' => 'postMessage',
	'css_var'   => 'footer-widget-area-container-background',
	'output'    => [
		[
			'element' => '.jupiterx-footer-widgets',
		],
	],
] );

// Column gap.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_footer_widget_area_container_column_gap',
	'section'   => $section,
	'css_var'   => 'footer-widget-area-container-column-gap',
	'label'     => __( 'Column Gap', 'jupiterx-core' ),
	'column'    => '3',
	'units'     => [ 'px', 'em', 'rem' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'       => '.jupiterx-footer-widgets .row',
			'property'      => 'margin',
			'value_pattern' => 'auto calc(-$ / 2)',
		],
		[
			'element'       => '.jupiterx-footer-widgets [class^="col"]',
			'property'      => 'padding',
			'value_pattern' => '0 calc($ / 2)',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widget_area_container_border',
	'section'   => $section,
	'label'     => __( 'Border', 'jupiterx-core' ),
	'css_var'   => 'footer-widget-area-container-border',
	'exclude'   => [ 'style', 'size', 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => 1,
			'unit' => 'px',
		],
		'color' => '#e9ecef',
	],
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets:not(.elementor-widget-sidebar)',
			'property' => 'border-top',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_widget_area_container_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_widget_area_container_spacing',
	'section'   => $section,
	'transport' => 'postMessage',
	'css_var'   => 'footer-widget-area-container',
	'default'   => [
		'desktop' => [
			'padding_top' => 1.5,
		],
	],
	'output'     => [
		[
			'element' => '.jupiterx-footer-widgets',
		],
	],
] );
