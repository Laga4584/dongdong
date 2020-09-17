<?php
/**
 * Add Jupiter settings for Portfolio Single > Styles > Featured Image tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_portfolio_single_featured_image';

// Full width.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_portfolio_single_featured_image_full_width',
	'section'  => $section,
	'css_var'  => 'portfolio-single-featured-image-full-width',
	'label'    => __( 'Full Width', 'jupiterx-core' ),
	'column'   => '3',
] );

// Min height.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_portfolio_single_featured_image_min_height',
	'section'     => $section,
	'css_var'     => 'portfolio-single-featured-image-min-height',
	'label'       => __( 'Min Height', 'jupiterx-core' ),
	'column'      => '4',
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [ 'unit' => '-' ],
	'units'       => [ '-', 'px', 'vh' ],
	'output'      => [
		[
			'element'       => '.single-portfolio .jupiterx-post-image img',
			'property'      => 'min-height',
		],
	],
] );

// Max height.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_portfolio_single_featured_image_max_height',
	'section'     => $section,
	'css_var'     => 'portfolio-single-featured-image-max-height',
	'label'       => __( 'Max Height', 'jupiterx-core' ),
	'column'      => '4',
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [
		'unit' => '-',
	],
	'units'       => [ '-', 'px', 'vh' ],
	'output'     => [
		[
			'element'       => '.single-portfolio .jupiterx-post-image img',
			'property'      => 'max-height',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_featured_image_divider_1',
	'section'  => $section,
	'column'   => '12 jupiterx-divider-control-empty',
] );

// Border width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_portfolio_single_featured_image_border_width',
	'section'     => $section,
	'css_var'     => 'portfolio-single-featured-image-border-width',
	'column'      => '4',
	'icon'        => 'border',
	'units'       => [ 'px' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.single-portfolio .jupiterx-post-image img',
			'property' => 'border-width',
		],
	],
] );

// Border radius.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_portfolio_single_featured_image_border_radius',
	'section'     => $section,
	'css_var'     => 'portfolio-single-featured-image-border-radius',
	'column'      => '4',
	'icon'        => 'corner-radius',
	'units'       => [ 'px', '%' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.single-portfolio .jupiterx-post-image:not(.jupiterx-post-image-full-width) img',
			'property' => 'border-radius',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_portfolio_single_featured_image_full_width',
			'operator' => '!=',
			'value'    => true,
		],
	],
] );

// Border color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_portfolio_single_featured_image_border_color',
	'section'   => $section,
	'css_var'   => 'portfolio-single-featured-image-border-color',
	'column'    => '3',
	'icon'      => 'border-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-image img',
			'property' => 'border-color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_featured_image_divider_2',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_portfolio_single_featured_image_spacing',
	'section'   => $section,
	'css_var'   => 'portfolio-single-featured-image',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 2,
		],
	],
	'output'    => [
		[
			'element' => '.single-portfolio .jupiterx-post-image',
		],
	],
] );
