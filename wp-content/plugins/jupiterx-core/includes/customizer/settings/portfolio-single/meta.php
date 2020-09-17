<?php
/**
 * Add Jupiter settings for Portfolio > Styles > Meta tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_portfolio_single_meta';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_portfolio_single_meta_align',
	'section'   => $section,
	'css_var'   => 'portfolio-single-meta-align',
	'label'     => __( 'Align', 'jupiterx-core' ),
	'column'    => '4',
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-meta',
			'property' => 'text-align',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_portfolio_single_meta_typography',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'portfolio-single-meta',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-portfolio .jupiterx-post-meta',
		],
	],
] );

// Meta divider.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-text',
	'settings'  => 'jupiterx_portfolio_single_meta_meta_divider',
	'section'   => $section,
	'css_var'   => [
		'name'  => 'portfolio-single-meta-breadcrumb-divider',
		'value' => '"$"',
	],
	'label'     => __( 'Meta Divider', 'jupiterx-core' ),
	'column'    => '5',
	'transport' => 'postMessage',
	'default'   => '|',
	'output'    => [
		[
			'element'       => '.single-portfolio .jupiterx-post-meta .list-inline-item + .list-inline-item:before',
			'property'      => 'content',
			'value_pattern' => '"$"',
		],
	],
] );

// Divider color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_portfolio_single_meta_meta_divider_color',
	'section'   => $section,
	'css_var'   => 'portfolio-single-meta-divider-color',
	'label'     => __( 'Divider Color', 'jupiterx-core' ),
	'column'    => '3',
	'transport' => 'postMessage',
	'output'     => [
		[
			'element'  => '.single-portfolio .jupiterx-post-meta .list-inline-item + .list-inline-item:before',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_meta_divider',
	'section'  => $section,
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Links', 'jupiterx-core' ),
	'settings'   => 'jupiterx_portfolio_single_meta_label',
	'section'    => $section,
] );

// Links color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_portfolio_single_meta_links_color',
	'section'   => $section,
	'css_var'   => 'portfolio-single-meta-links-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-meta a',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_meta_divider_2',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_portfolio_single_meta_spacing',
	'section'   => $section,
	'css_var'   => 'portfolio-single-meta',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 1,
		],
	],
	'output'    => [
		[
			'element' => '.single-portfolio .jupiterx-post-meta',
		],
	],
] );
