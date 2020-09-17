<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Typography tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar_widgets_title';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_sidebar_widgets_title_align',
	'section'   => $section,
	'label'     => esc_html__( 'Align', 'jupiterx-core' ),
	'css_var'   => 'sidebar-widgets-title-align',
	'column'    => '4',
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget .card-title',
			'property' => 'text-align',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_sidebar_widgets_title_typography',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'sidebar-widgets-title',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget .card-title',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_widgets_title_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_sidebar_widgets_title_spacing',
	'section'   => $section,
	'css_var'   => 'sidebar-widgets-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget .card-title',
		],
	],
] );
