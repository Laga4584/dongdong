<?php
/**
 * Add Jupiter Title Bar popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'title'      => __( 'Title', 'jupiterx-core' ),
	'subtitle'   => __( 'Subtitle', 'jupiterx-core' ),
	'breadcrumb' => __( 'Breadcrumb', 'jupiterx-core' ),
	'container'  => __( 'Container', 'jupiterx-core' ),
];

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_title_bar', [
	'title'    => __( 'Title Bar', 'jupiterx-core' ),
	'type'     => 'popup',
	'priority' => 125,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'popups'   => $popups,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/including-excluding-pages-from-displaying-the-title-bar/',
		'title' => __( 'Including/Excluding pages from displaying the Title Bar', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_title_bar_settings', [
	'popup' => 'jupiterx_title_bar',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_title_bar_styles', [
	'popup' => 'jupiterx_title_bar',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_title_bar_styles_popups',
	'section'  => 'jupiterx_title_bar_styles',
	'target'   => 'jupiterx_title_bar',
	'choices'  => $popups,
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_title_bar_' . $popup_id, [
		'popup' => 'jupiterx_title_bar',
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
