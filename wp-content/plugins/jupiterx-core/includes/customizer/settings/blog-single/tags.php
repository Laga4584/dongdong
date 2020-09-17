<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Tags tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_post_single_tags';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_post_single_tags_align',
	'section'   => $section,
	'css_var'   => 'post-single-tags-align',
	'label'     => __( 'Align', 'jupiterx-core' ),
	'column'    => '4',
	'transport' => 'postMessage',
	'default'   => [
		'desktop' => '',
		'tablet'  => 'center',
		'mobile'  => 'center',
	],
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags',
			'property' => 'text-align',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => 'contains',
			'value'    => [ '1', '2' ],
		],
	],
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_post_single_tags_links_typography',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'post-single-tags-links',
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.single-post .jupiterx-post-tags .btn',
		],
	],
] );

// Background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_tags_links_background_color',
	'section'   => $section,
	'css_var'   => 'post-single-tags-links-background-color',
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags .btn',
			'property' => 'background-color',
		],
	],
] );

// Column gap.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_post_single_tags_links_gap',
	'section'   => $section,
	'css_var'   => 'post-single-tags-links-gap',
	'column'    => '4',
	'icon'      => 'space-between',
	'units'     => [ 'px', 'em', 'rem' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'       => '.single-post .jupiterx-post-tags .jupiterx-post-tags-row',
			'property'      => 'margin-left',
			'value_pattern' => 'calc(-$ / 2)',
		],
		[
			'element'       => '.single-post .jupiterx-post-tags .jupiterx-post-tags-row',
			'property'      => 'margin-right',
			'value_pattern' => 'calc(-$ / 2)',
		],
		[
			'element'       => '.single-post .jupiterx-post-tags .btn',
			'property'      => 'margin-left',
			'value_pattern' => 'calc($ / 2)',
		],
		[
			'element'       => '.single-post .jupiterx-post-tags .btn',
			'property'      => 'margin-right',
			'value_pattern' => 'calc($ / 2)',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_post_single_tags_links_border',
	'section'   => $section,
	'css_var'   => 'post-single-tags-links-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-tags .btn',
		],
	],
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
	'settings' => 'jupiterx_post_single_tags_label',
	'section'  => $section,
] );

// Text color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_tags_links_color_hover',
	'section'   => $section,
	'css_var'   => 'post-single-tags-links-color-hover',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags .btn:hover',
			'property' => 'color',
		],
	],
] );

// Background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_tags_links_background_color_hover',
	'section'   => $section,
	'css_var'   => 'post-single-tags-links-background-color-hover',
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags .btn:hover',
			'property' => 'background-color',
		],
	],
] );

// Border color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_tags_links_border_color_hover',
	'section'   => $section,
	'css_var'   => 'post-single-tags-links-border-color-hover',
	'column'    => '3',
	'icon'      => 'border-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags .btn:hover',
			'property' => 'border-color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_tags_divider_2',
	'section'  => $section,
] );

// Padding.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_tags_links_spacing',
	'section'   => $section,
	'css_var'   => 'post-single-tags-links',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-tags .btn',
		],
	],
] );

// Margin.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_tags_spacing',
	'section'   => $section,
	'css_var'   => 'post-single-tags',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-tags',
		],
	],
] );
