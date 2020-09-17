<?php
/**
 * Add Jupiter settings for Layout > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar_settings';

// Layout.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-select',
	'settings'      => 'jupiterx_sidebar_layout',
	'section'       => $section,
	'label'         => __( 'Layout', 'jupiterx-core' ),
	'default'       => 'c_sp',
	'choices'       => JupiterX_Customizer_Utils::get_layouts(),
	'control_attrs' => [
		'style' => 'max-width: 48%',
	],
] );

// Sidebar Primary.
JupiterX_Customizer::add_field( [
	'type'         => 'jupiterx-select',
	'settings'     => 'jupiterx_sidebar_primary',
	'section'      => $section,
	'label'        => __( 'Primary Sidebar', 'jupiterx-core' ),
	'column'       => 6,
	'default'      => 'sidebar_primary',
	'load_choices' => 'widgets_area',
] );

// Sidebar Secondary.
JupiterX_Customizer::add_field( [
	'type'         => 'jupiterx-select',
	'settings'     => 'jupiterx_sidebar_secondary',
	'section'      => $section,
	'label'        => __( 'Secondary Sidebar', 'jupiterx-core' ),
	'column'       => 6,
	'default'      => 'sidebar_secondary',
	'load_choices' => 'widgets_area',
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-divider',
	'settings'      => 'jupiterx_sidebar_divider',
	'section'       => $section,
	'control_attrs' => [
		'style' => 'margin-top: 10px',
	],
] );

// Exceptions.
$jupiterx_post_types = [
	'post'      => __( 'Blog', 'jupiterx-core' ),
	'page'      => __( 'Page', 'jupiterx-core' ),
	'portfolio' => __( 'Portfolio', 'jupiterx-core' ),
];

if ( function_exists( 'jupiterx_get_post_types' ) ) {
	$jupiterx_post_types = array_merge( $jupiterx_post_types, jupiterx_get_post_types( 'labels' ) );
}

$archive_post_types = [
	'archive' => __( 'Archive', 'jupiterx-core' ),
	'search'  => __( 'Search', 'jupiterx-core' ),
	'product' => __( 'Shop', 'jupiterx-core' ),
];

if ( function_exists( 'jupiterx_get_post_types_archives' ) ) {
	$archive_post_types = array_merge( $archive_post_types, jupiterx_get_post_types_archives() );
}

$choices = array_merge( $jupiterx_post_types, $archive_post_types );

$fields = [];

foreach ( $choices as $jupiterx_post_type => $label ) {
	$field = [
		'label'   => $label,
		'options' => [
			'layout'        => [
				'type'         => 'jupiterx-select',
				'label'        => __( 'Layout', 'jupiterx-core' ),
				'default'      => 'c_sp',
				'choices'      => JupiterX_Customizer_Utils::get_layouts(),
				'controlAttrs' => [
					'style' => 'max-width: 48%',
				],
			],
			'primary'       => [
				'type'         => 'jupiterx-select',
				'label'        => __( 'Primary Sidebar', 'jupiterx-core' ),
				'column'       => 6,
				'default'      => 'sidebar_primary',
				'load_choices' => 'widgets_area',
			],
			'secondary'     => [
				'type'         => 'jupiterx-select',
				'label'        => __( 'Secondary Sidebar', 'jupiterx-core' ),
				'column'       => 6,
				'default'      => 'sidebar_secondary',
				'load_choices' => 'widgets_area',
			],
		],
	];

	$fields[ $jupiterx_post_type ] = $field;
}

JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-exceptions',
	'settings' => 'jupiterx_sidebar_exceptions',
	'section'  => $section,
	'label'    => __( 'Conditions', 'jupiterx-core' ),
	'default'  => [],
	'fields'   => $fields,
	'transport' => '',
] );
