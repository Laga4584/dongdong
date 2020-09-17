<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Avatar tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.4
 */

$section = 'jupiterx_post_single_avatar';

// Width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_avatar_width',
	'section'     => $section,
	'css_var'     => 'post-single-avatar-width',
	'label'       => __( 'Width', 'jupiterx-core' ),
	'column'      => '4',
	'input_attrs' => [ 'placeholder' => '50' ],
	'units'       => [ 'px' ],
	'output'      => [
		[
			'element'       => '.jupiterx-post-template-2 .jupiterx-post-meta-author-avatar img',
			'property'      => 'width',
		],
	],
] );

// Image Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_post_single_avatar_border',
	'section'   => $section,
	'css_var'   => 'post-single-avatar-border',
	'exclude'   => [ 'style', 'size' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'       => '.jupiterx-post-template-2 .jupiterx-post-meta-author-avatar img',
			'property'      => 'border',
		],
	],
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_avatar_spacing',
	'section'   => $section,
	'css_var'   => 'post-single-avatar',
	'exclude'   => [ 'padding' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-post-template-2 .jupiterx-post-meta-author-avatar img',
		],
	],
] );
