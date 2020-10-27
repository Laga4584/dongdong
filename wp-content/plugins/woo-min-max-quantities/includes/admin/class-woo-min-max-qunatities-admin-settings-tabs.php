<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Setting page Class
 * 
 * Handles Settings page functionality of plugin
 * 
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 1.0.0
 */
class WOO_Min_Max_Quantities_Settings_Tabs {

    function __construct() {
        # code...
    }

    /**
     * Add plugin settings
     * 
     * Handles to add plugin settings in Min/Max Quantites Settings Tab
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_quantites_get_settings() {

        // Global Settings for min max quantites
        $woo_min_max_quantites_settings = array(
            array(
                'name' => __('Minimun/Maximum Quantities', 'woo_min_max_quantities'),
                'type' => 'title',
                'desc' => '',
                'id' => 'woocommerce_min_max_quantity_options'
            ),
            array(
                'name' => __('Minimum order quantity', 'woo_min_max_quantities'),
                'desc' => __('The minimum allowed quantity of items in an order.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_minimum_order_quantity',
                'type' => 'text',
                'desc_tip' => true
            ),
            array(
                'name' => __('Maximum order quantity', 'woo_min_max_quantities'),
                'desc' => __('The maximum allowed quantity of items in an order.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_maximum_order_quantity',
                'type' => 'text',
                'desc_tip' => true
            ),
            array(
                'type' => 'sectionend',
                'id' => 'woocommerce_min_max_quantity_options'
            ),
            array(
                'name' => __('Minimun/Maximum Order Value', 'woo_min_max_quantities'),
                'type' => 'title',
                'desc' => '',
                'id' => 'woocommerce_min_max_order_value_options'
            ),
            array(
                'name' => __('Minimum order value', 'woo_min_max_quantities'),
                'desc' => __('The minimum allowed value of items in an order.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_minimum_order_value',
                'type' => 'text',
                'desc_tip' => true
            ),
            array(
                'name' => __('Maximum order value', 'woo_min_max_quantities'),
                'desc' => __('The maximum allowed value of items in an order.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_maximum_order_value',
                'type' => 'text',
                'desc_tip' => true
            ),
            array(
                'type' => 'sectionend',
                'id' => 'woocommerce_min_max_order_value_options'
            ),
            array(
                'name' => __('Minimun/Maximum Order Items', 'woo_min_max_quantities'),
                'type' => 'title',
                'desc' => '',
                'id' => 'woocommerce_min_max_order_items_options'
            ),
            array(
                'name' => __('Minimum order Items', 'woo_min_max_quantities'),
                'desc' => __('The minimum allowed number of items in an order.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_minimum_order_items',
                'type' => 'text',
                'desc_tip' => true
            ),
            array(
                'name' => __('Maximum order Items', 'woo_min_max_quantities'),
                'desc' => __('The maximum allowed number of items in an order.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_maximum_order_items',
                'type' => 'text',
                'desc_tip' => true
            ),
            array(
                'type' => 'sectionend',
                'id' => 'woocommerce_min_max_order_items_options'
            ),
            array(
                'name' => __('Add to Cart Error Messages', 'woo_min_max_quantities'),
                'type' => 'title',
                'desc' => 'Customize the error messages which will be display when product is added to cart.',
                'id' => 'woocommerce_mmq_addtocart_messages'
            ),
            array(
                'name' => __('Minimum product quantity not reached', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when minimum quantity for particular product not reached.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_addtocart_min_qty_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The minimum allowed quantity for {product_title} is {min_qty} (you currently have {cart_qty} in your cart).', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Maximum product quantity exceeded', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when maximum quantity for particular product exceeded.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_addtocart_max_qty_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The maximum allowed quantity for {product_title} is {max_qty} (you currently have {cart_qty} in your cart).', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Maximum order items exceeded', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when maximum allowed order items exceeded.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_addtocart_max_oitems_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The maximum allowed items is {max_order_items} (you currently have {cart_qty} in your cart).', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'type' => 'sectionend',
                'id' => 'woocommerce_mmq_addtocart_messages'
            ),
            array(
                'name' => __('Cart Page Error Messages', 'woo_min_max_quantities'),
                'type' => 'title',
                'desc' => 'Customize the error messages which will be display on cart page.',
                'id' => 'woocommerce_mmq_cartpage_messages'
            ),
            array(
                'name' => __('Minimum order quantity not reached', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when mimimum order quantity not reached.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_min_oqty_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The minimum allowed order quantity is {min_order_qty} - please add more items to your cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Maximum order quantity exceeded', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when maximum order quantity exceeded.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_max_oqty_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The maximum allowed order quantity is {max_order_qty} - please remove some items from your cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
             array(
                'name' => __('Minimum order value not reached', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when mimimum order value not reached.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_min_ovalue_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The minimum allowed order value is {min_order_value} - please add more items to your cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Maximum order value exceeded', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when maximum order value exceeded.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_max_ovalue_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The maximum allowed order value is {max_order_value} - please remove some items from your cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Minimum order items not reached', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when minimum order items not reached.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_min_oitems_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The minimum allowed items is {min_order_items} - please add more items to your cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Maximum order items exceeded', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when maximum allowed order items exceeded.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_max_oitems_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The maximum allowed items is {max_order_items} - please remove some items from your cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Minimum product quantity not reached', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when minimum quantity for particular product not reached.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_min_qty_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The minimum allowed quantity for {product_title} is {min_qty} - please increase the quantity in your cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Maximum product quantity exceeded', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when maximum quantity for particular product exceeded.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_max_qty_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('The maximum allowed quantity for {product_title} is {max_qty} - please decrease the quantity in your cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Product group of(step) quantity not allowed', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when product group of quantity not allowed', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cartpage_group_of_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('{product_title} must be bought in groups of {group_of_qty}. Please add or decrease another {product_required_qty} to continue.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),            
            array(
                'type' => 'sectionend',
                'id' => 'woocommerce_mmq_cartpage_messages'
            ),
            array(
                'name' => __('Category Error Messages', 'woo_min_max_quantities'),
                'type' => 'title',
                'desc' => 'Customize the error messages which will be display on cart page for Category rules.',
                'id' => 'woocommerce_mmq_category_messages'
            ),
            array(
                'name' => __('Minimum category quantity not reached', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when minimum quantity for particular category not reached.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cat_rule_min_qty_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('Your cart must contain at least {min_qty} products belonging to category {category_title}. Please add some quantity to cart.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Maximum category quantity exceeded', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when maximum quantity for particular category exceeded.', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cat_rule_max_qty_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('Your cart cannot contain more than {max_qty} products belonging to category {category_title}. Please remove some quantity to continue.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),
            array(
                'name' => __('Category group of(step) quantity not allowed', 'woo_min_max_quantities'),
                'desc' => __('This error message will be display when category group of quantity not allowed', 'woo_min_max_quantities'),
                'id' => 'woocommerce_cat_rule_group_of_err_msg',
                'type' => 'textarea',
                'desc_tip' => true,
                'default' => __('Items in the <strong>{category_title}</strong> category (<em>{products}</em>) must be bought in groups of {group_of_qty}. Please add another {remaining_quantity} to continue.', 'woo_min_max_quantities'),
                'class' => 'wide-input'
            ),    
            array(
                'type' => 'sectionend',
                'id' => 'woocommerce_mmq_category_messages'
            ),
        );

        return apply_filters( 'woo_min_max_quantites_get_settings', $woo_min_max_quantites_settings );
    }

    /**
     * Settings Tab
     * 
     * Adds the Minimum/Maximum tab to the WooCommerce settings page.
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_quantites_add_settings_tab($tabs) {

        $tabs['min_max_quantites'] = __('Minimum/Maximum Quantities', 'woo_min_max_quantities');

        return $tabs;
    }

    /**
     * Settings Tab Content
     * 
     * Adds the settings content to the min/max qunatities tab.
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_mqx_quantites_settings_tab_content() {

        woocommerce_admin_fields($this->woo_min_max_quantites_get_settings());
    }

    /**
     * Update Settings
     * 
     * Updates the settings when being saved.
     *
     *  @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_quantities_update_settings() {

        woocommerce_update_options($this->woo_min_max_quantites_get_settings());
    }

    /**
     * Adding Hooks
     * 
     * Adding proper hooks for the shortcodes.
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function add_hooks() {

        // Add filter to addd Min/Max Quantities tab on woocommerce setting page
        add_filter('woocommerce_settings_tabs_array', array($this, 'woo_min_max_quantites_add_settings_tab'), 99);

        // Add action to add Min/Max Quantities tab content
        add_action('woocommerce_settings_tabs_min_max_quantites', array($this, 'woo_min_mqx_quantites_settings_tab_content'));

        // Add action to save custom update content
        add_action('woocommerce_update_options_min_max_quantites', array($this, 'woo_min_max_quantities_update_settings'), 100);
    }

}