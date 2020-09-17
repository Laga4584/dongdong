<?php
/**
 * Add Jupiter settings for Site Settings > Styles > Main popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_site_main';

// Background.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-background',
	'settings'   => 'jupiterx_site_main_background',
	'section'    => $section,
	'css_var'    => 'site-main-background',
	'transport'  => 'postMessage',
	'default'    => [
		'color' => '#fff',
	],
	'output'     => [
		[
			'element' => '.jupiterx-main',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_site_main_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_site_main_spacing',
	'section'   => $section,
	'css_var'   => 'site-main',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-main',
		],
	],
] );
