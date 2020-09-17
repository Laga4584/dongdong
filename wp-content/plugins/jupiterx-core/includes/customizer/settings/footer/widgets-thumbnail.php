<?php
/**
 * Add Jupiter settings for Footer > Styles > Widgets Thumbnail popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer_widgets_thumbnail';

// Size.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_footer_widgets_thumbnail_size',
	'section'   => $section,
	'label'     => esc_html__( 'Size', 'jupiterx-core' ),
	'css_var'   => 'footer-widgets-thumbnail-size',
	'column'    => '4',
	'units'     => [ 'px' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer .jupiterx-widget-posts-image img, .jupiterx-footer .woocommerce ul.product_list_widget li img',
			'property' => 'width',
		],
		[
			'element'  => '.jupiterx-footer .jupiterx-widget-posts-image img, .jupiterx-footer .woocommerce ul.product_list_widget li img',
			'property' => 'height',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widgets_thumbnail_border',
	'section'   => $section,
	'css_var'   => 'footer-widgets-thumbnail-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'   => [
		[
			'element' => '.jupiterx-footer .jupiterx-widget-posts-image img, .jupiterx-footer .woocommerce ul.product_list_widget li img',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_widgets_thumbnail_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_footer_widgets_thumbnail_spacing',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'footer-widgets-thumbnail',
	'exclude'    => [ 'padding' ],
	'disable'    => [ jupiterx_get_direction( 'margin-left' ) ],
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-footer .jupiterx-widget-posts-image img, .jupiterx-footer .woocommerce ul.product_list_widget li img',
		],
	],
] );
