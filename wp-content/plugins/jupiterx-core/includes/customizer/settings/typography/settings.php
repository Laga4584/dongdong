<?php
/**
 * Add Jupiter settings for Fonts & Typography > Fonts tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography_settings';

// Theme fonts selector.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-fonts',
	'settings'    => 'jupiterx_typography_fonts',
	'section'     => $section,
	'description' => __( 'Please click "Publish" and then refresh the page to make selected fonts available in all the typography settings.', 'jupiterx-core' ),
	'default'     => [
		[
			'name' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"',
			'type' => 'system',
		],
	],
	'transport'   => 'postMessage',
	'api_source'  => [
		'adobe' => jupiterx_get_option( 'adobe_fonts_project_id', '' ),
	],
] );
