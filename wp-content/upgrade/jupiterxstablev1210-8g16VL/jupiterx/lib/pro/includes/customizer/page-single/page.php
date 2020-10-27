<?php
/**
 * Customizer settings for page single.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.9.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	// Child popup styles.
	JupiterX_Customizer::update_field( 'jupiterx_page_single_styles_popups', [
		'choices' => [
			'title'          => __( 'Title', 'jupiterx' ),
			'featured_image' => __( 'Featured Image', 'jupiterx' ),
			'social_share'   => __( 'Social Share', 'jupiterx' ),
		],
	] );

	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_page_single_template_type', [
		'choices' => [
			''        => __( 'Default', 'jupiterx' ),
			'_custom' => __( 'Custom', 'jupiterx' ),
		],
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_page_single_custom_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_page_single_social_share_pro_box' );

} );

add_action( 'jupiterx_page_single_template_type_after_field', function() {
	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_page_single_template',
		'section'         => 'jupiterx_page_single_settings',
		'label'           => __( 'My Templates', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => 'single',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_page_single_template_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );

// Social Share.
add_action( 'jupiterx_page_single_social_share_pro_box_after_field', function() {
	// Social Network Filter.
	JupiterX_Customizer::add_field( [
		'type'          => 'jupiterx-multicheck',
		'settings'      => 'jupiterx_page_single_social_share_filter',
		'section'       => 'jupiterx_page_single_social_share',
		'default'       => [
			'facebook',
			'twitter',
			'linkedin',
		],
		'icon_choices'  => [
			'facebook'    => 'share-facebook-f',
			'twitter'     => 'share-twitter',
			'pinterest'   => 'share-pinterest-p',
			'linkedin'    => 'share-linkedin-in',
			'reddit'      => 'share-reddit-alien',
			'digg'        => 'share-digg',
			'email'       => 'share-email',
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_page_single_social_share_divider',
		'section'  => 'jupiterx_page_single_social_share',
	] );

	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_page_single_social_share_align',
		'section'   => 'jupiterx_page_single_social_share',
		'css_var'   => 'page-single-social-share-align',
		'label'     => __( 'Align', 'jupiterx' ),
		'column'    => '4',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => '',
			'tablet'  => 'center',
			'mobile'  => 'center',
		],
		'choices'   => JupiterX_Customizer_Utils::get_align( 'justify-content' ),
		'output'    => [
			[
				'element'  => 'body.page .jupiterx-social-share-inner',
				'property' => 'justify-content',
			],
		],
	] );

	// Name.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_page_single_social_share_name',
		'section'   => 'jupiterx_page_single_social_share',
		'label'     => __( 'Name', 'jupiterx' ),
		'column'    => '3',
		'default'   => true,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_page_single_social_share_divider_2',
		'section'  => 'jupiterx_page_single_social_share',
	] );

	// Link spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_page_single_social_share_link_spacing',
		'section'   => 'jupiterx_page_single_social_share',
		'css_var'   => 'page-single-social-share-link',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'default'   => [
			'desktop' => [
				'padding_top'    => 0.4,
				jupiterx_get_direction( 'padding_right' ) => 0.75,
				'padding_bottom' => 0.4,
				jupiterx_get_direction( 'padding_left' ) => 0.75,
			],
		],
		'output'    => [
			[
				'element' => 'body.page .jupiterx-social-share-link',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_page_single_social_share_divider_3',
		'section'  => 'jupiterx_page_single_social_share',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_page_single_social_share_spacing',
		'section'   => 'jupiterx_page_single_social_share',
		'css_var'   => 'page-single-social-share',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_top' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => 'body.page .jupiterx-social-share',
			],
		],
	] );
} );
