<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/** public class
 * 
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 1.0.0
 */
class WOO_Min_Max_Quantities_Public {

    // define some class variables
    var $minimum_order_quantity;
    var $maximum_order_quantity;
    var $minimum_order_value;
    var $maximum_order_value;
    var $minimum_order_items;
    var $maximum_order_items;
    var $excludes = array();

    function __construct() {

        // assign global settings into variables
        $this->minimum_order_quantity   = absint( get_option( 'woocommerce_minimum_order_quantity' ) );
        $this->maximum_order_quantity   = absint( get_option( 'woocommerce_maximum_order_quantity' ) );
        $this->minimum_order_value      = absint( get_option( 'woocommerce_minimum_order_value' ) );
        $this->maximum_order_value      = absint( get_option( 'woocommerce_maximum_order_value' ) );
        $this->minimum_order_items      = absint( get_option( 'woocommerce_minimum_order_items' ) );
        $this->maximum_order_items      = absint( get_option( 'woocommerce_maximum_order_items' ) );

        /**
         * Add curency switcher support
         * https://codecanyon.net/item/woocommerce-currency-switcher/8085217
         * 
         * Convert price based on selected(current) currency
         * 
         */
        if (class_exists('WOOCS')) {

            global $WOOCS;
            $this->minimum_order_value = $WOOCS->raw_woocommerce_price($this->minimum_order_value);
            $this->maximum_order_value = $WOOCS->raw_woocommerce_price($this->maximum_order_value);
        }
    }

    /**
     * Add an error
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function add_error($error) {

        if (function_exists('wc_add_notice')) {
            wc_add_notice($error, 'error');
        } else {

            WC()->add_error($error);
        }
    }

    /**
     * Get product or variation ID to check
     * 
     * @return int
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.4
     */
    public function get_id_to_check($values) {

        // if variable product	
        if ($values['variation_id']) {

            // get min max rules
            $min_max_rules = get_post_meta($values['variation_id'], 'min_max_rules', true);

            // if min max rules checkbox at variation is checked then we checkng id will be variation id
            // else product id
            if ('yes' === $min_max_rules) {
                $checking_id = $values['variation_id'];
            } else {
                $checking_id = $values['product_id'];
            }
        } else { // if product type is simple
            $checking_id = $values['product_id'];
        }

        return $checking_id;
    }

    /**
     * Check product type is composite or not
     * 
     * @return int
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.4
     */
    public function is_composite_product($product_id) {

        if (empty($product_id)) {
            return false;
        }

        $product = wc_get_product($product_id);

        if ('composite' === woo_min_max_get_product_type($product)) {
            return true;
        }

        return false;
    }

