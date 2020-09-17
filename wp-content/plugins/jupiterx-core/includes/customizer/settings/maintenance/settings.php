<?php
/**
 * Add Jupiter settings for Pages > Maintenance > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_maintenance';

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_maintenance_warning',
	'section'         => $section,
	'label'           => __( 'Maintenance Mode returns HTTP 503 code, so search engines know to come back a short time later. It is not recommended to use this mode for more than a couple of days.', 'jupiterx-core' ),
	'jupiterx_url'    => '',
] );

// Fields description.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'settings'   => 'jupiterx_maintenance_label',
	'section'    => $section,
	'label'      => __( 'Maintenance page will be displayed to guests only.', 'jupiterx-core' ),
	'label_type' => 'description',
] );

// Enable maintenance.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-toggle',
	'settings'    => 'jupiterx_maintenance',
	'section'     => $section,
	'label'       => __( 'Maintenance', 'jupiterx-core' ),
	'column'      => 6,
	'default'     => false,
] );

// Template.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_maintenance_template',
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
