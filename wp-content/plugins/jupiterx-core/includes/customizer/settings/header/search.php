<?php
/**
 * Add Jupiter settings for Header > Styles tab > Search to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_search';

// Width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_search_width',
	'css_var'     => 'header-search-width',
	'section'     => $section,
	'column'      => '4',
	'icon'        => 'border-size',
	'units'       => [ 'px', '%', 'em', 'rem' ],
	'transport' => 'postMessage',
	'default'   => [
		'size' => 150,
		'unit' => 'px',
	],
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .form-control',
			'property' => 'width',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_header_search_border',
	'css_var'   => 'header-search-border',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'default'   => [
		'radius' => [
			'size' => 4,
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .form-control',
		],
	],
] );

// Background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_search_background_color',
	'css_var'   => 'header-search-background-color',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'background-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .form-control',
			'property' => 'background-color',
		],
	],
] );

// Text color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_search_text_color',
	'css_var'   => 'header-search-text-color',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'font-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .form-control, .jupiterx-site-navbar .jupiterx-search-form .form-control::placeholder',
			'property' => 'color',
		],
	],
] );

// Icon color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_search_icon_color',
	'css_var'   => 'header-search-icon-color',
	'section'   => $section,
	'column'    => '3',
	'icon'      => 'icon-color',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .btn',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_search_divider',
	'section'  => $section,
] );

// Form spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_search_spacing',
	'css_var'   => 'header-search',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-search-form',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'header_search_divider_2',
	'section'  => $section,
] );

// Field spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_search_field_spacing',
	'css_var'   => 'header-search-field',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-search-form .form-control',
		],
	],
] );
