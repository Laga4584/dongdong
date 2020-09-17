<?php
/**
 * Add Jupiter settings for Portfolio Single > Styles > Post Content tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

$section = 'jupiterx_portfolio_single_post_content';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => "{$section}_align",
	'section'   => $section,
	'css_var'   => 'portfolio-single-post-content-align',
	'label'     => __( 'Align', 'jupiterx-core' ),
	'column'    => '4',
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-content',
			'property' => 'text-align',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => "{$section}_typography",
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'portfolio-single-post-content',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-portfolio .jupiterx-post-content',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => "{$section}_divider",
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => "{$section}_spacing",
	'section'   => $section,
	'css_var'   => 'portfolio-single-post-content',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-portfolio .jupiterx-post-content',
		],
	],
] );
