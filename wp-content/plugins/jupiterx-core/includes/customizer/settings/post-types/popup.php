<?php
/**
 * Add custom post type popups to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

if ( ! function_exists( 'jupiterx_get_post_types' ) ) {
	return;
}

$jupiterx_post_types = jupiterx_get_post_types( 'objects' );

if ( empty( $jupiterx_post_types ) ) {
	return;
}

$popups = [
	'post_content' => __( 'Post Content', 'jupiterx-core' ),
];

/**
 * Start registering panel, section and fields to customizer.
 */
foreach ( $jupiterx_post_types as $post_type_id => $jupiterx_post_type_item ) {

	// Post type panel.
	JupiterX_Customizer::add_panel( "jupiterx_{$jupiterx_post_type_item->name}_panel", [
		'title' => $jupiterx_post_type_item->label,
		'panel' => 'jupiterx_post_types',
	] );

	// Single popup.
	JupiterX_Customizer::add_section( "jupiterx_{$jupiterx_post_type_item->name}_single", [
		'panel'   => "jupiterx_{$jupiterx_post_type_item->name}_panel",
		'title'   => $jupiterx_post_type_item->label . ' ' . __( 'Single', 'jupiterx-core' ),
		'type'    => 'popup',
		'tabs'    => [
			'settings' => __( 'Settings', 'jupiterx-core' ),
			'styles'   => __( 'Styles', 'jupiterx-core' ),
		],
		'popups'  => $popups,
		'preview' => [
			'post_type' => $jupiterx_post_type_item->name,
			'single'    => true,
		],
	] );

	// Settings tab.
	JupiterX_Customizer::add_section( "jupiterx_{$jupiterx_post_type_item->name}_single_settings", [
		'popup' => "jupiterx_{$jupiterx_post_type_item->name}_single",
		'type'  => 'pane',
		'pane'  => [
			'type' => 'tab',
			'id'   => 'settings',
		],
	] );

	// Type.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-choose',
		'settings' => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
		'section'  => "jupiterx_{$jupiterx_post_type_item->name}_single_settings",
		'label'    => __( 'Type', 'jupiterx-core' ),
		'default'  => '',
		'choices'  => [
			'' => [
				'label' => __( 'Default', 'jupiterx-core' ),
			],
			'_custom' => [
				'label' => __( 'Custom', 'jupiterx-core' ),
				'pro'   => true,
			],
		],
	] );

	// Pro Box.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-pro-box',
		'settings'        => "jupiterx_{$jupiterx_post_type_item->name}_single_custom_pro_box",
		'section'         => "jupiterx_{$jupiterx_post_type_item->name}_single_settings",
		'active_callback' => [
			[
				'setting'  => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );

	// Styles tab.
	JupiterX_Customizer::add_section( "jupiterx_{$jupiterx_post_type_item->name}_single_styles", [
		'popup' => "jupiterx_{$jupiterx_post_type_item->name}_single",
		'type'  => 'pane',
		'pane'  => [
			'type' => 'tab',
			'id'   => 'styles',
		],
	] );

	// Styles tab > Child popups.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-child-popup',
		'settings'   => "jupiterx_{$jupiterx_post_type_item->name}_single_styles_popups",
		'section'    => "jupiterx_{$jupiterx_post_type_item->name}_single_styles",
		'target'     => "jupiterx_{$jupiterx_post_type_item->name}_single",
		'choices'    => [
			'post_content'   => __( 'Post Content', 'jupiterx-core' ),
		],
		'active_callback' => [
			[
				'setting'  => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
				'operator' => '===',
				'value'    => '',
			],
		],
	] );

	// Create popup children.
	foreach ( $popups as $popup_id => $label ) {
		JupiterX_Customizer::add_section( "jupiterx_{$jupiterx_post_type_item->name}_single_" . $popup_id, [
			'popup' => "jupiterx_{$jupiterx_post_type_item->name}_single",
			'type'  => 'pane',
			'pane'  => [
				'type' => 'popup',
				'id'   => $popup_id,
			],
		] );
	}

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => "jupiterx_{$jupiterx_post_type_item->name}_single_label_1",
		'section'  => "jupiterx_{$jupiterx_post_type_item->name}_single_settings",
		'label'    => __( 'Display Elements', 'jupiterx-core' ),
		'active_callback' => [
			[
				'setting'  => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
				'operator' => '===',
				'value'    => '',
			],
		],
	] );

	// Display elements.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-multicheck',
		'settings' => "jupiterx_{$jupiterx_post_type_item->name}_single_elements",
		'section'  => "jupiterx_{$jupiterx_post_type_item->name}_single_settings",
		'default'  => [
			'featured_image',
		],
		'choices'  => [
			'featured_image' => __( 'Featured Image', 'jupiterx-core' ),
			'title'          => __( 'Title', 'jupiterx-core' ),
			'date'           => __( 'Date', 'jupiterx-core' ),
			'author'         => __( 'Author', 'jupiterx-core' ),
			'social_share'   => __( 'Social Share', 'jupiterx-core' ),
			'navigation'     => __( 'Navigation', 'jupiterx-core' ),
			'author_box'     => __( 'Author Box', 'jupiterx-core' ),
			'comments'       => __( 'Comments', 'jupiterx-core' ),
		],
		'active_callback' => [
			[
				'setting'  => "jupiterx_{$jupiterx_post_type_item->name}_single_template_type",
				'operator' => '===',
				'value'    => '',
			],
		],
	] );

	if ( $jupiterx_post_type_item->has_archive ) :

		// Archive popup.
		JupiterX_Customizer::add_section( "jupiterx_{$jupiterx_post_type_item->name}_archive", [
			'panel'   => "jupiterx_{$jupiterx_post_type_item->name}_panel",
			'title'   => $jupiterx_post_type_item->label . ' ' . __( 'Archive', 'jupiterx-core' ),
			'type'    => 'popup',
			'tabs'    => [
				'settings' => __( 'Settings', 'jupiterx-core' ),
			],
			'preview' => [
				'post_type' => $jupiterx_post_type_item->name,
				'archive'   => true,
			],
		] );

		// Settings tab.
		JupiterX_Customizer::add_section( "jupiterx_{$jupiterx_post_type_item->name}_archive_settings", [
			'popup' => "jupiterx_{$jupiterx_post_type_item->name}_archive",
			'type'  => 'pane',
			'pane'  => [
				'type' => 'tab',
				'id'   => 'settings',
			],
		] );

		// Template.
		JupiterX_Customizer::add_field( [
			'type'            => 'jupiterx-template',
			'settings'        => "jupiterx_{$jupiterx_post_type_item->name}_archive_template",
			'section'         => "jupiterx_{$jupiterx_post_type_item->name}_archive_settings",
			'label'           => __( 'My Templates', 'jupiterx-core' ),
			'placeholder'     => __( 'Select one', 'jupiterx-core' ),
			'template_type'   => 'archive',
			'locked'          => true,
		] );

		// Spacing.
		JupiterX_Customizer::add_responsive_field( [
			'type'      => 'jupiterx-box-model',
			'settings'  => "jupiterx_{$jupiterx_post_type_item->name}_archive",
			'section'   => "jupiterx_{$jupiterx_post_type_item->name}_archive_settings",
			'css_var'   => "{$jupiterx_post_type_item->name}-archive-spacing",
			'transport' => 'postMessage',
			'output'    => [
				[
					'element' => ".post-type-archive-{$jupiterx_post_type_item->name} .jupiterx-main-content, .post-type-archive-{$jupiterx_post_type_item->name} .jupiterx-main-content",
				],
			],
		] );
	endif;

	// Load all the settings.
	foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
		require_once $setting;
	}
}
