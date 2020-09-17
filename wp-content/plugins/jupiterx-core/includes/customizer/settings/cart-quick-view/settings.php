<?php
/**
 * Add Jupiter settings for Product page > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section_settings = 'jupiterx_cart_quick_view_settings';

// Cart quick view.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-toggle',
	'settings'   => 'jupiterx_header_shopping_cart',
	'section'    => $section_settings,
	'label'      => __( 'Cart Quick View', 'jupiterx-core' ),
	'column'     => '6',
] );

// Align.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'settings'   => 'jupiterx_header_shopping_cart_position',
	'section'    => $section_settings,
	'label'      => __( 'Position', 'jupiterx-core' ),
	'column'     => '6',
	'default'    => jupiterx_get_direction( 'left' ),
	'choices'    => [
		'left'  => [
			'icon'  => jupiterx_get_direction( 'alignment-left' ),
		],
		'right' => [
			'icon'  => jupiterx_get_direction( 'alignment-right' ),
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_header_shopping_cart',
			'operator' => '==',
			'value'    => true,
		],
	],
] );

