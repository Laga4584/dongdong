<?php
/**
 * Add Jupiter settings for Fonts & Typography > Typography > Links pop-up to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography_links';

// Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_typography_links_color',
	'section'   => $section,
	'css_var'   => 'link-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'default'   => '#007bff',
	'output'    => [
		[
			'element'  => 'a, .jupiterx-recent-comment .comment-author-link:before',
			'property' => 'color',
		],
	],
] );

// Text decoration.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-select',
	'settings'  => 'jupiterx_typography_links_text_decoration',
	'section'   => $section,
	'css_var'   => 'link-decoration',
	'column'    => '6',
	'icon'      => 'text-decoration',
	'default'   => 'none',
	'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => 'a',
			'property' => 'text-decoration',
		],
	],
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'settings'   => 'jupiterx_typography_links_label_hover',
	'section'    => $section,
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
] );

// Hover color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_typography_links_color_hover',
	'section'   => $section,
	'css_var'   => 'link-hover-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'default'   => '#0056b3',
	'output'    => [
		[
			'element'  => 'a:hover, .jupiterx-recent-comment:hover .comment-author-link:before',
			'property' => 'color',
		],
	],
] );

// Hover text decoration.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-select',
	'settings'  => 'jupiterx_typography_links_text_decoration_hover',
	'section'   => $section,
	'css_var'   => 'link-hover-decoration',
	'column'    => '6',
	'icon'      => 'text-decoration',
	'default'   => 'underline',
	'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => 'a:hover',
			'property' => 'text-decoration',
		],
	],
] );
