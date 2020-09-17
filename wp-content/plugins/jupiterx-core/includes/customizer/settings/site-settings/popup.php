<?php
/**
 * Add Jupiter Site Settings popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'body'        => __( 'Body', 'jupiterx-core' ),
	'main'        => __( 'Main', 'jupiterx-core' ),
	'body_border' => __( 'Body Border', 'jupiterx-core' ),
	'container'   => __( 'Container', 'jupiterx-core' ),
];

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_site', [
	'title'    => __( 'Site Settings', 'jupiterx-core' ),
	'type'     => 'popup',
	'priority' => 90,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'popups'   => $popups,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/setting-container-width-in-jupiter-x',
		'title' => __( 'Setting container width in Jupiter X', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_site_settings', [
	'popup' => 'jupiterx_site',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_site_styles', [
	'popup' => 'jupiterx_site',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_site_styles_popups',
	'section'  => 'jupiterx_site_styles',
	'target'   => 'jupiterx_site',
	'choices'  => [
		'body' => __( 'Body', 'jupiterx-core' ),
		'main' => __( 'Main', 'jupiterx-core' ),
	],
] );

// Styles tab > Child popups for boxed layout.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_site_styles_popups_body_border',
	'section'  => 'jupiterx_site_styles',
	'target'   => 'jupiterx_site',
	'choices'  => [
		'body_border' => __( 'Body Border', 'jupiterx-core' ),
	],
	'active_callback'  => [
		[
			'setting'  => 'jupiterx_site_width',
			'operator' => '===',
			'value'    => 'full_width',
		],
		[
			'setting'  => 'jupiterx_site_body_border_enabled',
			'operator' => '==',
			'value'    => true,
		],
	],
] );

// Styles tab > Child popups for boxed layout.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_site_styles_popups_boxed',
	'section'  => 'jupiterx_site_styles',
	'target'   => 'jupiterx_site',
	'choices'  => [
		'container' => __( 'Container', 'jupiterx-core' ),
	],
	'active_callback'  => [
		[
			'setting'  => 'jupiterx_site_width',
			'operator' => '===',
			'value'    => 'boxed',
		],
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_site_' . $popup_id, [
		'popup' => 'jupiterx_site',
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
