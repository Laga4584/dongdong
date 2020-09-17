<?php
/**
 * Add Jupiter settings for Header > Styles tab > Sticky Logo to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header_sticky_logo';

// Width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_sticky_logo_max_width',
	'css_var'     => 'header-sticky-logo-max-width',
	'section'     => $section,
	'label'       => __( 'Max Width', 'jupiterx-core' ),
	'column'      => '3-alt',
	'units'       => [ 'px', '%', 'vw' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-brand-img-sticky',
			'property' => 'max-width',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_sticky_logo_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_sticky_logo_spacing',
	'css_var'   => 'header-sticky-logo',
	'section'   => $section,
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-header-sticked .jupiterx-site-navbar .jupiterx-navbar-brand',
		],
	],
] );
