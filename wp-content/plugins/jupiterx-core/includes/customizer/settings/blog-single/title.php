<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Title tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_post_single_title';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => "{$section}_align",
	'section'   => $section,
	'css_var'   => 'post-single-title-align',
	'label'     => __( 'Align', 'jupiterx-core' ),
	'column'    => '4',
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-title',
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
	'css_var'    => 'post-single-title',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-post .jupiterx-post-title',
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
	'css_var'   => 'post-single-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-title',
		],
	],
] );