    /**
     * Check cart items
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_qunatites_check_cart_items() {

        $allow = apply_filters( 'woo_min_max_qunatites_check_cart_items', true );
        if( !$allow ) return;

        $checked_ids = $product_quantities = $category_quantities = array();
        $total_quantity = $total_cost = 0;
        $apply_cart_rules = false;

        // Count items + variations first
        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {

            $product = $values['data'];
            $checking_id = $this->get_id_to_check($values);

            if (!isset($product_quantities[$checking_id])) {
                $product_quantities[$checking_id] = $values['quantity'];
            } else {
                $product_quantities[$checking_id] += $values['quantity'];
            }

            // do_not_count and cart_exclude from variation or product
            $minmax_do_not_count = apply_filters('woo_min_max_quantity_minmax_do_not_count', ( 'yes' === get_post_meta($checking_id, 'variation_minmax_do_not_count', true) ? 'yes' : get_post_meta($values['product_id'], 'minmax_do_not_count', true)), $checking_id, $cart_item_key, $values);

            $minmax_cart_exclude = apply_filters('woo_min_max_quantity_minmax_cart_exclude', ( 'yes' === get_post_meta($checking_id, 'variation_minmax_cart_exclude', true) ? 'yes' : get_post_meta($values['product_id'], 'minmax_cart_exclude', true)), $checking_id, $cart_item_key, $values);

            if ('yes' !== $minmax_do_not_count && 'yes' !== $minmax_cart_exclude) {
                $total_cost += $product->get_price() * $values['quantity'];
            }
        }

        // Check cart items
        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
            $checking_id = $this->get_id_to_check($values);
            $terms = get_the_terms($values['product_id'], 'product_cat');
            $found_term_ids = array();

            if ($terms) {

                foreach ($terms as $term) {

                    if ('yes' === get_post_meta($checking_id, 'minmax_category_group_of_exclude', true) || 'yes' === get_post_meta($checking_id, 'variation_minmax_category_group_of_exclude', true)) {
                        continue;
                    }
                    
                    if (in_array($term->term_id, $found_term_ids)) {
                        continue;
                    }

                    $found_term_ids[] = $term->term_id;
                    $category_quantities[$term->term_id] = isset($category_quantities[$term->term_id]) ? $category_quantities[$term->term_id] + $values['quantity'] : $values['quantity'];

                    // Record count in parents of this category too
                    $parents = get_ancestors($term->term_id, 'product_cat');

                    foreach ($parents as $parent) {
                        if (in_array($parent, $found_term_ids)) {
                            continue;
                        }

                        $found_term_ids[] = $parent;
                        $category_quantities[$parent] = isset($category_quantities[$parent]) ? $category_quantities[$parent] + $values['quantity'] : $values['quantity'];
                    }
                }
            }

            // Check item rules once per product ID
            if (in_array($checking_id, $checked_ids)) {
                continue;
            }

            $product = $values['data'];

            // do_not_count and cart_exclude from variation or product
            $minmax_do_not_count = apply_filters('woo_min_max_quantity_minmax_do_not_count', ( 'yes' === get_post_meta($checking_id, 'variation_minmax_do_not_count', true) ? 'yes' : get_post_meta($values['product_id'], 'minmax_do_not_count', true)), $checking_id, $cart_item_key, $values);

            $minmax_cart_exclude = apply_filters('woo_min_max_quantity_minmax_cart_exclude', ( 'yes' === get_post_meta($checking_id, 'variation_minmax_cart_exclude', true) ? 'yes' : get_post_meta($values['product_id'], 'minmax_cart_exclude', true)), $checking_id, $cart_item_key, $values);

            if ('yes' === $minmax_do_not_count || 'yes' === $minmax_cart_exclude) {
                // Do not count
                $this->excludes[] = $product->get_title();
            } else {
                $total_quantity += $product_quantities[$checking_id];
            }

            if ('yes' !== $minmax_cart_exclude) {
                $apply_cart_rules = true;
            }

            $checked_ids[] = $checking_id;

            if ($values['variation_id']) {
                $min_max_rules = get_post_meta($values['variation_id'], 'min_max_rules', true);

                // variation level min max rules enabled
                if ('yes' === $min_max_rules) {
                    $minimum_quantity = absint(apply_filters('woo_min_max_quantity_minimum_allowed_quantity', get_post_meta($values['variation_id'], 'variation_minimum_allowed_quantity', true), $values['variation_id'], $cart_item_key, $values));

                    $maximum_quantity = absint(apply_filters('woo_min_max_quantity_maximum_allowed_quantity', get_post_meta($values['variation_id'], 'variation_maximum_allowed_quantity', true), $values['variation_id'], $cart_item_key, $values));

                    $group_of_quantity = absint(apply_filters('woo_min_max_quantity_group_of_quantity', get_post_meta($values['variation_id'], 'variation_group_of_quantity', true), $values['variation_id'], $cart_item_key, $values));
                } else {
                    $minimum_quantity = absint(apply_filters('woo_min_max_quantity_minimum_allowed_quantity', get_post_meta($values['product_id'], 'minimum_allowed_quantity', true), $values['product_id'], $cart_item_key, $values));

                    $maximum_quantity = absint(apply_filters('woo_min_max_quantity_maximum_allowed_quantity', get_post_meta($values['product_id'], 'maximum_allowed_quantity', true), $values['product_id'], $cart_item_key, $values));

                    $group_of_quantity = absint(apply_filters('woo_min_max_quantity_group_of_quantity', get_post_meta($values['product_id'], 'group_of_quantity', true), $values['product_id'], $cart_item_key, $values));
                }
            } else {
                $minimum_quantity = absint(apply_filters('woo_min_max_quantity_minimum_allowed_quantity', get_post_meta($checking_id, 'minimum_allowed_quantity', true), $checking_id, $cart_item_key, $values));

                $maximum_quantity = absint(apply_filters('woo_min_max_quantity_maximum_allowed_quantity', get_post_meta($checking_id, 'maximum_allowed_quantity', true), $checking_id, $cart_item_key, $values));

                $group_of_quantity = absint(apply_filters('woo_min_max_quantity_group_of_quantity', get_post_meta($checking_id, 'group_of_quantity', true), $checking_id, $cart_item_key, $values));
            }

            $this->woo_min_max_qunatites_check_rules($product, $product_quantities[$checking_id], $minimum_quantity, $maximum_quantity, $group_of_quantity);
        }

        // Cart rules
        if ($apply_cart_rules) {

            $excludes = '';

            if (sizeof($this->excludes) > 0) {
                $excludes = ' (' . __('excludes ', 'woo_min_max_quantities') . implode(', ', $this->excludes) . ')';
            }

            // Check cart quantity
            $quantity = $this->minimum_order_quantity;

            if ($quantity > 0 && $total_quantity < $quantity) {

                $allow = apply_filters( 'woo_min_max_qunatites_allow_min_order_qty_rules', true );
                if( !$allow ) return;

                $err_msg = get_option( 'woocommerce_cartpage_min_oqty_err_msg' );
                $find = array( '{min_order_qty}' );
                $replace = array( $quantity );
                $err_msg = str_replace( $find, $replace, $err_msg );
                $this->add_error( $err_msg . $excludes );

                return;
            }

            $quantity = $this->maximum_order_quantity;

            if ($quantity > 0 && $total_quantity > $quantity) {

                $err_msg = get_option( 'woocommerce_cartpage_max_oqty_err_msg' );
                $find = array( '{max_order_qty}' );
                $replace = array( $quantity );
                $err_msg = str_replace($find, $replace, $err_msg);
                $this->add_error( $err_msg );

                return;
            }

            $total_cost = apply_filters( 'woo_min_max_total_cost', $total_cost );

            // Check cart value
            if ($this->minimum_order_value && $total_cost && $total_cost < $this->minimum_order_value) {

                $err_msg = get_option( 'woocommerce_cartpage_min_ovalue_err_msg' );
                $find = array( '{min_order_value}' );
                $replace = array( wc_price($this->minimum_order_value) );
                $err_msg = str_replace( $find, $replace, $err_msg );
                $this->add_error( $err_msg . $excludes );

                return;
            }

            if ($this->maximum_order_value && $total_cost && $total_cost > $this->maximum_order_value) {

                $err_msg = get_option( 'woocommerce_cartpage_max_ovalue_err_msg' );
                $find = array( '{max_order_value}' );
                $replace = array( wc_price($this->maximum_order_value) );
                $err_msg = str_replace( $find, $replace, $err_msg );
                $this->add_error( $err_msg );

                return;
            }
        }

        // Check category rules
        foreach ($category_quantities as $category => $quantity) {
            
            $group_of_quantity_min = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $category, 'group_of_quantity_min', true ) : get_woocommerce_term_meta($category, 'group_of_quantity_min', true);
                        
            if ($group_of_quantity_min > 0 && $quantity < $group_of_quantity_min) {
                
                $term = get_term_by( 'id', $category, 'product_cat' );
                $err_msg = get_option( 'woocommerce_cat_rule_min_qty_err_msg' );
                $find = array( '{min_qty}', '{category_title}' );
                $replace = array( $group_of_quantity_min, $term->name );
                $err_msg = str_replace( $find, $replace, $err_msg );
                $this->add_error($err_msg);
                return;
            }

            $group_of_quantity_max = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $category, 'group_of_quantity_max', true ) : get_woocommerce_term_meta($category, 'group_of_quantity_max', true);
            $group_of_quantity_max = apply_filters('woo_min_max_qty_group_of_qty_max', $group_of_quantity_max);
            if ($group_of_quantity_max > 0 && $quantity > $group_of_quantity_max) {

                $term = get_term_by( 'id', $category, 'product_cat' );
                $err_msg = get_option( 'woocommerce_cat_rule_max_qty_err_msg' );
                $find = array( '{max_qty}', '{category_title}' );
                $replace = array( $group_of_quantity_max, $term->name );
                $err_msg = str_replace( $find, $replace, $err_msg );
                $this->add_error($err_msg);                
                return;
            }

            $group_of_quantity = version_compare( WC_VERSION, '3.6', 'ge' ) ? get_term_meta( $category, 'group_of_quantity', true ) : get_woocommerce_term_meta($category, 'group_of_quantity', true);

            if ($group_of_quantity > 0 && ( $quantity % $group_of_quantity ) > 0) {

                $term = get_term_by('id', $category, 'product_cat');
                $product_names = array();

                foreach (WC()->cart->get_cart() as $cart_item_key => $values) {

                    // if exclude is enable, skip
                    if ('yes' === get_post_meta($values['product_id'], 'minmax_category_group_of_exclude', true) || 'yes' === get_post_meta($values['variation_id'], 'variation_minmax_category_group_of_exclude', true)) {
                        continue;
                    }

                    if (has_term($category, 'product_cat', $values['product_id'])) {
                        $product_names[] = $values['data']->get_name();
                    }
                }

                if ($product_names) {
                    
                    $err_msg = get_option( 'woocommerce_cat_rule_group_of_err_msg' );
                    $find = array( 
                        '{category_title}', 
                        '{products}', 
                        '{group_of_qty}', 
                        '{remaining_quantity}' 
                    );
                    $replace = array( 
                        $term->name, 
                        implode(', ', $product_names), 
                        $group_of_quantity, 
                        $group_of_quantity - ( $quantity % $group_of_quantity ) 
                    );
                    $err_msg = str_replace( $find, $replace, $err_msg );
                    $err_msg = apply_filters( 'woo_min_max_items_in_group_of', $err_msg, $term->name, $product_names, $group_of_quantity, $quantity);                    
                    $this->add_error( $err_msg );
                    return;
                }
            }
        }

        // check no of items in cart
        $no_of_products_in_cart = $this->woo_min_max_get_cart_items_count();

        if ($this->minimum_order_items > 0 && $no_of_products_in_cart < $this->minimum_order_items) {

            $err_msg = get_option( 'woocommerce_cartpage_min_oitems_err_msg' );
            $find = array( '{min_order_items}' );
            $replace = array( $this->minimum_order_items );
            $err_msg = str_replace( $find, $replace, $err_msg );
            $this->add_error( $err_msg );
            return;

        } else if ($this->maximum_order_items > 0 && $no_of_products_in_cart > $this->maximum_order_items) {

            $err_msg = get_option( 'woocommerce_cartpage_max_oitems_err_msg' );
            $find = array( '{max_order_items}' );
            $replace = array( $this->maximum_order_items );
            $err_msg = str_replace( $find, $replace, $err_msg );
            $this->add_error( $err_msg );           
            return;
        }
    }

    /**
     * Add respective error message dpeending on rules checked
     *
     * @access public
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_qunatites_check_rules($product, $quantity, $minimum_quantity, $maximum_quantity, $group_of_quantity) {

        $product_id = woo_min_max_get_product_id($product);

        // composite products plugin compatibility
        if ( $this->is_composite_product($product_id) ) {
            return;
        }

        if ($minimum_quantity > 0 && $quantity < $minimum_quantity) {

            $err_msg = get_option( 'woocommerce_cartpage_min_qty_err_msg' );
            $find = array( '{product_title}', '{min_qty}' );
            $replace = array( $product->get_title(), $minimum_quantity );
            $err_msg = str_replace( $find, $replace, $err_msg );
            $this->add_error( $err_msg );

        } elseif ($maximum_quantity > 0 && $quantity > $maximum_quantity) {

            $err_msg = get_option( 'woocommerce_cartpage_max_qty_err_msg' );
            $find = array( '{product_title}', '{max_qty}' );
            $replace = array( $product->get_title(), $maximum_quantity );
            $err_msg = str_replace( $find, $replace, $err_msg );
            $this->add_error( $err_msg );
        }

        if ($group_of_quantity > 0 && ( $quantity % $group_of_quantity )) {

            $err_msg = get_option( 'woocommerce_cartpage_group_of_err_msg' );
            $find = array( '{product_title}', '{group_of_qty}', '{product_required_qty}' );
            $replace = array( $product->get_title(), $group_of_quantity,  $group_of_quantity - ( $quantity % $group_of_quantity ));
            $err_msg = str_replace( $find, $replace, $err_msg );
            $this->add_error( $err_msg );
        }
    }

    /**
     * Updates the quantity arguments
     *
     * @return array
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_qunatites_update_quantity_args($data, $product) {

        // if simple product, then product id. If variable product then variation id.
        $product_id = woo_min_max_get_product_id($product);

        // composite product plugin compability
        if ($this->is_composite_product($product_id)) {
            return $data;
        }

        $group_of_quantity = get_post_meta($product_id, 'group_of_quantity', true);
        $minimum_quantity = get_post_meta($product_id, 'minimum_allowed_quantity', true);
        $maximum_quantity = get_post_meta($product_id, 'maximum_allowed_quantity', true);

        // if variable product, only apply in cart
        if ( is_cart() && $product->is_type('variation') ) {

            $variation_id = woo_min_max_get_product_id($product);

            // If variable product then get main product id 
            $main_product_id = $product->get_parent_id();            
            $group_of_quantity = get_post_meta($main_product_id, 'group_of_quantity', true);
        	$minimum_quantity = get_post_meta($main_product_id, 'minimum_allowed_quantity', true);
        	$maximum_quantity = get_post_meta($main_product_id, 'maximum_allowed_quantity', true);
        	
            $min_max_rules = get_post_meta($variation_id, 'min_max_rules', true);

            if ('no' === $min_max_rules || empty($min_max_rules)) {
                $min_max_rules = false;
            } else {
                $min_max_rules = true;
            }

            $variation_minimum_quantity = get_post_meta($variation_id, 'variation_minimum_allowed_quantity', true);
            $variation_maximum_quantity = get_post_meta($variation_id, 'variation_maximum_allowed_quantity', true);
            $variation_group_of_quantity = get_post_meta($variation_id, 'variation_group_of_quantity', true);

            // override product level
            if ($min_max_rules && $variation_minimum_quantity) {
                $minimum_quantity = $variation_minimum_quantity;
            }

            // override product level
            if ($min_max_rules && $variation_maximum_quantity) {
                $maximum_quantity = $variation_maximum_quantity;
            }

            // override product level
            if ($min_max_rules && $variation_group_of_quantity) {
                $group_of_quantity = $variation_group_of_quantity;
            }
        }

        if ($minimum_quantity) {

            if ($product->managing_stock() && !$product->backorders_allowed() && absint($minimum_quantity) > $product->get_stock_quantity()) {
                $data['min_value'] = $product->get_stock_quantity();
            } else {
                $data['min_value'] = $minimum_quantity;
            }
        }

        if ($maximum_quantity) {

            if ($product->managing_stock() && $product->backorders_allowed()) {
                $data['max_value'] = $maximum_quantity;
            } elseif ($product->managing_stock() && absint($maximum_quantity) > $product->get_stock_quantity()) {
                $data['max_value'] = $product->get_stock_quantity();
            } else {
                $data['max_value'] = $maximum_quantity;
            }
        }

        if ($group_of_quantity) {
            $data['step'] = 1;

            // if both minimum and maximum quantity are set, make sure both are equally divisble by qroup of quantity
            if ($maximum_quantity && $minimum_quantity) {

                if (absint($maximum_quantity) % absint($group_of_quantity) === 0 && absint($minimum_quantity) % absint($group_of_quantity) === 0) {
                    $data['step'] = $group_of_quantity;
                }
            } elseif (!$maximum_quantity || absint($maximum_quantity) % absint($group_of_quantity) === 0) {

                $data['step'] = $group_of_quantity;
            }

            // set a new minimum if group of is set but not minimum
            // $data['min_value'] !== 0 will check it its displayed in group product. group product allow zero quantity
            if (!$minimum_quantity && $data['min_value'] !== 0 ) {
                $data['min_value'] = $group_of_quantity;
            }
        }

        // don't apply for cart as cart has qty already pre-filled
        if (!is_cart()) {
            $data['input_value'] = !empty($minimum_quantity) ? $minimum_quantity : $data['input_value'];
        }
        
        return $data;
    }

    /**
     * Adds variation min max settings to the localized variation parameters to be used by JS
     *
     * @access public
     * @param array $data
     * @param obhect $product
     * @param object $variation
     * @return array $data
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    function woo_min_max_qunatites_available_variation($data, $product, $variation) {

        $product_id = woo_min_max_get_product_id($product);
        $variation_id = woo_min_max_get_variation_id($variation);

        $min_max_rules = get_post_meta($variation_id, 'min_max_rules', true);

        if ('no' === $min_max_rules || empty($min_max_rules)) {
            $min_max_rules = false;
        } else {
            $min_max_rules = true;
        }

        $data['min_max_rules'] = $min_max_rules;
        //if( $min_max_rules === false )
        //return $data;

        $minimum_quantity = get_post_meta($product_id, 'minimum_allowed_quantity', true);
        $maximum_quantity = get_post_meta($product_id, 'maximum_allowed_quantity', true);
        $group_of_quantity = get_post_meta($product_id, 'group_of_quantity', true);

        $variation_minimum_quantity = get_post_meta($variation_id, 'variation_minimum_allowed_quantity', true);
        $variation_maximum_quantity = get_post_meta($variation_id, 'variation_maximum_allowed_quantity', true);
        $variation_group_of_quantity = get_post_meta($variation_id, 'variation_group_of_quantity', true);

        // override product level
        if ($variation->managing_stock()) {
            $product = $variation;
        }

        // override product level
        if ($min_max_rules && $variation_minimum_quantity) {
            $minimum_quantity = $variation_minimum_quantity;
        }

        // override product level
        if ($min_max_rules && $variation_maximum_quantity) {
            $maximum_quantity = $variation_maximum_quantity;
        }

        // override product level
        if ($min_max_rules && $variation_group_of_quantity) {
            $group_of_quantity = $variation_group_of_quantity;
        }

        if ($minimum_quantity) {

            if ($product->managing_stock() && $product->backorders_allowed() && absint($minimum_quantity) > $product->get_stock_quantity()) {
                $data['min_qty'] = $product->get_stock_quantity();
            } else {
                $data['min_qty'] = $minimum_quantity;
            }
        }

        if ($maximum_quantity) {

            if ($product->managing_stock() && $product->backorders_allowed()) {
                $data['max_qty'] = $maximum_quantity;
            } elseif ($product->managing_stock() && absint($maximum_quantity) > $product->get_stock_quantity()) {
                $data['max_qty'] = $product->get_stock_quantity();
            } else {
                $data['max_qty'] = $maximum_quantity;
            }
        }

        if ($group_of_quantity) {
            $data['step'] = 1;

            // if both minimum and maximum quantity are set, make sure both are equally divisble by qroup of quantity
            if ($maximum_quantity && $minimum_quantity) {

                if (absint($maximum_quantity) % absint($group_of_quantity) === 0 && absint($minimum_quantity) % absint($group_of_quantity) === 0) {
                    $data['step'] = $group_of_quantity;
                }
            } elseif (!$maximum_quantity || absint($maximum_quantity) % absint($group_of_quantity) === 0) {

                $data['step'] = $group_of_quantity;
            }
        }

        // don't apply for cart as cart has qty already pre-filled
        if (!is_cart()) {
            $data['input_value'] = !empty($minimum_quantity) ? $minimum_quantity : 1;
            if (empty($minimum_quantity) && empty($maximum_quantity)) {
                $data['input_value'] = $data['min_qty'] = $group_of_quantity;
            }
        }

        return $data;
    }

    /**
     * Add to cart validation
     *
     * @access public
     * @param mixed $pass
     * @param mixed $product_id
     * @param mixed $quantity
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_qunatites_add_to_cart($pass, $product_id, $quantity, $variation_id = 0) {

        $rule_for_variaton = false;

        // composite products plugin compability
        if ($this->is_composite_product($product_id)) {
            return $pass;
        }

        if ($variation_id) {

            $min_max_rules = get_post_meta($variation_id, 'min_max_rules', true);

            if ('yes' === $min_max_rules) {

                $maximum_quantity = absint(get_post_meta($variation_id, 'variation_maximum_allowed_quantity', true));
                $minimum_quantity = absint(get_post_meta($variation_id, 'variation_minimum_allowed_quantity', true));
                $rule_for_variaton = true;
            } else {

                $maximum_quantity = absint(get_post_meta($product_id, 'maximum_allowed_quantity', true));
                $minimum_quantity = absint(get_post_meta($product_id, 'minimum_allowed_quantity', true));
            }
        } else {

            $maximum_quantity = absint(get_post_meta($product_id, 'maximum_allowed_quantity', true));
            $minimum_quantity = absint(get_post_meta($product_id, 'minimum_allowed_quantity', true));
        }

        $total_quantity = $quantity;

        // Count items
        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {

            if ($rule_for_variaton) {

                if ($values['variation_id'] == $variation_id) {

                    $total_quantity += $values['quantity'];
                }
            } else {

                if ($values['product_id'] == $product_id) {

                    $total_quantity += $values['quantity'];
                }
            }
        }

        // Check for maximum product quantity exceeded
        if (isset($maximum_quantity) && $maximum_quantity > 0) {
            if ($total_quantity > 0 && $total_quantity > $maximum_quantity) {

                if (function_exists('wc_get_product ')) {

                    $_product = wc_get_product($product_id);
                } else {

                    $_product = new WC_Product($product_id);
                }

                $err_msg = get_option('woocommerce_addtocart_max_qty_err_msg');
                $find = array('{product_title}', '{max_qty}', '{cart_qty}');
                $replace = array( $_product->get_title(), $maximum_quantity, $total_quantity - $quantity);
                $err_msg = str_replace($find, $replace, $err_msg);

                $this->add_error(apply_filters('woo_max_allowed_product_qty_err', $err_msg, $_product, $maximum_quantity, $total_quantity - $quantity));

                $pass = false;
            }
        }

        // Check for minimum product quantity not reached
        if (isset($minimum_quantity) && $minimum_quantity > 0) {
            if ($total_quantity < $minimum_quantity) {

                if (function_exists('get_product')) {

                    $_product = get_product($product_id);
                } else {

                    $_product = new WC_Product($product_id);
                }

                $err_msg = get_option('woocommerce_addtocart_min_qty_err_msg');
                $find = array('{product_title}', '{min_qty}', '{cart_qty}');
                $replace = array( $_product->get_title(), $minimum_quantity, $total_quantity - $quantity);
                $err_msg = str_replace($find, $replace, $err_msg);

                $this->add_error(apply_filters('woo_min_allowed_product_qty_err', $err_msg, $_product, $minimum_quantity, $total_quantity - $quantity));

                $pass = false;
            }
        }

        // if item in cart
        $no_of_products_in_cart = $this->woo_min_max_get_cart_items_count();
        if (!empty($no_of_products_in_cart)) {

            if ($this->maximum_order_items > 0 && $no_of_products_in_cart >= $this->maximum_order_items) {

                $err_msg = get_option('woocommerce_addtocart_max_oitems_err_msg');
                $find = array('{max_order_items}', '{cart_qty}');
                $replace = array( $this->maximum_order_items, $no_of_products_in_cart);
                $err_msg = str_replace($find, $replace, $err_msg);        
                $this->add_error($err_msg);

                $pass = false;
            }
        }

        return $pass;
    }

    /**
     * Add quantity property to add to cart button on shop loop for simple products.
     *
     * @access public
     * @return void
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_qunatites_add_to_cart_link($html, $product) {

        $product_id = woo_min_max_get_product_id($product);
        $product_type = woo_min_max_get_product_type($product);

        if ('variable' !== $product_type && !$this->is_composite_product($product_id)) {

            $quantity_attribute = 1;
            $minimum_quantity = absint(get_post_meta($product_id, 'minimum_allowed_quantity', true));
            $group_of_quantity = absint(get_post_meta($product_id, 'group_of_quantity', true));

            if ($minimum_quantity || $group_of_quantity) {

                $quantity_attribute = $minimum_quantity;

                if ($group_of_quantity > 0 && $minimum_quantity < $group_of_quantity) {
                    $quantity_attribute = $group_of_quantity;
                }

                $html = str_replace('<a ', '<a data-quantity="' . $quantity_attribute . '" ', $html);
            }
        }

        return $html;
    }

    /**
     * Counts the number of products ( items ) in cart
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_get_cart_items_count() {

        $no_of_products_in_cart = count(WC()->cart->get_cart());

        return $no_of_products_in_cart;
    }

    /**
     * If the minimum allowed quantity for purchase is lower then the current stock, we need to
     * let the user know that they are on backorder, or out of stock.
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.4
     */
    public function woo_maybe_show_backorder_message($args, $product) {

        // if managing stock is not enabled then return
        if (!$product->managing_stock()) {
            return $args;
        }

        // Figure out what our minimum_quantity is
        $product_id = woo_min_max_get_product_id($product);
        if ('WC_Product_Variation' === get_class($product)) {
            $variation_id = woo_min_max_get_variation_id($product);
            $min_max_rules = get_post_meta($variation_id, 'min_max_rules', true);
            if ('yes' === $min_max_rules) {
                $minimum_quantity = absint(get_post_meta($variation_id, 'variation_minimum_allowed_quantity', true));
            } else {
                $minimum_quantity = absint(get_post_meta($product_id, 'minimum_allowed_quantity', true));
            }
        } else {
            $minimum_quantity = absint(get_post_meta($product_id, 'minimum_allowed_quantity', true));
        }

        // If the minimum quantity allowed for purchase is smaller then the amount in stock, we need
        // clearer messaging
        if ($minimum_quantity > 0 && $product->get_stock_quantity() < $minimum_quantity) {
            if ($product->backorders_allowed()) {
                return array(
                    'availability' => __('Available on backorder', 'woo_min_max_quantities'),
                    'class' => 'available-on-backorder',
                );
            } else {
                return array(
                    'availability' => __('Out of stock', 'woo_min_max_quantities'),
                    'class' => 'out-of-stock',
                );
            }
        }

        return $args;
    }
    
   

    /** Add hooks
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function add_hooks() {

        // Check items
        add_action('woocommerce_check_cart_items', array($this, 'woo_min_max_qunatites_check_cart_items'));

        // quantity selelectors (2.0+)
        add_filter('woocommerce_quantity_input_args', array($this, 'woo_min_max_qunatites_update_quantity_args'), 10, 2);
        add_filter('woocommerce_available_variation', array($this, 'woo_min_max_qunatites_available_variation'), 10, 3);

        // Prevent add to cart
        add_filter('woocommerce_add_to_cart_validation', array($this, 'woo_min_max_qunatites_add_to_cart'), 10, 4);

        // Min add to cart ajax
        add_filter('woocommerce_loop_add_to_cart_link', array($this, 'woo_min_max_qunatites_add_to_cart_link'), 10, 2);

        // Show a notice when items would have to be on back order because of min/max
        add_filter('woocommerce_get_availability', array($this, 'woo_maybe_show_backorder_message'), 10, 2);        
    }
}