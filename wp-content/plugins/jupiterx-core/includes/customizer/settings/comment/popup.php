<?php
/**
 * Add Jupiter Comment popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$popups = [
	'title'        => __( 'Title', 'jupiterx-core' ),
	'field'        => __( 'Field', 'jupiterx-core' ),
	'button'       => __( 'Button', 'jupiterx-core' ),
	'avatar'       => __( 'Avater', 'jupiterx-core' ),
	'name'         => __( 'Name', 'jupiterx-core' ),
	'date'         => __( 'Date', 'jupiterx-core' ),
	'comment_text' => __( 'Comment Text', 'jupiterx-core' ),
	'action_link'  => __( 'Action Link', 'jupiterx-core' ),
];

// Comment popup.
JupiterX_Customizer::add_section( 'jupiterx_comment', [
	'panel'  => 'jupiterx_elements',
	'title'  => __( 'Comment', 'jupiterx-core' ),
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
JupiterX_Customizer::add_section( 'jupiterx_comment_settings', [
	'popup' => 'jupiterx_comment',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_comment_styles', [
	'popup' => 'jupiterx_comment',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-child-popup',
	'settings'   => 'jupiterx_comment_styles_popups',
	'section'    => 'jupiterx_comment_styles',
	'target'     => 'jupiterx_comment',
	'choices'    => [
		'title'        => __( 'Title', 'jupiterx-core' ),
		'field'        => __( 'Field', 'jupiterx-core' ),
		'button'       => __( 'Button', 'jupiterx-core' ),
		'avatar'       => __( 'Avatar', 'jupiterx-core' ),
		'name'         => __( 'Name', 'jupiterx-core' ),
		'date'         => __( 'Date', 'jupiterx-core' ),
		'comment_text' => __( 'Comment Text', 'jupiterx-core' ),
		'action_link'  => __( 'Action Links', 'jupiterx-core' ),
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_comment_' . $popup_id, [
		'popup' => 'jupiterx_comment',
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
