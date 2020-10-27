<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Scripts Class
 *
 * Contains logic to add scripts and style in admin and public site 
 *
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 1.0.0
 */
class WOO_Min_Max_Quantities_Scripts {

    function __construct() {
        # code...
    }

    /**
     * Scripts functions
     * 
     * Add js on frontend only in product single page and cart page
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_quantities_load_scripts() {

        global $woocommerce;

        /**
         * https://woocommerce.com/products/woocommerce-quick-view/
         * check quick view plugin is activated or not. 
         * If activated then need to enque js to change qty value on selection of dropdown
         */
        $quick_view_activated = false;
        if (class_exists('WC_Quick_View')) {
            $quick_view_activated = true;
        }

        // only load on single product page and cart page
        if (is_product() || is_cart() || $quick_view_activated) {

            // register/enqueue scripts
            wp_register_script('woo-min-max-public-script', WOO_MIN_MAX_QUANTITIES_URL . 'includes/js/woo-min-max-public-scripts.js', array('jquery'), WOO_MIN_MAX_QUANTITIES_PLUGIN_VERSION, true );
            wp_enqueue_script('woo-min-max-public-script');
        }
    }

    /**
     * Scripts functions
     * 
     * Add js on backend only
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function woo_min_max_quantities_admin_scripts( $hook_suffix ) {
        
        global $post;

        // product page        
        if( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) && isset( $post->post_type ) && $post->post_type == 'product' ) {
                       
            wp_register_script( 'woo-min-max-admin-script', WOO_MIN_MAX_QUANTITIES_URL . 'includes/js/woo-min-max-admin-scripts.js', array( 'jquery' ), WOO_MIN_MAX_QUANTITIES_PLUGIN_VERSION );
            wp_enqueue_script( 'woo-min-max-admin-script' );
        }        
    }

    /**
     * Add action to enqueue lock field script
     * 
     * Check if page is product edit page
     * Check if page is translated product edit page, so original product edit page will not effect
     * Check if source_lang is set
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 2.0.3
     */
    public function woo_min_max_check_wpml_activated() {
        
        global $pagenow, $woocommerce_wpml;
        
        // check wpml and woocommerce multilingual plugin is activated
        if( function_exists('icl_object_id') && class_exists('woocommerce_wpml') ) {
                    
            if( ( $pagenow == 'post.php' && isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'product' && !empty( $woocommerce_wpml->products ) && !$woocommerce_wpml->products->is_original_product( $_GET['post'] ) ) ||
                ( $pagenow == 'post-new.php' && isset( $_GET['source_lang'] ) && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'product' ) && 
                !$woocommerce_wpml->settings['trnsl_interface'] ) {
                
                // add action to enqueue lock fields js
                add_action( 'admin_enqueue_scripts', array( $this, 'woo_min_max_load_wpml_lock_fields_js') );
            }
        }
    }

    /**
     * Enqueue lock field script
     * 
     * Handles to add lock fields js For WPML Support
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 2.0.3
     */
    public function woo_min_max_load_wpml_lock_fields_js() {
        
        // register lock script
        wp_register_script( 'woo-min-max-qty-wpml-lock-script', WOO_MIN_MAX_QUANTITIES_URL . 'includes/js/woo-min-max-lock-fields.js', array('jquery'), WOO_MIN_MAX_QUANTITIES_PLUGIN_VERSION );
        
        // enqueue lock script
        wp_enqueue_script( 'woo-min-max-qty-wpml-lock-script' );
    }

    /**
     * Add hooks ( action and filters). 
     * 
     * contains all action and filter related to scripts
     *
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.0
     */
    public function add_hooks() {

        // add action to add frontend script
        add_action( 'wp_enqueue_scripts', array( $this, 'woo_min_max_quantities_load_scripts' ) );

        // add action to add backend script
        add_action( 'admin_enqueue_scripts', array( $this, 'woo_min_max_quantities_admin_scripts' ) );

        // Add action to enqueue lock script when WPML is active and translated product is edited
        add_action( 'admin_init', array( $this, 'woo_min_max_check_wpml_activated') );
    }

}
