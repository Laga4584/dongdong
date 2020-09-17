<?php
/**
 * Add Jupiter settings for Elements Styles > Styles > Paragraph pop-up to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_element_paragraph';

// Paragraph child popup.
JupiterX_Customizer::add_section( $section, [
	'popup' => 'jupiterx_elements',
	'type'  => 'pane',
	'pane'  => [
		'type' => 'popup',
		'id'   => 'paragraph',
	],
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_element_paragraph_typography',
	'section'    => $section,
	'css_var'    => 'paragraph',
	'transport'  => 'postMessage',
	'responsive' => true,
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => 'p',
		],
	],
] );
