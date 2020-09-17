<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Field tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment_field';

// Field Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_comment_field_typography',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'comment-field-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
	'output'     => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control',
		],
	],
] );

// Field Background Color Normal.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_field_background_color',
	'css_var'   => 'comment-field-background-color',
	'section'   => $section,
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'column'    => '1',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control',
			'property' => 'background-color',
		],
	],
] );

// Field Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_comment_field_border',
	'section'   => $section,
	'css_var'   => 'comment-field-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control',
		],
	],
] );

// Hover Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Focus', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
	'settings'   => 'jupiterx_comment_field_label_focus',
	'section'    => $section,
] );

// Field Color Focus.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_field_color_focus',
	'section'   => $section,
	'css_var'   => 'comment-field-color-focus',
	'icon'      => 'font-color',
	'column'    => '3',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control:focus',
			'property' => 'color',
		],
	],
] );

// Field Background Color Focus.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_field_background_color_focus',
	'css_var'   => 'comment-field-background-color-focus',
	'section'   => $section,
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'column'    => '3',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control:focus',
			'property' => 'background-color',
		],
	],
] );

// Field Border Color Focus.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_field_border_color_focus',
	'section'   => $section,
	'css_var'   => 'post-single-avatar-border-focus',
	'column'    => '3',
	'icon'      => 'border-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control:focus',
			'property' => 'border-color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_comment_field_divider',
	'section'  => $section,
] );

// Spacing Margin.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_field_spacing_margin',
	'section'   => $section,
	'css_var'   => 'comment-field-margin',
	'exclude'   => [ 'padding' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-comments .comment-form .jupiterx-comment-field-wrapper',
		],
		[
			'element' => '.jupiterx-comments .comment-form .form-group',
		],
		[
			'element' => '.jupiterx-comments .comment-form input[name=wp-comment-cookies-consent]',
		],
	],
] );

// Spacing Padding.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_field_spacing_padding',
	'section'   => $section,
	'css_var'   => 'comment-field-padding',
	'exclude'   => [ 'margin' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control',
		],
	],
] );

