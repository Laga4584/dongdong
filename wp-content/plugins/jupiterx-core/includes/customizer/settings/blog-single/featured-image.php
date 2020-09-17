<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Featured Image tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_post_single_featured_image';

// Full width.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-toggle',
	'settings'  => 'jupiterx_post_single_featured_image_full_width',
	'section'   => $section,
	'label'     => __( 'Full Width', 'jupiterx-core' ),
	'column'    => '3',
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => '!=',
			'value'    => '2',
		],
	],
] );

// Min height.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_min_height',
	'section'     => $section,
	'css_var'     => 'post-single-featured-image-min-height',
	'label'       => __( 'Min Height', 'jupiterx-core' ),
	'column'      => '4',
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [ 'unit' => '-' ],
	'units'       => [ '-', 'px', 'vh' ],
	'output'      => [
		[
			'element'       => '.single-post:not(.jupiterx-post-template-2) .jupiterx-post-image img',
			'property'      => 'min-height',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => '!=',
			'value'    => '2',
		],
	],
] );

// Max height.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_max_height',
	'section'     => $section,
	'css_var'     => 'post-single-featured-image-max-height',
	'label'       => __( 'Max Height', 'jupiterx-core' ),
	'column'      => '4',
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [ 'unit' => '-' ],
	'units'       => [ '-', 'px', 'vh' ],
	'output'     => [
		[
			'element'       => '.single-post:not(.jupiterx-post-template-2) .jupiterx-post-image img',
			'property'      => 'max-height',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => '!=',
			'value'    => '2',
		],
	],
] );

// Min height (template 2).
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_template_2_min_height',
	'section'     => $section,
	'css_var'     => 'post-single-featured-image-template-2-min-height',
	'label'       => __( 'Min Height', 'jupiterx-core' ),
	'column'      => '4',
	'input_attrs' => [ 'placeholder' => '60' ],
	'transport'   => 'postMessage',
	'default'     => [
		'size' => 60,
		'unit' => 'vh',
	],
	'units'       => [ '-', 'px', 'vh' ],
	'output'      => [
		[
			'element'       => '.single-post.jupiterx-post-template-2 .jupiterx-post-header',
			'property'      => 'min-height',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => '==',
			'value'    => '2',
		],
	],
] );

// Max height (template 2).
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_template_2_max_height',
	'section'     => $section,
	'css_var'     => 'post-single-featured-image-template-2-max-height',
	'label'       => __( 'Max Height', 'jupiterx-core' ),
	'column'      => '4',
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [ 'unit' => '-' ],
	'units'       => [ '-', 'px', 'vh' ],
	'output'     => [
		[
			'element'       => '.single-post.jupiterx-post-template-2 .jupiterx-post-header',
			'property'      => 'max-height',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => '==',
			'value'    => '2',
		],
	],
] );

// Overlay color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_featured_image_background_color',
	'section'   => $section,
	'css_var'   => 'post-single-featured-image-overlay-color',
	'label'     => __( 'Overlay Color', 'jupiterx-core' ),
	'column'    => '3',
	'transport' => 'postMessage',
	'default'   => 'rgba(108, 117, 125, 0.5)',
	'output'    => [
		[
			'element'  => '.jupiterx-post-template-2 .jupiterx-post-image-overlay',
			'property' => 'background-color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => 'contains',
			'value'    => [ '2' ],
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_featured_image_divider',
	'section'  => $section,
	'column'   => '12 jupiterx-divider-control-empty',
] );

// Border width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_border_width',
	'section'     => $section,
	'css_var'     => 'post-single-featured-image-border-width',
	'column'      => '4',
	'icon'        => 'border',
	'units'       => [ 'px' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-post-template-1  .jupiterx-post-image img, .jupiterx-post-template-3 .jupiterx-post-image img',
			'property' => 'border-width',
		],
		[
			'element'  => '.jupiterx-post-template-2 .jupiterx-post-header',
			'property' => 'border-width',
		],
	],
] );

// Border radius.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_border_radius',
	'section'     => $section,
	'css_var'     => 'post-single-featured-image-border-radius',
	'column'      => '4',
	'icon'        => 'corner-radius',
	'units'       => [ 'px', '%' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.single-post .jupiterx-main-content:not(.jupiterx-post-image-full-width) .jupiterx-post-image img',
			'property' => 'border-radius',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => '!=',
			'value'    => '2',
		],
		[
			'setting'  => 'jupiterx_post_single_featured_image_full_width',
			'operator' => '!=',
			'value'    => true,
		],
	],
] );

// Border color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_featured_image_border_color',
	'section'   => $section,
	'css_var'   => 'post-single-featured-image-border-color',
	'column'    => '3',
	'icon'       => 'border-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-post-template-1  .jupiterx-post-image img, .jupiterx-post-template-3 .jupiterx-post-image img',
			'property' => 'border-color',
		],
		[
			'element'  => '.jupiterx-post-template-2 .jupiterx-post-header',
			'property' => 'border-color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_featured_image_divider_2',
	'section'  => $section,
] );

// All Template except 2 spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_featured_image_spacing',
	'section'   => $section,
	'css_var'   => 'post-single-featured-image',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 2,
		],
	],
	'output'    => [
		[
			'element' => '.single-post:not(.jupiterx-post-template-2) .jupiterx-post-image',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => 'contains',
			'value'    => [ '1', '3' ],
		],
	],
] );

// Template 2 spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_featured_image_template_2_spacing',
	'section'   => $section,
	'css_var'   => 'post-single-template-2-featured-image',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 2,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-post-template-2 .jupiterx-post-header',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => 'contains',
			'value'    => [ '2' ],
		],
	],
] );
