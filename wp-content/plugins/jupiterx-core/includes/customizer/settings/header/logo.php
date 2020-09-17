<?php
/**
 * Add Jupiter settings for Header > Styles tab > Logo to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_logo';

// Logo.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_header_logo',
	'section'     => $section,
	'column'      => '6',
	'label'       => __( 'Logo', 'jupiterx-core' ),
	'default'     => 'jupiterx_logo',
	'choices'     => [
		'jupiterx_logo'           => __( 'Primary', 'jupiterx-core' ),
		'jupiterx_logo_secondary' => __( 'Secondary', 'jupiterx-core' ),
	],
] );

// Width.
JupiterX_Customizer::add_responsive_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_logo_max_width',
	'css_var'     => 'header-logo-max-width',
	'section'     => $section,
	'label'       => __( 'Max Width', 'jupiterx-core' ),
	'column'      => '3-alt',
	'units'       => [ 'px', '%', 'vw' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-brand-img',
			'property' => 'max-width',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_logo_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_logo_spacing',
	'css_var'   => 'header-logo',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-navbar-brand',
		],
	],
] );
