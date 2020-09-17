<?php
/**
 * Add Jupiter Fonts & Typography popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'body'  => __( 'Body', 'jupiterx-core' ),
	'links' => __( 'Links', 'jupiterx-core' ),
	'h1'    => __( 'Heading 1', 'jupiterx-core' ),
	'h2'    => __( 'Heading 2', 'jupiterx-core' ),
	'h3'    => __( 'Heading 3', 'jupiterx-core' ),
	'h4'    => __( 'Heading 4', 'jupiterx-core' ),
	'h5'    => __( 'Heading 5', 'jupiterx-core' ),
	'h6'    => __( 'Heading 6', 'jupiterx-core' ),
];

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_typography', [
	'title'    => __( 'Fonts & Typography', 'jupiterx-core' ),
	'type'     => 'popup',
	'priority' => 100,
	'tabs'     => [
		'settings' => __( 'Fonts', 'jupiterx-core' ),
		'styles'   => __( 'Typography', 'jupiterx-core' ),
	],
	'popups'   => $popups,
	'help'     => [
		'url'   => 'https://themes.artbees.net/docs/changing-typography-for-body-headings-and-links',
		'title' => __( 'Changing typography for Body, Headings and Links', 'jupiterx-core' ),
	],
] );

// Fonts tab.
JupiterX_Customizer::add_section( 'jupiterx_typography_settings', [
	'popup' => 'jupiterx_typography',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Typography tab.
JupiterX_Customizer::add_section( 'jupiterx_typography_styles', [
	'popup' => 'jupiterx_typography',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Typography warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_typography_styles_warning',
	'section'         => 'jupiterx_typography_styles',
	'label'           => __( 'Learn how to use the following settings properly.', 'jupiterx-core' ),
	'jupiterx_url'    => 'https://themes.artbees.net/docs/plugin-conflicts-with-jupiter-x',
	'active_callback' => 'jupiterx_is_help_links',
] );

// Typography tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_typography_styles_popups',
	'section'  => 'jupiterx_typography_styles',
	'target'   => 'jupiterx_typography',
	'choices'  => $popups,
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_typography_' . $popup_id, [
		'popup' => 'jupiterx_typography',
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
