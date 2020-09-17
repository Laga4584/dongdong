<?php
/**
 * Add Jupiter settings for Pages > 404 > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_404';

// Template.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_404_template',
	'section'     => $section,
	'label'       => __( 'Template', 'jupiterx-core' ),
	'column'      => 6,
	'default'     => '',
	'placeholder' => __( 'None', 'jupiterx-core' ),
	'transport'   => 'postMessage',
	'preview'     => true,
	'jupiterx'    => [
		'select2' => [
			'action'    => 'jupiterx_core_customizer_get_select2_options',
			'post_type' => 'page',
		],
	],
] );
