<?php
/**
 * Add Jupiter settings for Header > Styles tab > Container to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_sticky_container';

// Background color.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-color',
	'settings'   => 'jupiterx_header_sticky_container_background_color',
	'css_var'    => 'header-sticky-container-background-color',
	'section'    => $section,
	'column'     => '3',
	'icon'       => 'background-color',
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element'  => '.jupiterx-header-sticked .jupiterx-site-navbar',
			'property' => 'background-color',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-border',
	'settings'   => 'jupiterx_header_sticky_container_border',
	'css_var'    => 'header-sticky-container-border',
	'section'    => $section,
	'exclude'    => [ 'style', 'size', 'radius' ],
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element'  => '.jupiterx-header-sticked .jupiterx-site-navbar',
			'property' => 'border-bottom',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_sticky_container_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_header_sticky_container_spacing',
	'css_var'    => 'header-sticky-container',
	'section'    => $section,
	'exclude'    => [ 'margin' ],
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-header-sticked .jupiterx-site-navbar',
		],
	],
] );
