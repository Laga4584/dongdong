<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'widgets_title'     => __( 'Widgets Title', 'jupiterx-core' ),
	'widgets_text'      => __( 'Widgets Text', 'jupiterx-core' ),
	'widgets_link'      => __( 'Widgets Link', 'jupiterx-core' ),
	'widgets_thumbnail' => __( 'Widgets Thumbnail', 'jupiterx-core' ),
	'widgets_container' => __( 'Widgets Container', 'jupiterx-core' ),
	'divider'           => __( 'Divider', 'jupiterx-core' ),
];

// Layout popup.
JupiterX_Customizer::add_section( 'jupiterx_sidebar', [
	'title'    => __( 'Layout', 'jupiterx-core' ),
	'type'     => 'popup',
	'priority' => 130,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'popups'   => $popups,
	'help'     => [
		'url'   => 'https://themes.artbees.net/docs/adding-a-sidebar-globally',
		'title' => __( 'Adding a Sidebar globally', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_sidebar_settings', [
	'popup' => 'jupiterx_sidebar',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_sidebar_styles', [
	'popup' => 'jupiterx_sidebar',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_sidebar_styles_popups',
	'section'  => 'jupiterx_sidebar_styles',
	'target'   => 'jupiterx_sidebar',
	'choices'  => $popups,
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_sidebar_' . $popup_id, [
		'popup' => 'jupiterx_sidebar',
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
