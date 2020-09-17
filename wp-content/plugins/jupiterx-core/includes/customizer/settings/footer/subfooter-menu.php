<?php
/**
 * Add Jupiter settings for Footer > Sub Footer > Styles > Menu popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer_sub_menu';

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_footer_sub_menu_links_typography',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'subfooter-menu-links',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform' ],
	'default'    => [
		'desktop' => [
			'color' => '#f8f9fa',
		],
	],
	'output'     => [
		[
			'element' => '.jupiterx-subfooter-menu li a',
		],
	],
] );

// Space between.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_footer_sub_menu_links_space_between',
	'section'   => $section,
	'css_var'   => 'subfooter-menu-links-space-between',
	'column'    => '4',
	'icon'      => 'space-between',
	'units'     => [ 'px' ],
	'transport' => 'postMessage',
	'default'   => [
		'size' => 9,
		'unit' => 'px',
	],
	'output'    => [
		[
			'element'  => '.jupiterx-subfooter-menu-container ul',
			'property' => 'margin-left',
			'value_pattern' => 'calc(-$ / 2)',
		],
		[
			'element'  => '.jupiterx-subfooter-menu-container ul',
			'property' => 'margin-right',
			'value_pattern' => 'calc(-$ / 2)',
		],
		[
			'element'  => '.jupiterx-subfooter-menu-container ul > li',
			'property' => 'padding-left',
			'value_pattern' => 'calc($ / 2)',
		],
		[
			'element'  => '.jupiterx-subfooter-menu-container ul > li',
			'property' => 'padding-right',
			'value_pattern' => 'calc($ / 2)',
		],
	],
] );

// Hover.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'settings'   => 'jupiterx_footer_sub_menu_label',
	'section'    => $section,
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
] );

// Hover color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_footer_sub_menu_links_hover_color',
	'section'   => $section,
	'css_var'   => 'subfooter-menu-links-hover-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-subfooter-menu li a:hover',
			'property' => 'color',
		],
	],
] );

// Hover text decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_footer_sub_menu_links_hover_text_decoration',
	'section'     => $section,
	'css_var'     => 'subfooter-menu-links-hover-text-decoration',
	'column'      => '6',
	'icon'        => 'text-decoration',
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-subfooter-menu li a:hover',
			'property' => 'text-decoration',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_sub_menu_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_sub_menu_spacing',
	'section'   => $section,
	'css_var'   => 'subfooter-menu',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-subfooter-menu-container',
		],
	],
] );
