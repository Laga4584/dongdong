<?php
/**
 * Add Jupiter prtfolio archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

// Portfolio popup.
JupiterX_Customizer::add_section( 'jupiterx_portfolio_archive', [
	'panel'    => 'jupiterx_portfolio_panel',
	'title'    => __( 'Portfolio Archive', 'jupiterx-core' ),
	'type'     => 'popup',
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
	],
	'preview' => true,
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_portfolio_archive_settings', [
	'popup' => 'jupiterx_portfolio_archive',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
