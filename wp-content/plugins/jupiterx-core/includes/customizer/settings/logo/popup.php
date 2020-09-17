<?php
/**
 * Add Jupiter Logo popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Insert button under Site Identity section.
JupiterX_Customizer::add_field( [
	'priority' => 0,
	'type'     => 'jupiterx-popup',
	'settings' => 'jupiterx_popup_logo_switch',
	'section'  => 'title_tagline',
	'label'    => __( 'Site Logo', 'jupiterx-core' ),
	'text'     => __( 'Logo', 'jupiterx-core' ),
	'target'   => 'jupiterx_logo',
] );

// Layout popup.
JupiterX_Customizer::add_section( 'jupiterx_logo', [
	'title'  => __( 'Logo', 'jupiterx-core' ),
	'type'   => 'popup',
	'tabs'   => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
	],
	'hidden' => true,
	'help'   => [
		'url'   => 'https://themes.artbees.net/docs/adding-multiple-versions-of-logo-to-website',
		'title' => __( 'Adding Multiple versions of logo to website', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_logo_settings', [
	'popup' => 'jupiterx_logo',
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
