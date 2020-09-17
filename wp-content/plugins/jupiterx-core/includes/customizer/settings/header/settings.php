<?php
/**
 * Add Jupiter settings for Header > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_settings';

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_header_warning',
	'section'         => $section,
	'label'           => __( 'Learn how to use the following settings properly.', 'jupiterx-core' ),
	'jupiterx_url'    => 'https://themes.artbees.net/docs/plugin-conflicts-with-jupiter-x',
	'active_callback' => function() {
		return class_exists( '\ElementorPro\Plugin' ) && jupiterx_is_help_links();
	},
] );

// Type.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_header_type',
	'section'  => $section,
	'label'    => __( 'Type', 'jupiterx-core' ),
	'default'  => '',
	'choices'  => [
		'' => [
			'label' => __( 'Default', 'jupiterx-core' ),
		],
		'_custom' => [
			'label' => __( 'Custom', 'jupiterx-core' ),
			'pro'   => true,
		],
	],
] );

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_header_align',
	'css_var'  => 'header-align',
	'section'  => $section,
	'label'    => __( 'Align', 'jupiterx-core' ),
	'column'   => '4',
	'default'  => [
		'desktop' => 'row',
		'tablet'  => 'row',
		'mobile'  => 'row',
	],
	'choices' => JupiterX_Customizer_Utils::get_align( 'flex-direction', [ 'center' ] ),
	'output'  => [
		[
			'element'  => '.jupiterx-site-navbar > div',
			'property' => 'flex-direction',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Overlap content.
JupiterX_Customizer::add_responsive_field( [
	'type'            => 'jupiterx-toggle',
	'settings'        => 'jupiterx_header_overlap',
	'css_var'         => 'header-overlap',
	'section'         => $section,
	'label'           => __( 'Overlap Content', 'jupiterx-core' ),
	'column'          => '4',
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Full width.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-toggle',
	'settings'        => 'jupiterx_header_full_width',
	'section'         => $section,
	'label'           => __( 'Full Width', 'jupiterx-core' ),
	'column'          => '4',
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Display elements.
JupiterX_Customizer::add_responsive_field( [
	'type'            => 'jupiterx-multicheck',
	'settings'        => 'jupiterx_header_elements',
	'section'         => $section,
	'css_var'         => 'header_elements',
	'label'           => __( 'Display Elements', 'jupiterx-core' ),
	'default'         => [
		'desktop' => [ 'logo', 'menu', 'search', 'cart' ],
		'tablet'  => [ 'logo', 'menu', 'search', 'cart' ],
		'mobile'  => [ 'logo', 'menu', 'search', 'cart' ],
	],
	'choices'         => [
		'logo'      => __( 'Logo', 'jupiterx-core' ),
		'menu'      => __( 'Menu', 'jupiterx-core' ),
		'search'    => __( 'Search', 'jupiterx-core' ),
		'cart'      => __( 'Cart', 'jupiterx-core' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Behavior.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-choose',
	'settings'        => 'jupiterx_header_behavior',
	'css_var'         => 'header-behavior',
	'section'         => $section,
	'label'           => __( 'Behavior', 'jupiterx-core' ),
	'column'          => '5',
	'default'         => 'static',
	'choices'         => [
		'static'  => [
			'label' => __( 'Static', 'jupiterx-core' ),
		],
		'fixed' => [
			'label' => __( 'Fixed', 'jupiterx-core' ),
		],
		'sticky' => [
			'label' => __( 'Sticky', 'jupiterx-core' ),
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Position.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-choose',
	'settings'        => 'jupiterx_header_position',
	'css_var'         => 'header-position',
	'section'         => $section,
	'label'           => __( 'Position', 'jupiterx-core' ),
	'column'          => '3',
	'default'         => 'top',
	'choices'         => [
		'top'  => [
			'label' => __( 'Top', 'jupiterx-core' ),
		],
		'bottom' => [
			'label' => __( 'Bottom', 'jupiterx-core' ),
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_header_behavior',
			'operator' => '===',
			'value'    => 'fixed',
		],
	],
] );

// Offset.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-text',
	'settings'        => 'jupiterx_header_offset',
	'css_var'         => 'header-offset',
	'section'         => $section,
	'label'           => __( 'Offset', 'jupiterx-core' ),
	'column'          => '3',
	'inputType'       => 'number',
	'unit'            => 'px',
	'default'         => 500,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_header_behavior',
			'operator' => '===',
			'value'    => 'sticky',
		],
	],
] );

// Behavior tablet.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-toggle',
	'settings'        => 'jupiterx_header_behavior_tablet',
	'css_var'         => 'header-behavior-tablet',
	'section'         => $section,
	'label'           => __( 'Enable on Tablet', 'jupiterx-core' ),
	'column'          => '6',
	'default'         => true,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_header_behavior',
			'operator' => '!==',
			'value'    => 'static',
		],
	],
] );

// Behavior mobile.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-toggle',
	'settings'        => 'jupiterx_header_behavior_mobile',
	'css_var'         => 'header-behavior-mobile',
	'section'         => $section,
	'label'           => __( 'Enable on Mobile', 'jupiterx-core' ),
	'column'          => '6',
	'default'         => true,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '',
		],
		[
			'setting'  => 'jupiterx_header_behavior',
			'operator' => '!==',
			'value'    => 'static',
		],
	],
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-pro-box',
	'settings'        => 'jupiterx_header_custom_pro_box',
	'section'         => $section,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '===',
			'value'    => '_custom',
		],
	],
] );
