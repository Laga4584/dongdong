<?php
/**
 * Add Jupiter blog archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Blog popup.
JupiterX_Customizer::add_section( 'jupiterx_blog_archive', [
	'panel'    => 'jupiterx_blog_panel',
	'title'    => __( 'Blog Archive', 'jupiterx-core' ),
	'type'     => 'popup',
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
	],
	'preview' => true,
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_blog_archive_settings', [
	'popup' => 'jupiterx_blog_archive',
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
