<?php
/**
 * Add Jupiter portfolio archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'title'          => __( 'Title', 'jupiterx-core' ),
	'meta'           => __( 'Meta', 'jupiterx-core' ),
	'featured_image' => __( 'Featured Image', 'jupiterx-core' ),
	'post_content'   => __( 'Post Content', 'jupiterx-core' ),
	'navigation'     => __( 'Navigation', 'jupiterx-core' ),
	'social_share'   => __( 'Social Share', 'jupiterx-core' ),
	'related_posts'  => __( 'Related Works', 'jupiterx-core' ),
];

// Portfolio single popup.
JupiterX_Customizer::add_section( 'jupiterx_portfolio_single', [
	'panel'  => 'jupiterx_portfolio_panel',
	'title'  => __( 'Portfolio Single', 'jupiterx-core' ),
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
JupiterX_Customizer::add_section( 'jupiterx_portfolio_single_settings', [
	'popup' => 'jupiterx_portfolio_single',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_portfolio_single_styles', [
	'popup' => 'jupiterx_portfolio_single',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles tab > Child popups.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-child-popup',
	'settings'   => 'jupiterx_portfolio_single_styles_popups',
	'section'    => 'jupiterx_portfolio_single_styles',
	'target'     => 'jupiterx_portfolio_single',
	'choices'    => [
		'title'          => __( 'Title', 'jupiterx-core' ),
		'meta'           => __( 'Meta', 'jupiterx-core' ),
		'featured_image' => __( 'Featured Image', 'jupiterx-core' ),
		'post_content'   => __( 'Post Content', 'jupiterx-core' ),
		'navigation'     => [
			'label' => __( 'Navigation', 'jupiterx-core' ),
			'pro'   => true,
		],
		'social_share'   => [
			'label' => __( 'Social Share', 'jupiterx-core' ),
			'pro'   => true,
		],
		'related_posts'  => [
			'label' => __( 'Related Works', 'jupiterx-core' ),
			'pro'   => true,
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_portfolio_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_portfolio_single_' . $popup_id, [
		'popup' => 'jupiterx_portfolio_single',
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
