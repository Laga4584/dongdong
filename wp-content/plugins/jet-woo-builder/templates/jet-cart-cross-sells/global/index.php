<?php
/**
 * Cart Cross Sells products Template
 */

// Display custom column count
add_filter( 'woocommerce_cross_sells_columns', array( $this, 'change_cross_sells_columns_count' ) );

$settings = $this->get_settings_for_display();
$limit    = jet_woo_builder_shop_settings()->get( 'cross_sells_products_per_page' );

woocommerce_cross_sell_display( $limit, $settings['cross_sell_products_columns'], $settings['cross_sell_products_orderby'], $settings['cross_sell_products_order'] );
