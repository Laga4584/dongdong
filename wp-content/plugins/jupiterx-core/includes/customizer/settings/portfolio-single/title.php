<?php
/**
 * Add Jupiter settings for Portfolio > Styles > Title tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_portfolio_single_title';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_portfolio_single_title_align',
	'section'   => $section,
	'label'     => __( 'Align', 'jupiterx-core' ),
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'portfolio-single-title-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-title',
			'property' => 'text-align',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_portfolio_single_title_typography',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'portfolio-single-title',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-portfolio .jupiterx-post-title',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_title_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_portfolio_single_title_spacing',
	'section'   => $section,
	'css_var'   => 'portfolio-single-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-portfolio .jupiterx-post-title',
		],
	],
] );

