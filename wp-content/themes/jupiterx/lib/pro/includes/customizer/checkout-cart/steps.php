<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Steps.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	$section = 'jupiterx_checkout_cart_steps';

	// Step 1.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-text',
		'settings' => 'jupiterx_checkout_cart_steps_1',
		'text'    => __( 'Step 1', 'jupiterx' ),
		'section'  => $section,
		'default'  => __( 'Cart', 'jupiterx' ),
	] );

	// Step 2.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-text',
		'settings' => 'jupiterx_checkout_cart_steps_2',
		'text'    => __( 'Step 2', 'jupiterx' ),
		'section'  => $section,
		'default'  => __( 'Delivery & Payment', 'jupiterx' ),
	] );

	// Step 3.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-text',
		'settings' => 'jupiterx_checkout_cart_steps_3',
		'text'    => __( 'Step 3', 'jupiterx' ),
		'section'  => $section,
		'default'  => __( 'Complete Order', 'jupiterx' ),
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_1',
		'section'  => $section,
	] );

	// Step.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Step', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_step',
		'section'  => $section,
	] );

	// Step style.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_checkout_cart_steps_step_style',
		'section'   => $section,
		'text'      => __( 'Style', 'jupiterx' ),
		'default'   => 'number',
		'column'    => '6',
		'choices'   => [
			'default' => __( 'Default', 'jupiterx' ),
			'number'  => __( 'Number', 'jupiterx' ),
			'icon'    => __( 'Icon', 'jupiterx' ),
		],
	] );

	// Step background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_step_bg_color',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-step-bg-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step',
				'property' => 'background-color',
			],
		],
	] );

	// Step border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_steps_step_border',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-step-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.jupiterx-wc-step',
			],
			[
				'element'       => '.jupiterx-wc-steps-inner:after',
				'property'      => 'height',
				'value_pattern' => '$',
				'choice'        => 'width',
			],
		],
	] );

	// Step spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_steps_spacing',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'padding_right' => 1.5,
				'padding_left'  => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.jupiterx-wc-step',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_2',
		'section'  => $section,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '!=',
				'value'    => 'default',
			],
		],
	] );

	// Number.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Number', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label',
		'section'  => $section,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'number',
			],
		],
	] );

	// Number typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_checkout_cart_steps_number_typography',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-number',
		'transport' => 'postMessage',
		'exclude'   => [ 'letter_spacing', 'line_height', 'text_transform' ],
		'default'   => [
			'desktop' => [
				'color' => '#fff',
			],
		],
		'output'    => [
			[
				'element' => '.jupiterx-wc-step-number',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'number',
			],
		],
	] );

	// Number background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_number_background_color',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-number-bg-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'default'   => '#adb5bd',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-number',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'number',
			],
		],
	] );

	// Icon.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Icon', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_11',
		'section'  => $section,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'icon',
			],
		],
	] );

	// Icon size.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-input',
		'settings'  => 'jupiterx_checkout_cart_steps_icon_size',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-icon-size',
		'icon'      => 'font-size',
		'units'     => [ 'px', 'em', 'rem' ],
		'column'    => '4',
		'default'     => [
			'size' => 1.5,
			'unit' => 'rem',
		],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-icon',
				'property' => 'font-size',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'icon',
			],
		],
	] );

	// Icon color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_icon_color',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-icon-color',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'default'   => '#adb5bd',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-icon',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'icon',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_3',
		'section'  => $section,
	] );

	// Title.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Title', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_2',
		'section'  => $section,
	] );

	// Title typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_checkout_cart_steps_title_typography',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-title',
		'transport' => 'postMessage',
		'exclude'   => [ 'line_height' ],
		'default'   => [
			'desktop' => [
				'color'     => '#adb5bd',
				'font_size' => [
					'size' => 1.25,
					'unit' => 'rem',
				],
			],
		],
		'output'    => [
			[
				'element' => '.jupiterx-wc-step-title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_4',
		'section'  => $section,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_steps_step_divider',
		'label'    => __( 'Divider', 'jupiterx' ),
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-step-divider',
		'transport' => 'postMessage',
		'exclude'   => [ 'radius' ],
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-divider',
				'property' => 'border',
			],
			[
				'element'  => '.jupiterx-wc-step-divider',
				'property' => 'height',
				'choice'   => 'size',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_5',
		'section'  => $section,
	] );

	// Container.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Container', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_container',
		'section'  => $section,
	] );

	// Container background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_container_bg_color',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-container-bg-color',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-steps',
				'property' => 'background-color',
			],
		],
	] );

	// Container border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_steps_container_border',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-container-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.jupiterx-wc-steps',
			],
		],
	] );

	// Container spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_steps_container_spacing',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-container',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'padding_top' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.jupiterx-wc-steps',
			],
		],
	] );

	// Active label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Active', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'green',
		'settings'   => 'jupiterx_checkout_cart_steps_label_3',
		'section'    => $section,
	] );

	// Step.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Step', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_6',
		'section'  => $section,
	] );

	// Step background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_box_bg_color_active',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-step-bg-color-active',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step.jupiterx-wc-step-active',
				'property' => 'background-color',
			],
		],
	] );

	// Step border width.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-input',
		'settings' => 'jupiterx_checkout_cart_steps_box_border_width_active',
		'section'  => $section,
		'css_var'  => 'checkout-cart-steps-step-border-width-active',
		'icon'     => 'border',
		'units'    => [ 'px' ],
		'column'   => '4',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step.jupiterx-wc-step-active',
				'property' => 'border-width',
			],
		],
	] );

	// Step border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_step_border_color_active',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-step-border-color-active',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step.jupiterx-wc-step-active',
				'property' => 'border-color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_6',
		'section'  => $section,
	] );

	// Number.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Number', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_4',
		'section'  => $section,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'number',
			],
		],
	] );

	// Number color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_number_color_active',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-number-color-active',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',

		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-active .jupiterx-wc-step-number',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'number',
			],
		],
	] );

	// Number background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_number_background_color_active',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-number-bg-color-active',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'default'   => '#007bff',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-active .jupiterx-wc-step-number',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'number',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_7',
		'section'  => $section,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'number',
			],
		],
	] );

	// Icon.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Icon', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_8',
		'section'  => $section,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'icon',
			],
		],
	] );

	// Icon color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_icon_color_active',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-icon-color-active',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'default'   => '#212529',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-active .jupiterx-wc-step-icon',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'icon',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_steps_divider_8',
		'section'  => $section,
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_steps_step_style',
				'operator' => '==',
				'value'    => 'icon',
			],
		],
	] );

	// Title.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Title', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_steps_label_5',
		'section'  => $section,
	] );

	// Title color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_steps_title_color_active',
		'section'   => $section,
		'css_var'   => 'checkout-cart-steps-title-color-active',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'default'   => '#212529',
		'output'    => [
			[
				'element'  => '.jupiterx-wc-step-active .jupiterx-wc-step-title',
				'property' => 'color',
			],
		],
	] );
} );
