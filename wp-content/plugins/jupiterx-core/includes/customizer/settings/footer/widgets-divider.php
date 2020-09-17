<?php
/**
 * Add Jupiter settings for Footer > Styles > Divider popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer_widgets_divider';

// Widget divider.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widgets_divider',
	'section'   => $section,
	'css_var'   => 'footer-widgets-divider',
	'exclude'   => [ 'radius' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets .jupiterx-widget-divider',
			'property' => 'border-top',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_divider_line',
	'section'  => $section,
] );

// Items.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widgets_items_divider',
	'section'   => $section,
	'css_var'   => 'footer-widgets-divider-items',
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
			'element'  => '.jupiterx-footer-widgets .jupiterx-widget ul li, .jupiterx-footer-widgets .jupiterx-widget .jupiterx-widget-posts-item',
			'property' => 'border-bottom',
		],
	],
] );

// Items spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_widgets_items_spacing',
	'section'   => $section,
	'css_var'   => 'footer-widgets-divider-items',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-footer-widgets .jupiterx-widget ul li, .jupiterx-footer-widgets .jupiterx-widget .jupiterx-widget-posts-item',
		],
	],
] );
