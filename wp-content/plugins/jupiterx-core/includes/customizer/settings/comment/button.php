<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Button tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment_button';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_comment_button_align',
	'section'   => $section,
	'label'     => __( 'Align', 'jupiterx-core' ),
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'comment-button-align',
	'transport' => 'postMessage',
	'column'   => 6,
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit',
			'property' => 'text-align',
		],
	],
] );

JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_comment_button_full_width',
	'section'  => $section,
	'label'    => __( 'Full Width', 'jupiterx-core' ),
	'column'   => '6',
	'default'  => false,
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn',
			'property' => 'width',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_comment_button_typography',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'comment-button-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height' ],
	'output'     => [
		[
			'element' => '.jupiterx-comments .form-submit button.btn',
		],
	],
] );

// Button Background Color Normal.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_button_background_color',
	'css_var'   => 'comment-button-background-color',
	'section'   => $section,
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'column'    => '1',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn',
			'property' => 'background-color',
		],
	],
] );

// Button Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_comment_button_border',
	'section'   => $section,
	'css_var'   => 'comment-button-border',
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
			'element' => '.jupiterx-comments .form-submit button.btn',
		],
	],
] );

// Hover Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
	'settings'   => 'jupiterx_comment_button_label_hover',
	'section'    => $section,
] );

// Button Color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_button_color_hover',
	'section'   => $section,
	'css_var'   => 'comment-button-color-hover',
	'icon'      => 'font-color',
	'column'    => '3',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn:hover',
			'property' => 'color',
		],
	],
] );

// Button Background Color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_button_background_color_hover',
	'css_var'   => 'comment-button-background-color-hover',
	'section'   => $section,
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'column'    => '3',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn:hover',
			'property' => 'background-color',
		],
	],
] );

JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_button_border_color_hover',
	'section'   => $section,
	'css_var'   => 'comment-button-border-hover',
	'column'    => '3',
	'icon'      => 'border-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn:hover',
			'property' => 'border-color',
		],
	],
] );


// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_comment_button_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_button_spacing',
	'section'   => $section,
	'css_var'   => 'comment-button-box-model',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-comments .form-submit button.btn',
		],
	],
] );

