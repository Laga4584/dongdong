<?php
/**
 * Customizer settings for Product List.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_product_list_styles_pro_box_after_field', function() {
	$sortable_popups = [
		'category'      => __( 'Category', 'jupiterx' ),
		'name'          => __( 'Name', 'jupiterx' ),
		'rating'        => __( 'Rating', 'jupiterx' ),
		'regular_price' => __( 'Regular Price', 'jupiterx' ),
	];

	$static_popups = [
		'image'             => __( 'Image', 'jupiterx' ),
		'sale_price'        => __( 'Sale Price', 'jupiterx' ),
		'add_cart_button'   => __( 'Add to Cart Button', 'jupiterx' ),
		'sale_badge'        => __( 'Sale Badge', 'jupiterx' ),
		'outstock_badge'    => __( 'Out of Stock', 'jupiterx' ),
		'item_container'    => __( 'Item Container', 'jupiterx' ),
		'pagination'        => __( 'Pagination', 'jupiterx' ),
	];

	$popups = array_merge(
		$sortable_popups,
		$static_popups,
		[
			'quick_view_button' => __( 'Quick View Button', 'jupiterx' ),
			'loadmore_button'   => __( 'Load More Button', 'jupiterx' ),
		]
	);

	// Elements popup.
	JupiterX_Customizer::update_section( 'jupiterx_product_list', [
		'popups' => $popups,
		'tabs'   => [
			'settings' => __( 'Settings', 'jupiterx' ),
			'styles'   => __( 'Styles', 'jupiterx' ),
		],
	] );

	// Styles tab > Sortable child popups.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-child-popup',
		'settings' => 'jupiterx_product_list_sort_elements',
		'section'  => 'jupiterx_product_list_styles',
		'target'   => 'jupiterx_product_list',
		'sortable' => true,
		'choices'  => $sortable_popups,
	] );

	// Styles tab > Child popups.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-child-popup',
		'settings' => 'jupiterx_product_list_styles_popups',
		'section'  => 'jupiterx_product_list_styles',
		'target'   => 'jupiterx_product_list',
		'choices'  => $static_popups,
	] );

	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-child-popup',
		'settings' => 'jupiterx_product_list_styles_popups_quick_view_button',
		'section'  => 'jupiterx_product_list_styles',
		'target'   => 'jupiterx_product_list',
		'choices'  => [
			'quick_view_button' => __( 'Quick View Button', 'jupiterx' ),
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_list_quick_view',
				'operator' => '===',
				'value'    => true,
			],
			[
				'setting'  => 'jupiterx_product_list_quick_view_opener',
				'operator' => '==',
				'value'    => 2,
			],
		],
	] );

	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-child-popup',
		'settings' => 'jupiterx_product_list_styles_popups_loadmore_button',
		'section'  => 'jupiterx_product_list_styles',
		'target'   => 'jupiterx_product_list',
		'choices'  => [
			'loadmore_button' => __( 'Load More Button', 'jupiterx' ),
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_list_pagination',
				'operator' => '===',
				'value'    => 'loadmore',
			],
		],
	] );

	// Create popup children.
	foreach ( $popups as $popup_id => $label ) {
		JupiterX_Customizer::add_section( 'jupiterx_product_list_' . $popup_id, [
			'popup' => 'jupiterx_product_list',
			'type'  => 'pane',
			'pane'  => [
				'type' => 'popup',
				'id'   => $popup_id,
			],
		] );
	}
} );

add_action( 'jupiterx_after_customizer_register', function() {
	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_product_list_styles_pro_box' );
} );

// Image.
add_action( 'jupiterx_after_customizer_register', function() {
	// Image Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_image_border',
		'section'   => 'jupiterx_product_list_image',
		'css_var'   => 'product-list-image-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'default'   => [
			'width' => [
				'size' => '0',
				'unit' => 'px',
			],
			'radius' => [
				'size' => 4,
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product a .jupiterx-wc-loop-product-image',
				'property' => 'border',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_image_divider',
		'section'  => 'jupiterx_product_list_image',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_image_spacing',
		'section'   => 'jupiterx_product_list_image',
		'css_var'   => 'product-list-image',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a .jupiterx-wc-loop-product-image',
			],
		],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 1,
				'margin_left' => 'auto',
				'margin_right' => 'auto',
			],
		],
	] );
} );

// Name.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_name_typography',
		'section'    => 'jupiterx_product_list_name',
		'responsive' => true,
		'css_var'    => 'product-list-name',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .woocommerce-loop-product__title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_name_divider',
		'section'  => 'jupiterx_product_list_name',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_name_spacing',
		'section'   => 'jupiterx_product_list_name',
		'css_var'   => 'product-list-name',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .woocommerce-loop-product__title',
			],
		],
	] );
} );

// Regular Price.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_regular_price_typography',
		'section'    => 'jupiterx_product_list_regular_price',
		'responsive' => true,
		'css_var'    => 'product-list-regular-price',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .price',
			],
		],
	] );

	// Text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_product_list_regular_price_text_decoration',
		'section'   => 'jupiterx_product_list_regular_price',
		'css_var'   => 'product-list-regular-price-text-decoration',
		'column'    => '5',
		'icon'      => 'text-decoration',
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .price > span',
				'property' => 'text-decoration',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_regular_price_divider',
		'section'  => 'jupiterx_product_list_regular_price',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_regular_price_spacing',
		'section'   => 'jupiterx_product_list_regular_price',
		'css_var'   => 'product-list-regular-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .price',
			],
		],
	] );
} );

// Sale Price.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_sale_price_typography',
		'section'    => 'jupiterx_product_list_sale_price',
		'responsive' => true,
		'css_var'    => 'product-list-sale-price',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .price ins',
			],
		],
	] );

	// Text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_product_list_sale_price_text_decoration',
		'section'   => 'jupiterx_product_list_sale_price',
		'css_var'   => 'product-list-sale-price-text-decoration',
		'column'    => '5',
		'icon'      => 'text-decoration',
		'default'   => 'none',
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .price ins',
				'property' => 'text-decoration',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_sale_price_divider',
		'section'  => 'jupiterx_product_list_sale_price',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_sale_price_spacing',
		'section'   => 'jupiterx_product_list_sale_price',
		'css_var'   => 'product-list-sale-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .price ins',
			],
		],
	] );
} );

// Rating.
add_action( 'jupiterx_after_customizer_register', function() {
	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_list_rating_label_1',
		'section'  => 'jupiterx_product_list_rating',
		'label'    => __( 'Icon', 'jupiterx' ),
	] );

	// Size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_list_rating_icon_size',
		'section'     => 'jupiterx_product_list_rating',
		'css_var'     => 'product-list-rating-icon-size',
		'column'      => '4',
		'icon'        => 'font-size',
		'units'       => [ 'px', 'em', 'rem' ],
		'transport'   => 'postMessage',
		'output'   => [
			[
				'element'  => '.woocommerce ul.products li.product .star-rating',
				'property' => 'font-size',
			],
		],
	] );

	// Icon Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_rating_icon_color',
		'section'   => 'jupiterx_product_list_rating',
		'css_var'   => 'product-list-rating-icon-color',
		'column'    => '3',
		'icon'      => 'icon-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .star-rating:before',
				'property' => 'color',
			],
		],
	] );

	// Active label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Active', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'green',
		'settings' => 'jupiterx_product_list_rating_label_2',
		'section'  => 'jupiterx_product_list_rating',
	] );

	// Icon color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_rating_icon_color_active',
		'section'   => 'jupiterx_product_list_rating',
		'css_var'   => 'product-list-rating-icon-color-active',
		'column'    => '3',
		'icon'      => 'icon-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .star-rating span',
				'property' => 'color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_rating_divider_1',
		'section'  => 'jupiterx_product_list_rating',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_rating_spacing',
		'section'   => 'jupiterx_product_list_rating',
		'css_var'   => 'product-list-rating',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 0.4,
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .rating-wrapper',
			],
		],
	] );
} );

// Category.
add_action( 'jupiterx_after_customizer_register', function() {
	// Text typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_product_list_category_typography',
		'section'   => 'jupiterx_product_list_category',
		'css_var'   => 'product-list-category',
		'label'     => __( 'Text', 'jupiterx' ),
		'exclude'   => [ 'line_height', 'text_transform', 'letter_spacing' ],
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'color' => '#212526',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product span.posted_in',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_category_divider_2',
		'section'  => 'jupiterx_product_list_category',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'     => 'jupiterx-box-model',
		'settings' => 'jupiterx_product_list_category_spacing',
		'section'  => 'jupiterx_product_list_category',
		'css_var'  => 'product-list-category',
		'exclude'  => [ 'padding' ],
		'transport'  => 'postMessage',
		'output'   => [
			[
				'element' => '.woocommerce ul.products li.product span.posted_in',
			],
		],
	] );
} );

// Add to Cart Button.
add_action( 'jupiterx_after_customizer_register', function() {
	// Icon.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_list_add_cart_button_icon',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button-icon',
		'label'     => __( 'Icon', 'jupiterx' ),
		'column'    => '3',
		'default'   => true,
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.woocommerce ul.products li.product a.button:before',
				'property'      => 'display',
				'exclude'       => [ true ],
				'value_pattern' => 'none',
			],
			[
				'element'       => '.woocommerce ul.products li.product a.button:before',
				'property'      => 'display',
				'exclude'       => [ false ],
				'value_pattern' => 'inline',
			],
		],
	] );

	// Full width.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_list_add_cart_button_full_width',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button-full-width',
		'label'     => __( 'Full Width', 'jupiterx' ),
		'column'    => '3',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.woocommerce ul.products li.product a.button',
				'property'      => 'width',
				'exclude'       => [ false ],
				'value_pattern' => '100',
				'units'         => '%',
			],
			[
				'element'       => '.woocommerce ul.products li.product a.button',
				'property'      => 'width',
				'exclude'       => [ true ],
				'value_pattern' => 'auto',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_add_cart_button_typography',
		'section'    => 'jupiterx_product_list_add_cart_button',
		'responsive' => true,
		'css_var'    => 'product-list-add-cart-button',
		'exclude'    => [ 'line_height' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-color',
		'settings' => 'jupiterx_product_list_add_cart_button_background_color',
		'section'  => 'jupiterx_product_list_add_cart_button',
		'css_var'  => 'product-list-add-cart-button-background-color',
		'icon'     => 'background-color',
		'transport' => 'postMessage',
		'output'   => [
			[
				'element'  => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_add_cart_button_border',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
			],
		],
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_add_cart_button_shadow',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
				'units'   => 'px',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings' => 'jupiterx_product_list_add_cart_button_label_1',
		'section'  => 'jupiterx_product_list_add_cart_button',
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_add_cart_button_text_color_hover',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button-text-color-hover',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn):hover',
				'property' => 'color',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_add_cart_button_background_color_hover',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button-background-color-hover',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn):hover',
				'property' => 'background-color',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_add_cart_button_border_color_hover',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button-border-color-hover',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn):hover',
				'property' => 'border-color',
			],
		],
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_add_cart_button_shadow_hover',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn):hover',
				'units'   => 'px',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_add_cart_button_divider_3',
		'section'  => 'jupiterx_product_list_add_cart_button',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_add_cart_button_spacing',
		'section'   => 'jupiterx_product_list_add_cart_button',
		'css_var'   => 'product-list-add-cart-button',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
			],
		],
		'default' => [
			'desktop' => [
				'margin_bottom' => 0.2,
			],
		],
	] );
} );

// Sale Badge.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_sale_badge_typography',
		'section'    => 'jupiterx_product_list_sale_badge',
		'responsive' => true,
		'css_var'    => 'product-list-sale-badge',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .onsale',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_sale_badge_background_color',
		'section'   => 'jupiterx_product_list_sale_badge',
		'css_var'   => 'product-list-sale-badge-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .onsale',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_sale_badge_border',
		'section'   => 'jupiterx_product_list_sale_badge',
		'css_var'   => 'product-list-sale-badge-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width' => [
				'size' => '0',
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .onsale',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_sale_badge_divider_3',
		'section'  => 'jupiterx_product_list_sale_badge',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_sale_badge_spacing',
		'section'   => 'jupiterx_product_list_sale_badge',
		'css_var'   => 'product-list-sale-badge',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .onsale',
			],
		],
	] );
} );

// Out of Stock.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_outstock_badge_typography',
		'section'    => 'jupiterx_product_list_outstock_badge',
		'responsive' => true,
		'css_var'    => 'product-list-outstock-badge',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-out-of-stock',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_outstock_badge_background_color',
		'section'   => 'jupiterx_product_list_outstock_badge',
		'css_var'   => 'product-list-outstock-badge-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-out-of-stock',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_outstock_badge_border',
		'section'   => 'jupiterx_product_list_outstock_badge',
		'css_var'   => 'product-list-outstock-badge-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width'  => [
				'size' => '0',
				'unit' => 'px',
			],
			'radius' => [
				'size' => 4,
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-out-of-stock',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_outstock_badge_divider_3',
		'section'  => 'jupiterx_product_list_outstock_badge',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_outstock_badge_spacing',
		'section'   => 'jupiterx_product_list_outstock_badge',
		'css_var'   => 'product-list-outstock-badge',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-out-of-stock',
			],
		],
	] );
} );

// Item Container.
add_action( 'jupiterx_after_customizer_register', function() {
	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_product_list_item_container_align',
		'section'   => 'jupiterx_product_list_item_container',
		'label'     => __( 'Align', 'jupiterx' ),
		'choices'   => JupiterX_Customizer_Utils::get_align(),
		'css_var'   => 'product-list-item-container-align',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product',
				'property' => 'text-align',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_item_container_background_color',
		'section'   => 'jupiterx_product_list_item_container',
		'css_var'   => 'product-list-item-container-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products .jupiterx-product-container',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_item_container_border',
		'section'   => 'jupiterx_product_list_item_container',
		'css_var'   => 'product-list-item-container-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width' => [
				'size' => '0',
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products .jupiterx-product-container',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_item_container_divider_3',
		'section'  => 'jupiterx_product_list_item_container',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_item_container_spacing',
		'section'   => 'jupiterx_product_list_item_container',
		'css_var'   => 'product-list-item-container',
		'exclude'   => [ 'margin' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products .jupiterx-product-container',
			],
		],
	] );
} );

// Pagination.
add_action( 'jupiterx_after_customizer_register', function() {
	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_product_list_pagination_align',
		'section'   => 'jupiterx_product_list_pagination',
		'label'     => __( 'Align', 'jupiterx' ),
		'column'    => '4',
		'choices'   => JupiterX_Customizer_Utils::get_align(),
		'css_var'   => 'product-list-pagination-align',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => 'center',
			'tablet'  => 'center',
			'mobile'  => 'center',
		],
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination',
				'property' => 'text-align',
			],
		],
	] );

	// Gutter Space.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-text',
		'settings'    => 'jupiterx_product_list_pagination_gutter_space',
		'section'     => 'jupiterx_product_list_pagination',
		'css_var'     => 'product-list-pagination-gutter-space',
		'label_empty' => true,
		'column'      => '4',
		'icon'        => 'grid-horizontal-space',
		'transport'   => 'postMessage',
		'input_type'  => 'number',
		'unit'        => 'px',
		'output'      => [
			[
				'element'       => '.woocommerce nav.woocommerce-pagination ul li',
				'property'      => 'margin-left',
				'value_pattern' => 'calc($px / 2)',
			],
			[
				'element'       => '.woocommerce nav.woocommerce-pagination ul li',
				'property'      => 'margin-right',
				'value_pattern' => 'calc($px / 2)',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_pagination_typography',
		'section'    => 'jupiterx_product_list_pagination',
		'responsive' => true,
		'css_var'    => 'product-list-pagination-typography',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers',
			],
		],
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-background',
		'settings'   => 'jupiterx_product_list_pagination_background',
		'section'    => 'jupiterx_product_list_pagination',
		'css_var'    => 'product-list-pagination-background',
		'transport'  => 'postMessage',
		'exclude'    => [ 'image', 'position', 'repeat', 'attachment', 'size' ],
		'output'     => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_pagination_border',
		'section'   => 'jupiterx_product_list_pagination',
		'css_var'   => 'product-list-pagination-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul li .page-numbers, .woocommerce nav.woocommerce-pagination ul li:first-child .page-numbers, .woocommerce nav.woocommerce-pagination ul li:last-child .page-numbers',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings' => 'jupiterx_product_list_add_cart_buton_label_1',
		'section'  => 'jupiterx_product_list_pagination',
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-background',
		'settings'   => 'jupiterx_product_list_pagination_background_hover',
		'section'    => 'jupiterx_product_list_pagination',
		'css_var'    => 'product-list-pagination-background-hover',
		'transport'  => 'postMessage',
		'exclude'    => [ 'image', 'position', 'repeat', 'attachment', 'size' ],
		'output'     => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers:not(.current):hover',
			],
		],
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_pagination_color_hover',
		'section'   => 'jupiterx_product_list_pagination',
		'column'    => '4',
		'css_var'   => 'product-list-pagination-color-hover',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination ul .page-numbers:not(.current):hover',
				'property' => 'color',
			],
		],
	] );

	// Border Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_pagination_border_color_hover',
		'section'   => 'jupiterx_product_list_pagination',
		'column'    => '4',
		'css_var'   => 'product-list-pagination-border-color-hover',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination ul li .page-numbers:not(.current):hover, .woocommerce nav.woocommerce-pagination ul li:first-child .page-numbers:not(.current):hover, .woocommerce nav.woocommerce-pagination ul li:last-child .page-numbers:not(.current):hover',
				'property' => 'border-color',
			],
		],
	] );

	// Active label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Active', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'green',
		'settings' => 'jupiterx_product_list_add_cart_buttdn_label_1',
		'section'  => 'jupiterx_product_list_pagination',
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-background',
		'settings'   => 'jupiterx_product_list_pagination_background_active',
		'section'    => 'jupiterx_product_list_pagination',
		'css_var'    => 'product-list-pagination-background-active',
		'transport'  => 'postMessage',
		'exclude'    => [ 'image', 'position', 'repeat', 'attachment', 'size' ],
		'output'     => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers.current',
			],
		],
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_pagination_color_active',
		'section'   => 'jupiterx_product_list_pagination',
		'column'    => '4',
		'css_var'   => 'product-list-pagination-color-active',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination ul .page-numbers.current',
				'property' => 'color',
			],
		],
	] );

	// Border Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_pagination_border_color_active',
		'section'   => 'jupiterx_product_list_pagination',
		'column'    => '4',
		'css_var'   => 'product-list-pagination-border-color-active',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination ul li .page-numbers.current, .woocommerce nav.woocommerce-pagination ul li:first-child .page-numbers.current, .woocommerce nav.woocommerce-pagination ul li:last-child .page-numbers.current',
				'property' => 'border-color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_pagination_divider',
		'section'  => 'jupiterx_product_list_pagination',
	] );

	// Margin.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_pagination_margin',
		'section'   => 'jupiterx_product_list_pagination',
		'css_var'   => 'product-list-pagination-margin',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination',
			],
		],
	] );

	// Padding.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_pagination_padding',
		'section'   => 'jupiterx_product_list_pagination',
		'css_var'   => 'product-list-pagination-padding',
		'exclude'   => [ 'margin' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers',
			],
		],
	] );
} );


// Quick View Button.
add_action( 'jupiterx_after_customizer_register', function() {
	// Icon.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_list_quick_view_button_icon',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button-icon',
		'label'     => __( 'Icon', 'jupiterx' ),
		'column'    => '3',
		'default'   => true,
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:before',
				'property'      => 'display',
				'exclude'       => [ true ],
				'value_pattern' => 'none',
			],
			[
				'element'       => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:before',
				'property'      => 'display',
				'exclude'       => [ false ],
				'value_pattern' => 'inline',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_quick_view_button_typography',
		'section'    => 'jupiterx_product_list_quick_view_button',
		'responsive' => true,
		'css_var'    => 'product-list-quick-view-button',
		'exclude'    => [ 'line_height' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_quick_view_button_background_color',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button-background-color',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_quick_view_button_border',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
			],
		],
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_quick_view_button_shadow',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
				'units'   => 'px',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings' => 'jupiterx_product_list_quick_view_button_label_1',
		'section'  => 'jupiterx_product_list_quick_view_button',
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_quick_view_button_text_color_hover',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button-text-color-hover',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:hover',
				'property' => 'color',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_quick_view_button_background_color_hover',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button-background-color-hover',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:hover',
				'property' => 'background-color',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_quick_view_button_border_color_hover',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button-border-color-hover',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:hover',
				'property' => 'border-color',
			],
		],
	] );

	// Shadow Hover.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_quick_view_button_shadow_hover',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:hover',
				'units'   => 'px',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_quick_view_button_divider_3',
		'section'  => 'jupiterx_product_list_quick_view_button',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_quick_view_button_spacing',
		'section'   => 'jupiterx_product_list_quick_view_button',
		'css_var'   => 'product-list-quick-view-button',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
			],
		],
		'default'   => [
			'desktop' => [
				'margin_left' => 1,
			],
			'tablet' => [
				'margin_left' => 0,
			],
		],
	] );
} );

// Load More Full Width.
add_action( 'jupiterx_after_customizer_register', function() {
	// Full width.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_list_loadmore_button_full_width',
		'section'   => 'jupiterx_product_list_loadmore_button',
		'css_var'   => 'product-list-loadmore-button-full-width',
		'label'     => __( 'Full Width', 'jupiterx' ),
		'column'    => '12',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
				'property'      => 'width',
				'exclude'       => [ false ],
				'value_pattern' => '100',
				'units'         => '%',
			],
			[
				'element'       => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
				'property'      => 'width',
				'exclude'       => [ true ],
				'value_pattern' => 'auto',
			],
		],
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_loadmore_button_typography',
		'section'    => 'jupiterx_product_list_loadmore_button',
		'responsive' => true,
		'css_var'    => 'product-list-loadmore-button-typography',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-color',
		'settings' => 'jupiterx_product_list_loadmore_button_background_color',
		'section'  => 'jupiterx_product_list_loadmore_button',
		'css_var'  => 'product-list-loadmore-button-background-color',
		'icon'     => 'background-color',
		'transport' => 'postMessage',
		'output'   => [
			[
				'element'  => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_loadmore_button_border',
		'section'   => 'jupiterx_product_list_loadmore_button',
		'css_var'   => 'product-list-loadmore-button-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
			],
		],
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_loadmore_button_shadow',
		'section'   => 'jupiterx_product_list_loadmore_button',
		'css_var'   => 'product-list-loadmore-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
				'units'   => 'px',
			],
		],
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-label',
		'label'      => __( 'Hover', 'jupiterx' ),
		'label_type' => 'fancy',
		'color'      => 'orange',
		'settings' => 'jupiterx_product_list_loadmore_button_label_1',
		'section'  => 'jupiterx_product_list_loadmore_button',
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_loadmore_button_text_color_hover',
		'section'   => 'jupiterx_product_list_loadmore_button',
		'css_var'   => 'product-list-loadmore-button-text-color-hover',
		'column'    => '3',
		'icon'      => 'font-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more:hover',
				'property' => 'color',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_loadmore_button_background_color_hover',
		'section'   => 'jupiterx_product_list_loadmore_button',
		'css_var'   => 'product-list-loadmore-button-background-color-hover',
		'column'    => '3',
		'icon'      => 'background-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more:hover',
				'property' => 'background-color',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_loadmore_button_border_color_hover',
		'section'   => 'jupiterx_product_list_loadmore_button',
		'css_var'   => 'product-list-loadmore-button-border-color-hover',
		'column'    => '3',
		'icon'      => 'border-color',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more:hover',
				'property' => 'border-color',
			],
		],
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_loadmore_button_shadow_hover',
		'section'   => 'jupiterx_product_list_loadmore_button',
		'css_var'   => 'product-list-loadmore-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more:hover',
				'units'   => 'px',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_loadmore_button_divider',
		'section'  => 'jupiterx_product_list_loadmore_button',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_loadmore_button_spacing',
		'section'   => 'jupiterx_product_list_loadmore_button',
		'css_var'   => 'product-list-loadmore-button-spacing',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
			],
		],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 3,
			],
		],
	] );

});
