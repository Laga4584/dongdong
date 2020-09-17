<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Action Link tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment_action_link';

// Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_action_link_color',
	'section'   => $section,
	'css_var'   => 'comment-action-link-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-links a, .jupiterx-comments .logged-in-as a, .comment-respond a',
			'property' => 'color',
		],
	],
] );

// Text decoration.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-select',
	'settings'  => 'jupiterx_comment_action_link_text_decoration',
	'section'   => $section,
	'css_var'   => 'comment-action-link-decoration',
	'column'    => '6',
	'icon'      => 'text-decoration',
	'default'   => 'none',
	'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-links a, .jupiterx-comments .logged-in-as a, .comment-respond a',
			'property' => 'text-decoration',
		],
	],
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'settings'   => 'jupiterx_comment_action_link_label_hover',
	'section'    => $section,
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
] );

// Hover color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_action_link_color_hover',
	'section'   => $section,
	'css_var'   => 'comment-action-link-hover-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-links a:hover, .jupiterx-comments .logged-in-as a:hover, .comment-respond a:hover',
			'property' => 'color',
		],
	],
] );

// Hover text decoration.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-select',
	'settings'  => 'jupiterx_comment_action_link_text_decoration_hover',
	'section'   => $section,
	'css_var'   => 'comment-action-link-hover-decoration',
	'column'    => '6',
	'icon'      => 'text-decoration',
	'default'   => 'underline',
	'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-links a:hover, .jupiterx-comments .logged-in-as a:hover, .comment-respond a:hover',
			'property' => 'text-decoration',
		],
	],
] );

