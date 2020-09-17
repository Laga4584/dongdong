<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Comment Text tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment_comment_text';

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_comment_comment_text_typography',
	'section'    => $section,
	'responsive' => true,
	'css_var'    => 'comment-comment-text',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform' ],
	'output'     => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-body-wrapper',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_comment_comment_text_divider',
	'section'  => $section,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_comment_text_spacing',
	'section'   => $section,
	'css_var'   => 'comment-comment-text-box-model',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-body-wrapper',
		],
	],
] );

