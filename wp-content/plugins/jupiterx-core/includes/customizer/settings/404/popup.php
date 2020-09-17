<?php
/**
 * Add Jupiter 404 popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Layout popup.
JupiterX_Customizer::add_section( 'jupiterx_404', [
	'panel' => 'jupiterx_pages',
	'title' => __( '404', 'jupiterx-core' ),
	'type'  => 'popup',
	'tabs'  => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
	],
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/setting-custom-template-for-404-page',
		'title' => __( 'Setting custom template for 404 page', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_404_settings', [
	'popup' => 'jupiterx_404',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
	'help' => [
		'url' => 'google.com',
		'title' => 'google.com',
	],
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
