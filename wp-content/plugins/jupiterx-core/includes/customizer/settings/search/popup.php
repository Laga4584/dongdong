<?php
/**
 * Add Jupiter Search Page popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Layout popup.
JupiterX_Customizer::add_section( 'jupiterx_search', [
	'panel' => 'jupiterx_pages',
	'title' => __( 'Search', 'jupiterx-core' ),
	'type'  => 'popup',
	'tabs'  => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
	],
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/displaying-search-results-from-specific-post-types',
		'title' => __( 'Displaying Search Results from specific Post Types', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_search_settings', [
	'popup' => 'jupiterx_search',
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
