<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Meta tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_post_single_meta';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_post_single_meta_align',
	'section'   => $section,
	'css_var'   => 'post-single-meta-align',
	'label'     => __( 'Align', 'jupiterx-core' ),
	'column'    => '4',
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-meta',
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

// Avatar.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-toggle',
	'settings'  => 'jupiterx_post_single_meta_avatar',
	'section'   => $section,
	'label'     => __( 'Avatar', 'jupiterx-core' ),
	'column'    => '3',
	'default'   => true,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template',
			'operator' => 'contains',
			'value'    => [ '2', '3' ],
		],
	],
] );

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_post_single_meta_typography',
	'section'   => $section,
	'css_var'   => 'post-single-meta',
	'transport' => 'postMessage',
	'exclude'   => [ 'line_height', 'text_transform' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-meta',
		],
	],
] );

// Meta divider.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-text',
	'settings'  => 'jupiterx_post_single_meta_meta_divider',
	'section'   => $section,
	'css_var'   => [
		'name'  => 'post-single-meta-breadcrumb-divider',
		'value' => '"$"',
	],
	'label'     => __( 'Meta Divider', 'jupiterx-core' ),
	'column'    => '5',
	'transport' => 'postMessage',
	'default'   => '|',
	'output'    => [
		[
			'element'       => '.single-post .jupiterx-post-meta .list-inline-item + .list-inline-item:before',
			'property'      => 'content',
			'value_pattern' => '"$"',
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

// Divider color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_meta_meta_divider_color',
	'section'   => $section,
	'css_var'   => 'post-single-meta-divider-color',
	'label'     => __( 'Divider Color', 'jupiterx-core' ),
	'column'    => '3',
	'transport' => 'postMessage',
	'output'     => [
		[
			'element'  => '.single-post .jupiterx-post-meta .list-inline-item + .list-inline-item:before',
			'property' => 'color',
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

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_meta_divider',
	'section'  => $section,
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Links', 'jupiterx-core' ),
	'settings'   => 'jupiterx_post_single_meta_label',
	'section'    => $section,
] );

// Links color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_meta_links_color',
	'section'   => $section,
	'css_var'   => 'post-single-meta-links-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-meta a',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_meta_divider_2',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_meta_spacing',
	'section'   => $section,
	'css_var'   => 'post-single-meta',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 1,
		],
	],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-meta',
		],
	],
] );
