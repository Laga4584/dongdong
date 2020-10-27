<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to different functions 
 * 
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 2.0.1
 */

/**
 * Get Product Type
 * Added to add compability for woocommerce version 3.0
 */
function woo_min_max_get_product_type($product) {
    return method_exists($product, 'get_type') ? $product->get_type() : $product->product_type;
}

/**
 * Get Product Id
 * Added to add compability for woocommerce version 3.0
 */
function woo_min_max_get_product_id($product) {
    return method_exists($product, 'get_id') ? $product->get_id() : $product->id;
}

/**
 * Get Product Variation Id
 * Added to add compability for woocommerce version 3.0
 */
function woo_min_max_get_variation_id($variation) {
    return method_exists($variation, 'get_id') ? $variation->get_id() : $variation->variation_id;
}