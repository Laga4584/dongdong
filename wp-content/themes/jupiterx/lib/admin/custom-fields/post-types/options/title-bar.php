<?php
/**
 * Add Jupiter Post Options > Title Bar meta options.
 *
 * @package JupiterX\Framework\Admin\Custom_Fields
 *
 * @since   1.0.0
 */

$key    = 'field_jupiterx_post_title_bar';
$parent = 'group_jupiterx_post';

// Title bar.
acf_add_local_field( [
	'key'    => "{$key}_tab",
	'parent' => $parent,
	'label'  => __( 'Title Bar', 'jupiterx' ),
	'type'   => 'tab',
] );

// Title.
acf_add_local_field( [
	'key'           => "{$key}_title",
	'parent'        => $parent,
	'label'         => __( 'Title', 'jupiterx' ),
	'name'          => 'jupiterx_title_bar_title',
	'type'          => 'button_group',
	'wrapper'       => [ 'width' => '50' ],
	'choices'       => [
		'global' => __( 'Global', 'jupiterx' ),
		'1'      => __( 'Yes', 'jupiterx' ),
		''       => __( 'No', 'jupiterx' ),
	],
	'default_value' => 'global',
] );

// Breadcrumb.
acf_add_local_field( [
	'key'           => "{$key}_breadcrumb",
	'parent'        => $parent,
	'label'         => __( 'Breadcrumb', 'jupiterx' ),
	'name'          => 'jupiterx_title_bar_breadcrumb',
	'type'          => 'button_group',
	'wrapper'       => [ 'width' => '50' ],
	'choices'       => [
		'global' => __( 'Global', 'jupiterx' ),
		'1'      => __( 'Yes', 'jupiterx' ),
		''       => __( 'No', 'jupiterx' ),
	],
	'default_value' => 'global',
] );

do_action( 'jupiterx_custom_field_post_types' );
