<?php
/**
 * Add Jupiter Page single popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'title'          => __( 'Title', 'jupiterx-core' ),
	'featured_image' => __( 'Featured Image', 'jupiterx-core' ),
	'social_share'   => __( 'Social Share', 'jupiterx-core' ),
];

// Page Single Popup.
JupiterX_Customizer::add_section( 'jupiterx_page_single', [
	'panel'  => 'jupiterx_pages',
	'title'  => __( 'Page Single', 'jupiterx-core' ),
	'type'   => 'popup',
	'tabs'   => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'popups'  => $popups,
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/display-settings-for-blog-shop-single-pages',
		'title' => __( 'Display settings for Blog, Shop single pages', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_page_single_settings', [
	'popup' => 'jupiterx_page_single',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_page_single_styles', [
	'popup' => 'jupiterx_page_single',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-child-popup',
	'settings'   => 'jupiterx_page_single_styles_popups',
	'section'    => 'jupiterx_page_single_styles',
	'target'     => 'jupiterx_page_single',
	'choices'    => [
		'title'          => __( 'Title', 'jupiterx-core' ),
		'featured_image' => __( 'Featured Image', 'jupiterx-core' ),
		'social_share'   => [
			'label' => __( 'Social Share', 'jupiterx-core' ),
			'pro'   => true,
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_page_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_page_single_' . $popup_id, [
		'popup' => 'jupiterx_page_single',
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
