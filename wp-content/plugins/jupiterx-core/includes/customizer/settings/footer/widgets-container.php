<?php
/**
 * Add Jupiter settings for Footer > Styles > Widgets Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer_widgets_container';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_footer_widgets_container_align',
	'section'   => $section,
	'label'     => __( 'Align', 'jupiterx-core' ),
	'default'   => jupiterx_get_direction( 'left' ),
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'footer-widgets-container-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets .jupiterx-widget',
			'property' => 'text-align',
		],
	],
] );

// Background.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-background',
	'settings'  => 'jupiterx_footer_widgets_container_background',
	'section'   => $section,
	'css_var'   => 'footer-widgets-container-background',
	'transport' => 'postMessage',
	'exclude'   => [ 'image', 'position', 'repeat', 'attachment', 'size' ],
	'output'    => [
		[
			'element' => '.jupiterx-footer-widgets .jupiterx-widget',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widgets_container_border',
	'section'   => $section,
	'label'     => __( 'Border', 'jupiterx-core' ),
	'css_var'   => 'footer-widgets-container-border',
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
			'element' => '.jupiterx-footer-widgets .jupiterx-widget',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_widgets_container_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_footer_widgets_container_spacing',
	'section'    => $section,
	'responsive' => true,
	'transport'  => 'postMessage',
	'css_var'    => 'footer-widgets-container',
	'output'     => [
		[
			'element' => '.jupiterx-footer-widgets .jupiterx-widget',
		],
	],
] );
