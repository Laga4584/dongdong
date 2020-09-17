<?php
/**
 * Add Jupiter settings for Footer > Styles > Widgets Link popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer_widgets_link';

// Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_footer_widgets_link_color',
	'section'   => $section,
	'css_var'   => 'footer-widgets-link-color',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets a, .jupiterx-footer-widgets .jupiterx-recent-comment .comment-author-link:before',
			'property' => 'color',
		],
	],
] );

// Text decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_footer_widgets_link_text_decoration',
	'section'     => $section,
	'css_var'     => 'footer-widgets-link-text-decoration',
	'column'      => '6',
	'icon'        => 'text-decoration',
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element' => '.jupiterx-footer-widgets a',
			'property' => 'text-decoration',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'settings'   => 'jupiterx_footer_widgets_link_label_hover',
	'section'    => $section,
	'label'      => __( 'Hover', 'jupiterx-core' ),
	'label_type' => 'fancy',
	'color'      => 'orange',
] );

// Hover color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_footer_widgets_link_color_hover',
	'section'   => $section,
	'css_var'   => 'footer-widgets-link-color-hover',
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets a:hover, .jupiterx-footer-widgets .jupiterx-recent-comment:hover .comment-author-link:before',
			'property' => 'color',
		],
	],
] );

// Hover text decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_footer_widgets_link_text_decoration_hover',
	'section'     => $section,
	'css_var'     => 'footer-widgets-link-text-decoration-hover',
	'column'      => '6',
	'icon'        => 'text-decoration',
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-footer-widgets a:hover',
			'property' => 'text-decoration',
		],
	],
] );
