<?php
/**
 * Customizer settings for Header.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	// Type.
	JupiterX_Customizer::update_field( 'jupiterx_header_type', [
		'choices' => [
			''        => __( 'Default', 'jupiterx' ),
			'_custom' => __( 'Custom', 'jupiterx' ),
		],
	] );

	// Overlap content.
	JupiterX_Customizer::update_field( 'jupiterx_header_overlap', [
		'active_callback' => [
			'relation' => 'OR',
			'terms'    => [
				[
					'terms' => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '',
						],
					],
				],
				[
					'terms' => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '_custom',
						],
					],
				],
			],
		],
	] );

	// Behavior.
	JupiterX_Customizer::update_field( 'jupiterx_header_behavior', [
		'active_callback' => [
			'relation' => 'OR',
			'terms'    => [
				[
					'terms' => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '',
						],
					],
				],
				[
					'terms' => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '_custom',
						],
					],
				],
			],
		],
	] );

	// Position.
	JupiterX_Customizer::update_field( 'jupiterx_header_position', [
		'active_callback' => [
			'relation' => 'OR',
			'terms'    => [
				[
					'relation' => 'AND',
					'terms'    => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '',
						],
						[
							'setting'  => 'jupiterx_header_behavior',
							'operator' => '===',
							'value'    => 'fixed',
						],
					],
				],
				[
					'relation' => 'AND',
					'terms'    => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '_custom',
						],
						[
							'setting'  => 'jupiterx_header_behavior',
							'operator' => '===',
							'value'    => 'fixed',
						],
					],
				],
			],
		],
	] );

	// Offset.
	JupiterX_Customizer::update_field( 'jupiterx_header_offset', [
		'active_callback' => [
			'relation' => 'OR',
			'terms'    => [
				[
					'relation' => 'AND',
					'terms'    => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '',
						],
						[
							'setting'  => 'jupiterx_header_behavior',
							'operator' => '===',
							'value'    => 'sticky',
						],
					],
				],
				[
					'relation' => 'AND',
					'terms'    => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '_custom',
						],
						[
							'setting'  => 'jupiterx_header_behavior',
							'operator' => '===',
							'value'    => 'sticky',
						],
					],
				],
			],
		],
	] );

	// Behavior tablet.
	JupiterX_Customizer::update_field( 'jupiterx_header_behavior_tablet', [
		'active_callback' => [
			'relation' => 'OR',
			'terms'    => [
				[
					'relation' => 'AND',
					'terms'    => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '',
						],
						[
							'setting'  => 'jupiterx_header_behavior',
							'operator' => '!==',
							'value'    => 'static',
						],
					],
				],
				[
					'relation' => 'AND',
					'terms'    => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '_custom',
						],
						[
							'setting'  => 'jupiterx_header_behavior',
							'operator' => '!==',
							'value'    => 'static',
						],
					],
				],
			],
		],
	] );

	// Behavior mobile.
	JupiterX_Customizer::update_field( 'jupiterx_header_behavior_mobile', [
		'active_callback' => [
			'relation' => 'OR',
			'terms'    => [
				[
					'relation' => 'AND',
					'terms'    => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '',
						],
						[
							'setting'  => 'jupiterx_header_behavior',
							'operator' => '!==',
							'value'    => 'static',
						],
					],
				],
				[
					'relation' => 'AND',
					'terms'    => [
						[
							'setting'  => 'jupiterx_header_type',
							'operator' => '===',
							'value'    => '_custom',
						],
						[
							'setting'  => 'jupiterx_header_behavior',
							'operator' => '!==',
							'value'    => 'static',
						],
					],
				],
			],
		],
	] );

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_header_custom_pro_box' );
} );

add_action( 'jupiterx_header_type_after_field', function() {
	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_header_template',
		'section'         => 'jupiterx_header_settings',
		'label'           => __( 'My Templates', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => 'header',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_header_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );

add_action( 'jupiterx_header_overlap_after_field', function() {
	// Divider.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-divider',
		'settings'        => 'jupiterx_header_settings_divider',
		'column'          => '12 jupiterx-divider-control-empty',
		'section'         => 'jupiterx_header_settings',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_header_type',
				'operator' => '===',
				'value'    => '_custom',
			],
		],
	] );
} );

add_action( 'jupiterx_header_behavior_mobile_after_field', function() {
	// Template.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-template',
		'settings'        => 'jupiterx_header_sticky_template',
		'section'         => 'jupiterx_header_settings',
		'label'           => __( 'My Templates', 'jupiterx' ),
		'placeholder'     => __( 'Select one', 'jupiterx' ),
		'template_type'   => 'header',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_header_type',
				'operator' => '===',
				'value'    => '_custom',
			],
			[
				'setting'  => 'jupiterx_header_behavior',
				'operator' => '===',
				'value'    => 'sticky',
			],
		],
	] );
} );
