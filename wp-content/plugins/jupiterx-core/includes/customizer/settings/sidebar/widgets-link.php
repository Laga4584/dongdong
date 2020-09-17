<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Link tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar_widgets_link';

// Normal Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_sidebar_widgets_link_color',
	'section'   => $section,
	'css_var'   => 'sidebar-widgets-link-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link), .jupiterx-sidebar .jupiterx-widget .jupiterx-recent-comment .comment-author-link:before',
			'property' => 'color',
		],
	],
] );

// Normal Text Decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_sidebar_widgets_link_text_decoration',
	'section'     => $section,
	'css_var'     => 'sidebar-widgets-link-text-decoration',
	'column'      => '6',
	'icon'        => 'text-decoration',
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link), .jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link) span',
			'property' => 'text-decoration',
		],
	],
] );

// Hover Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'settings'   => 'jupiterx_sidebar_widgets_link_label_hover',
	'section'    => $section,
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
] );

// Hover Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_sidebar_widgets_link_color_hover',
	'section'   => $section,
	'css_var'   => 'sidebar-widgets-link-color-hover',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link):hover, .jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link):hover span, .jupiterx-sidebar .jupiterx-widget .jupiterx-recent-comment:hover .comment-author-link:before',
			'property' => 'color',
		],
	],
] );

// Hover Text Decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_sidebar_widgets_link_text_decoration_hover',
	'section'     => $section,
	'css_var'     => 'sidebar-widgets-link-text-decoration-hover',
	'column'      => '6',
	'icon'        => 'text-decoration',
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link):hover',
			'property' => 'text-decoration',
		],
	],
] );
