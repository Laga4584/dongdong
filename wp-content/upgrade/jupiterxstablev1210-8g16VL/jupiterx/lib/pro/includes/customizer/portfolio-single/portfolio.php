<?php
/**
 * Customizer settings for Portfolio.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	// Child popup styles.
	JupiterX_Customizer::update_field( 'jupiterx_portfolio_single_styles_popups', [
		'choices' => [
			'title'          => __( 'Title', 'jupiterx' ),
			'meta'           => __( 'Meta', 'jupiterx' ),
			'featured_image' => __( 'Featured Image', 'jupiterx' ),
			'post_content'   => __( 'Post Content', 'jupiterx' ),
			'navigation'     => __( 'Navigation', 'jupiterx' ),
			'social_share'   => __( 'Social Share', 'jupiterx' ),
			'related_posts'  => __( 'Related Works', 'jupiterx' ),
		],
	] );

	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_portfolio_single_template_type', [
		'choices' => [
			''        => __( 'Default', 'jupiterx' ),
			'_custom' => __( 'Custom', 'jupiterx' ),
		],
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_portfolio_single_custom_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_portfolio_single_navigation_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_portfolio_single_social_share_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_portfolio_single_related_posts_pro_box' );
} );

add_action( 'jupiterx_portfolio_single_template_type_after_field', function() {
	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_portfolio_single_template',
		'section'         => 'jupiterx_portfolio_single_settings',
		'label'           => __( 'My Templates', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => 'single',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_portfolio_single_template_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );

// Navigation.
add_action( 'jupiterx_portfolio_single_navigation_pro_box_after_field', function() {
	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_portfolio_single_navigation_label',
		'section'  => 'jupiterx_portfolio_single_navigation',
		'label'    => __( 'Image', 'jupiterx' ),
	] );

	// Image.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-toggle',
		'settings' => 'jupiterx_portfolio_single_navigation_image',
		'section'  => 'jupiterx_portfolio_single_navigation',
		'column'   => '3',
		'default'  => true,
	] );

	// Image border radius.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-input',
		'settings'        => 'jupiterx_portfolio_single_navigation_image_border_radius',
		'section'         => 'jupiterx_portfolio_single_navigation',
		'css_var'         => 'portfolio-single-navigation-image-border-radius',
		'column'          => '4',
		'icon'            => 'corner-radius',
		'units'           => [ 'px', '%' ],
		'transport'       => 'postMessage',
		'output'          => [
			[
				'element'  => '.single-portfolio .jupiterx-post-navigation-link img',
				'property' => 'border-radius',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_portfolio_single_navigation_image',
				'operator' => '==',
				'value'    => true,
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_navigation_divider',
		'section'  => 'jupiterx_portfolio_single_navigation',
	] );

	// Title label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Title', 'jupiterx' ),
		'settings' => 'jupiterx_portfolio_single_navigation_label_2',
		'section'  => 'jupiterx_portfolio_single_navigation',
	] );

	// Title typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_portfolio_single_navigation_title_typography',
		'section'    => 'jupiterx_portfolio_single_navigation',
		'responsive' => true,
		'css_var'    => 'portfolio-single-navigation-title',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-portfolio .jupiterx-post-navigation-title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_navigation_divider_2',
		'section'  => 'jupiterx_portfolio_single_navigation',
	] );

	// Label label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Label', 'jupiterx' ),
		'settings' => 'jupiterx_portfolio_single_navigation_label_3',
		'section'  => 'jupiterx_portfolio_single_navigation',
	] );

	// Label typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_portfolio_single_navigation_label_typography',
		'section'    => 'jupiterx_portfolio_single_navigation',
		'responsive' => true,
		'css_var'    => 'portfolio-single-navigation-label',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-portfolio .jupiterx-post-navigation-label',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_navigation_divider_3',
		'section'  => 'jupiterx_portfolio_single_navigation',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_navigation_spacing',
		'section'   => 'jupiterx_portfolio_single_navigation',
		'css_var'   => 'portfolio-single-navigation',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_top' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-post-navigation',
			],
		],
	] );
} );

// Social Share.
add_action( 'jupiterx_portfolio_single_social_share_pro_box_after_field', function() {
	// Social Network Filter.
	JupiterX_Customizer::add_field( [
		'type'          => 'jupiterx-multicheck',
		'settings'      => 'jupiterx_portfolio_single_social_share_filter',
		'section'       => 'jupiterx_portfolio_single_social_share',
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
		'settings' => 'jupiterx_portfolio_single_social_share_divider',
		'section'  => 'jupiterx_portfolio_single_social_share',
	] );

	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_portfolio_single_social_share_align',
		'section'   => 'jupiterx_portfolio_single_social_share',
		'css_var'   => 'portfolio-single-social-share-align',
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
				'element'  => '.single-portfolio .jupiterx-social-share-inner',
				'property' => 'justify-content',
			],
		],
	] );

	// Name.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_portfolio_single_social_share_name',
		'section'   => 'jupiterx_portfolio_single_social_share',
		'label'     => __( 'Name', 'jupiterx' ),
		'column'    => '3',
		'default'   => true,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_social_share_divider_2',
		'section'  => 'jupiterx_portfolio_single_social_share',
	] );

	// Link spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_social_share_link_spacing',
		'section'   => 'jupiterx_portfolio_single_social_share',
		'css_var'   => 'portfolio-single-social-share-link',
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
				'element' => '.single-portfolio .jupiterx-social-share-link',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_social_share_divider_3',
		'section'  => 'jupiterx_portfolio_single_social_share',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_social_share_spacing',
		'section'   => 'jupiterx_portfolio_single_social_share',
		'css_var'   => 'portfolio-single-social-share',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_top' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-social-share',
			],
		],
	] );
} );

add_action( 'jupiterx_portfolio_single_related_posts_pro_box_after_field', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_portfolio_single_related_posts_typography',
		'section'    => 'jupiterx_portfolio_single_related_posts',
		'responsive' => true,
		'css_var'    => 'portfolio-single-related-posts',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform' ],
		'output'     => [
			[
				'element' => '.single-portfolio .jupiterx-post-related .card-title',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_portfolio_single_related_posts_background_color',
		'section'   => 'jupiterx_portfolio_single_related_posts',
		'css_var'   => 'portfolio-single-related-posts-background-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-portfolio .jupiterx-post-related .card-body',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_portfolio_single_related_posts_border',
		'section'   => 'jupiterx_portfolio_single_related_posts',
		'css_var'   => 'portfolio-single-related-posts-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-post-related .card',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_related_posts_spacing',
		'section'   => 'jupiterx_portfolio_single_related_posts',
		'css_var'   => 'portfolio-single-related-posts',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-post-related .card-body',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_portfolio_single_related_posts_divider',
		'section'  => 'jupiterx_portfolio_single_related_posts',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_portfolio_single_related_posts_container_spacing',
		'section'   => 'jupiterx_portfolio_single_related_posts',
		'css_var'   => 'portfolio-single-related-posts-container',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-portfolio .jupiterx-post-related',
			],
		],
	] );
} );
