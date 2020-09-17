<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'logo'             => __( 'Logo', 'jupiterx-core' ),
	'Menu'             => __( 'Menu', 'jupiterx-core' ),
	'Submenu'          => __( 'Submenu', 'jupiterx-core' ),
	'search'           => __( 'Search', 'jupiterx-core' ),
	'container'        => __( 'Container', 'jupiterx-core' ),
	'sticky_container' => __( 'Sticky Container', 'jupiterx-core' ),
	'sticky_logo'      => __( 'Sticky Logo', 'jupiterx-core' ),
];

// Header popup.
JupiterX_Customizer::add_section( 'jupiterx_header', [
	'title'    => __( 'Header', 'jupiterx-core' ),
	'type'     => 'popup',
	'priority' => 120,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'popups'   => $popups,
	'help'     => [
		'url'   => 'https://themes.artbees.net/docs/assigning-the-header-globally',
		'title' => __( 'Assigning the Header Globally', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_header_settings', [
	'popup' => 'jupiterx_header',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_header_styles', [
	'popup' => 'jupiterx_header',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_header_styles_warning',
	'section'         => 'jupiterx_header_styles',
	'label'           => __( 'Learn how to use the following settings properly.', 'jupiterx-core' ),
	'jupiterx_url'    => 'https://themes.artbees.net/docs/plugin-conflicts-with-jupiter-x',
	'active_callback' => function() {
		return class_exists( '\ElementorPro\Plugin' ) && jupiterx_is_help_links();
	},
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_header_styles_popups',
	'section'  => 'jupiterx_header_styles',
	'target'   => 'jupiterx_header',
	'choices'  => [
		'logo'          => __( 'Logo', 'jupiterx-core' ),
		'Menu'          => __( 'Menu', 'jupiterx-core' ),
		'Submenu'       => __( 'Submenu', 'jupiterx-core' ),
		'search'        => __( 'Search', 'jupiterx-core' ),
		'container'     => __( 'Container', 'jupiterx-core' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '!=',
			'value'    => '_custom',
		],
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_header_styles_popups_sticky',
	'section'  => 'jupiterx_header_styles',
	'target'   => 'jupiterx_header',
	'choices'  => [
		'sticky_logo'      => __( 'Sticky Logo', 'jupiterx-core' ),
		'sticky_container' => __( 'Sticky Container', 'jupiterx-core' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_behavior',
			'operator' => '==',
			'value'    => 'sticky',
		],
		[
			'setting'  => 'jupiterx_header_type',
			'operator' => '!=',
			'value'    => '_custom',
		],
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_header_' . $popup_id, [
		'popup' => 'jupiterx_header',
		'type'  => 'pane',
		'pane'  => [
			'type' => 'popup',
			'id'   => $popup_id,
		],
	] );
}

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
