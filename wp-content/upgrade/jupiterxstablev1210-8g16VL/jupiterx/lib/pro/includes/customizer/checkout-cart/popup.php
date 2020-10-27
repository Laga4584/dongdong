<?php
/**
 * Modify Jupiter X Customizer settings for Shop > Checkout & Cart.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_checkout_cart_styles_pro_box_after_field', function() {

	$popups = [
		'steps'       => __( 'Steps', 'jupiterx' ),
		'boxes'       => __( 'Boxes', 'jupiterx' ),
		'heading'     => __( 'Heading', 'jupiterx' ),
		'field_label' => __( 'Field Label', 'jupiterx' ),
		'field'       => __( 'Field', 'jupiterx' ),
		'button'      => __( 'Button', 'jupiterx' ),
		'back_button' => __( 'Back Button', 'jupiterx' ),
		'body_text'   => __( 'Body Text', 'jupiterx' ),
		'remove_icon' => __( 'Remove Icon', 'jupiterx' ),
		'thumbnail'   => __( 'Thumbnail', 'jupiterx' ),
		'table'       => __( 'Table', 'jupiterx' ),
	];

	// Elements popup.
	JupiterX_Customizer::update_section( 'jupiterx_checkout_cart', [
		'pro'    => false,
		'popups' => $popups,
	] );

	// Create popup children.
	foreach ( $popups as $popup_id => $label ) {
		JupiterX_Customizer::add_section( 'jupiterx_checkout_cart_' . $popup_id, [
			'popup' => 'jupiterx_checkout_cart',
			'type'  => 'pane',
			'pane'  => [
				'type' => 'popup',
				'id'   => $popup_id,
			],
		] );
	}

	// Styles tab > Child popups.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-child-popup',
		'settings' => 'jupiterx_checkout_cart_styles_popups',
		'section'  => 'jupiterx_checkout_cart_styles',
		'target'   => 'jupiterx_checkout_cart',
		'choices'  => $popups,
	] );
} );

add_action( 'jupiterx_after_customizer_register', function() {

	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_checkout_cart_styles_pro_box' );
} );
