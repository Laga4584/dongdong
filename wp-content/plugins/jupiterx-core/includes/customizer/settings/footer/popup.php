<?php
/**
 * Add Jupiter Footer popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$popups = [
	'widgets_title'         => __( 'Widgets Title', 'jupiterx-core' ),
	'widgets_text'          => __( 'Widgets Text', 'jupiterx-core' ),
	'widgets_link'          => __( 'Widgets Link', 'jupiterx-core' ),
	'widgets_thumbnail'     => __( 'Widgets Thumbnail', 'jupiterx-core' ),
	'widgets_container'     => __( 'Widgets Container', 'jupiterx-core' ),
	'widgets_divider'       => __( 'Widgets Divider', 'jupiterx-core' ),
	'widget_area_container' => __( 'Widget Area Container', 'jupiterx-core' ),
	'sub_copyright'         => __( 'Sub Footer Copyright', 'jupiterx-core' ),
	'sub_menu'              => __( 'Sub Footer Menu', 'jupiterx-core' ),
	'sub_container'         => __( 'Sub Footer Container', 'jupiterx-core' ),
];

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_footer', [
	'title'    => __( 'Footer', 'jupiterx-core' ),
	'type'     => 'popup',
	'priority' => 135,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'popups'   => $popups,
	'help'     => [
		'url'   => 'https://themes.artbees.net/docs/assigning-the-footer-globally',
		'title' => __( 'Assigning the Footer Globally', 'jupiterx-core' ),
	],
] );

// Settings tab.
JupiterX_Customizer::add_section( 'jupiterx_footer_settings', [
	'popup' => 'jupiterx_footer',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'settings',
	],
] );

// Styles tab.
JupiterX_Customizer::add_section( 'jupiterx_footer_styles', [
	'popup' => 'jupiterx_footer',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'tab',
		'id'   => 'styles',
	],
] );

// Styles warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_footer_styles_warning',
	'section'         => 'jupiterx_footer_styles',
	'label'           => __( 'Learn how to use the following settings properly.', 'jupiterx-core' ),
	'jupiterx_url'    => 'https://themes.artbees.net/docs/plugin-conflicts-with-jupiter-x',
	'active_callback' => function() {
		return class_exists( '\ElementorPro\Plugin' ) && jupiterx_is_help_links();
	},
] );

// Styles tab > Widgets child popups.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_footer_styles_popups',
	'section'  => 'jupiterx_footer_styles',
	'target'   => 'jupiterx_footer',
	'choices'  => [
		'widgets_title'         => __( 'Widgets Title', 'jupiterx-core' ),
		'widgets_text'          => __( 'Widgets Text', 'jupiterx-core' ),
		'widgets_link'          => __( 'Widgets Link', 'jupiterx-core' ),
		'widgets_thumbnail'     => __( 'Widgets Thumbnail', 'jupiterx-core' ),
		'widgets_container'     => __( 'Widgets Container', 'jupiterx-core' ),
		'widgets_divider'       => __( 'Widgets Divider', 'jupiterx-core' ),
		'widget_area_container' => __( 'Widget Area Container', 'jupiterx-core' ),
	],
	'active_callback'  => [
		[
			'setting'  => 'jupiterx_footer_widget_area',
			'operator' => '===',
			'value'    => true,
		],
		[
			'setting'  => 'jupiterx_footer_type',
			'operator' => '!=',
			'value'    => '_custom',
		],
	],
] );

// Styles tab > Subfooter child popups (sortable).
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-child-popup',
	'settings'   => 'jupiterx_footer_sub_sort_content',
	'section'    => 'jupiterx_footer_styles',
	'target'     => 'jupiterx_footer',
	'sortable'   => true,
	'default'    => [ 'sub_menu', 'sub_copyright' ],
	'choices'    => [
		'sub_copyright' => __( 'Sub Footer Copyright', 'jupiterx-core' ),
		'sub_menu'      => __( 'Sub Footer Menu', 'jupiterx-core' ),
	],
	'active_callback'  => [
		[
			'setting'  => 'jupiterx_footer_sub',
			'operator' => '===',
			'value'    => true,
		],
		[
			'setting'  => 'jupiterx_footer_type',
			'operator' => '!=',
			'value'    => '_custom',
		],
	],
] );

// Styles tab > Subfooter child popups (static).
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-child-popup',
	'settings' => 'jupiterx_footer_sub_styles_popups',
	'section'  => 'jupiterx_footer_styles',
	'target'   => 'jupiterx_footer',
	'choices'  => [
		'sub_container' => __( 'Sub Footer Container', 'jupiterx-core' ),
	],
	'active_callback'  => [
		[
			'setting'  => 'jupiterx_footer_sub',
			'operator' => '===',
			'value'    => true,
		],
		[
			'setting'  => 'jupiterx_footer_type',
			'operator' => '!=',
			'value'    => '_custom',
		],
	],
] );

// Create popup children.
foreach ( $popups as $popup_id => $label ) {
	JupiterX_Customizer::add_section( 'jupiterx_footer_' . $popup_id, [
		'popup' => 'jupiterx_footer',
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
