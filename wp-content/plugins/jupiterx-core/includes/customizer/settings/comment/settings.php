<?php
/**
 * Add Jupiter settings for Element > Comment > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment_settings';

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_comment_label_1',
	'section'  => $section,
	'label'    => __( 'Display Contents', 'jupiterx-core' ),
] );

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_comment_elements',
	'section'  => $section,
	'css_var'  => 'comment-elements',
	'default'  => [
		'avatar',
		'date',
		'role',
	],
	'choices'  => [
		'avatar' => __( 'Avatar', 'jupiterx-core' ),
		'role'   => __( 'Role', 'jupiterx-core' ),
		'date'   => __( 'Date', 'jupiterx-core' ),
	],
] );

