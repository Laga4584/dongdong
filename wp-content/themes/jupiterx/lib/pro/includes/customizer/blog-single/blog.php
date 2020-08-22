<?php
/**
 * Customizer settings for Blog.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_post_single_template_type_after_field', function() {
	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_post_single_template_custom',
		'section'         => 'jupiterx_post_single_settings',
		'label'           => __( 'My Templates', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => 'single',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_post_single_template_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );

add_action( 'jupiterx_after_customizer_register', function() {

	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_post_single_template_type', [
		'choices' => [
			''        => __( 'Default', 'jupiterx' ),
			'_custom' => __( 'Custom', 'jupiterx' ),
		],
	] );

	// Child popup styles.
	JupiterX_Customizer::update_field( 'jupiterx_post_single_styles_popups', [
		'choices' => [
			'featured_image' => __( 'Featured Image', 'jupiterx' ),
			'title'          => __( 'Title', 'jupiterx' ),
			'meta'           => __( 'Meta', 'jupiterx' ),
			'post_content'   => __( 'Post Content', 'jupiterx' ),
			'tags'           => __( 'Tags', 'jupiterx' ),
			'social_share'   => __( 'Social Share', 'jupiterx' ),
			'navigation'     => __( 'Navigation', 'jupiterx' ),
			'author_box'     => __( 'Author Box', 'jupiterx' ),
			'related_posts'  => __( 'Recommended Posts', 'jupiterx' ),
		],
	] );

	// Template.
	JupiterX_Customizer::update_field( 'jupiterx_post_single_template', [
		'choices' => [
			'1' => 'blog-single-01',
			'2' => 'blog-single-02',
			'3' => 'blog-single-03',
		],
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_custom_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_social_share_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_navigation_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_author_box_pro_box' );
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_related_posts_pro_box' );
} );

// Social Share.
add_action( 'jupiterx_post_single_social_share_pro_box_after_field', function() {
	// Social Network Filter.
	JupiterX_Customizer::add_field( [
		'type'          => 'jupiterx-multicheck',
		'settings'      => 'jupiterx_post_single_social_share_filter',
		'section'       => 'jupiterx_post_single_social_share',
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
		'settings' => 'jupiterx_post_single_social_share_divider',
		'section'  => 'jupiterx_post_single_social_share',
	] );

	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_post_single_social_share_align',
		'section'   => 'jupiterx_post_single_social_share',
		'css_var'   => 'post-single-social-share-align',
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
				'element'  => '.single-post .jupiterx-social-share-inner',
				'property' => 'justify-content',
			],
		],
	] );

	// Name.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_post_single_social_share_name',
		'section'   => 'jupiterx_post_single_social_share',
		'label'     => __( 'Name', 'jupiterx' ),
		'column'    => '3',
		'default'   => true,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_social_share_divider_2',
		'section'  => 'jupiterx_post_single_social_share',
	] );

	// Link spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_social_share_link_spacing',
		'section'   => 'jupiterx_post_single_social_share',
		'css_var'   => 'post-single-social-share-link',
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
				'element' => '.single-post .jupiterx-social-share-link',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_social_share_divider_3',
		'section'  => 'jupiterx_post_single_social_share',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_social_share_spacing',
		'section'   => 'jupiterx_post_single_social_share',
		'css_var'   => 'post-single-social-share',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_top' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-social-share',
			],
		],
	] );
} );

// Navigation.
add_action( 'jupiterx_post_single_navigation_pro_box_after_field', function() {
	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_post_single_navigation_label',
		'section'  => 'jupiterx_post_single_navigation',
		'label'    => __( 'Image', 'jupiterx' ),
	] );

	// Image.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-toggle',
		'settings' => 'jupiterx_post_single_navigation_image',
		'section'  => 'jupiterx_post_single_navigation',
		'column'   => '3',
		'default'  => true,
	] );

	// Image border radius.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-input',
		'settings'        => 'jupiterx_post_single_navigation_image_border_radius',
		'section'         => 'jupiterx_post_single_navigation',
		'css_var'         => 'post-single-navigation-image-border-radius',
		'column'          => '4',
		'icon'            => 'corner-radius',
		'units'           => [ 'px', '%' ],
		'transport'       => 'postMessage',
		'output'          => [
			[
				'element'  => '.single-post .jupiterx-post-navigation-link img',
				'property' => 'border-radius',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_post_single_navigation_image',
				'operator' => '==',
				'value'    => true,
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_navigation_divider',
		'section'  => 'jupiterx_post_single_navigation',
	] );

	// Title label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Title', 'jupiterx' ),
		'settings' => 'jupiterx_post_single_navigation_label_2',
		'section'  => 'jupiterx_post_single_navigation',
	] );

	// Title typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_navigation_title_typography',
		'section'   => 'jupiterx_post_single_navigation',
		'css_var'   => 'post-single-navigation-title',
		'transport' => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-navigation-title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_navigation_divider_2',
		'section'  => 'jupiterx_post_single_navigation',
	] );

	// Label label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Label', 'jupiterx' ),
		'settings' => 'jupiterx_post_single_navigation_label_3',
		'section'  => 'jupiterx_post_single_navigation',
	] );

	// Label typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_navigation_label_typography',
		'section'   => 'jupiterx_post_single_navigation',
		'css_var'   => 'post-single-navigation-label',
		'transport' => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-navigation-label',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_navigation_divider_3',
		'section'  => 'jupiterx_post_single_navigation',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_navigation_spacing',
		'section'   => 'jupiterx_post_single_navigation',
		'css_var'   => 'post-single-navigation',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_top' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-navigation',
			],
		],
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_post_single_navigation_pro_box' );
} );

// Author Box.
add_action( 'jupiterx_post_single_author_box_pro_box_after_field', function() {
	// Avatar label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Avatar', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label',
		'section'    => 'jupiterx_post_single_author_box',
	] );

	// Avatar border radius.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_post_single_author_box_avatar_border_radius',
		'section'     => 'jupiterx_post_single_author_box',
		'css_var'     => 'post-single-author-box-avatar-border-radius',
		'column'      => '4',
		'icon'        => 'corner-radius',
		'units'       => [ 'px', '%' ],
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'  => '.single-post .jupiterx-post-author-box-avatar img',
				'property' => 'border-radius',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider',
		'section'  => 'jupiterx_post_single_author_box',
	] );

	// Name label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Name', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label_2',
		'section'    => 'jupiterx_post_single_author_box',
	] );

	// Name typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_author_box_name_typography',
		'section'   => 'jupiterx_post_single_author_box',
		'css_var'   => 'post-single-author-box-name',
		'transport' => 'postMessage',
		'exclude'   => [ 'letter_spacing', 'text_transform', 'line_height' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-author-box-link',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider_2',
		'section'  => 'jupiterx_post_single_author_box',
	] );

	// Description label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Description', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label_3',
		'section'    => 'jupiterx_post_single_author_box',
	] );

	// Description typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_author_box_description_typography',
		'section'   => 'jupiterx_post_single_author_box',
		'css_var'   => 'post-single-author-box-description',
		'transport' => 'postMessage',
		'exclude'   => [ 'letter_spacing', 'text_transform' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-author-box-content p',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider_3',
		'section'  => 'jupiterx_post_single_author_box',
	] );

	// Icons label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Social Network Icons', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label_4',
		'section'    => 'jupiterx_post_single_author_box',
	] );

	// Icons size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_post_single_author_box_icons_size',
		'section'     => 'jupiterx_post_single_author_box',
		'css_var'     => 'post-single-author-box-icons-size',
		'column'      => '4',
		'icon'        => 'font-size',
		'units'       => [ 'px', 'em', 'rem' ],
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'  => '.single-post .jupiterx-post-author-icons a',
				'property' => 'font-size',
			],
		],
	] );

	// Icons gap.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-input',
		'settings'  => 'jupiterx_post_single_author_box_icons_gap',
		'section'   => 'jupiterx_post_single_author_box',
		'css_var'   => 'post-single-author-box-icons-gap',
		'column'    => '4',
		'icon'      => 'space-between',
		'units'     => [ 'px', 'em' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.single-post .jupiterx-post-author-icons li',
				'property'      => 'margin-right',
			],
		],
	] );

	// Icons color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_post_single_author_box_icons_color',
		'section'   => 'jupiterx_post_single_author_box',
		'css_var'   => 'post-single-author-box-icons-color',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-post .jupiterx-post-author-icons a',
				'property' => 'color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider_4',
		'section'  => 'jupiterx_post_single_author_box',
	] );

	// Container label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Container', 'jupiterx' ),
		'settings'   => 'jupiterx_post_single_author_box_label_5',
		'section'    => 'jupiterx_post_single_author_box',
	] );

	// Container align.
	JupiterX_Customizer::add_responsive_field( [
		'type'     => 'jupiterx-choose',
		'settings' => 'jupiterx_post_single_author_box_align',
		'section'  => 'jupiterx_post_single_author_box',
		'css_var'  => 'post-single-author-box-align',
		'column'   => '4',
		'default'  => [
			'desktop' => '',
			'tablet'  => 'center',
			'mobile'  => 'center',
		],
		'choices'  => JupiterX_Customizer_Utils::get_align(),
	] );

	// Container background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_post_single_author_box_background_color',
		'section'   => 'jupiterx_post_single_author_box',
		'css_var'   => 'post-single-author-box-background-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-post .jupiterx-post-author-box',
				'property' => 'background-color',
			],
		],
	] );

	// Container border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_post_single_author_box_border',
		'section'   => 'jupiterx_post_single_author_box',
		'css_var'   => 'post-single-author-box-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-author-box',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_author_box_divider_5',
		'section'  => 'jupiterx_post_single_author_box',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_author_box_spacing',
		'section'   => 'jupiterx_post_single_author_box',
		'css_var'   => 'post-single-author-box',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_top' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-author-box',
			],
		],
	] );
} );

// Related Posts.
add_action( 'jupiterx_post_single_related_posts_pro_box_after_field', function() {
	// Typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_post_single_related_posts_typography',
		'section'   => 'jupiterx_post_single_related_posts',
		'css_var'   => 'post-single-related-posts',
		'transport' => 'postMessage',
		'exclude'   => [ 'text_transform' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-related .card-title',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_post_single_related_posts_background_color',
		'section'   => 'jupiterx_post_single_related_posts',
		'css_var'   => 'post-single-related-posts-background-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-post .jupiterx-post-related .card-body',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_post_single_related_posts_border',
		'section'   => 'jupiterx_post_single_related_posts',
		'css_var'   => 'post-single-related-posts-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-related .card',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_related_posts_spacing',
		'section'   => 'jupiterx_post_single_related_posts',
		'css_var'   => 'post-single-related-posts',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-related .card-body',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_post_single_related_posts_divider',
		'section'  => 'jupiterx_post_single_related_posts',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_post_single_related_posts_container_spacing',
		'section'   => 'jupiterx_post_single_related_posts',
		'css_var'   => 'post-single-related-posts-container',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_top' => 3,
			],
		],
		'output'    => [
			[
				'element' => '.single-post .jupiterx-post-related',
			],
		],
	] );

} );
