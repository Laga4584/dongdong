<?php
/**
 * Plugin Name: WooCommerce - Minimum/Maximum Quantities
 * Plugin URI: http://wpeliteplugins.com
 * Description: Lets you define minimum/maximum allowed quantities for products, variations and orders. Also you can define minimum/maximum order value.
 * Version: 2.1.1
 * Author: WPElitePlugins
 * Author URI: http://wpeliteplugins.com
 *
 * Text Domain: woo_min_max_quantities
 * Domain Path: languages
 *
 * WC tested up to: 4.2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Basic plugin definitions
 * 
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 1.0.0
 */
if ( ! defined( 'WOO_MIN_MAX_QUANTITIES_PLUGIN_VERSION' ) ) {
    define( 'WOO_MIN_MAX_QUANTITIES_PLUGIN_VERSION', '2.1.1' ); //Plugin version number
}
if ( ! defined( 'WOO_MIN_MAX_QUANTITIES_DIR' ) ) {
    define( 'WOO_MIN_MAX_QUANTITIES_DIR', dirname( __FILE__ ) ); // plugin dir
}
if ( ! defined( 'WOO_MIN_MAX_QUANTITIES_URL' ) ) {
    define( 'WOO_MIN_MAX_QUANTITIES_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if ( ! defined('WOO_MIN_MAX_QUANTITIES_ADMIN' ) ) {
    define( 'WOO_MIN_MAX_QUANTITIES_ADMIN', WOO_MIN_MAX_QUANTITIES_DIR . '/includes/admin' ); // plugin admin dir
}
if ( ! defined( 'WOO_MIN_MAX_QUANTITIES_IMG_URL' ) ) {
    define( 'WOO_MIN_MAX_QUANTITIES_IMG_URL', WOO_MIN_MAX_QUANTITIES_URL . 'includes/images' ); // plugin img url
}
if ( ! defined( 'WOO_MIN_MAX_QUANTITIES_MAIN_POST_TYPE' ) ) {
    define( 'WOO_MIN_MAX_QUANTITIES_MAIN_POST_TYPE', 'product' ); //woocommerce post type
}
if ( ! defined( 'WOO_MIN_MAX_QUANTITIES_PLUGIN_BASENAME' )) {
    define( 'WOO_MIN_MAX_QUANTITIES_PLUGIN_BASENAME', basename( WOO_MIN_MAX_QUANTITIES_DIR ) ); //Plugin base name
}
if ( ! defined( 'WOO_MIN_MAX_QUANTITIES_PLUGIN_KEY' ) ) {
    define( 'WOO_MIN_MAX_QUANTITIES_PLUGIN_KEY', 'woominmaxqty' );
}

// Required updater functions file
if ( ! function_exists( 'wpeliteplugins_updater_install' ) ) {
    require_once( 'includes/wpeliteplugins-upd-functions.php' );
}

/**
 * Check WooCommerce activated or not.
 * 
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 2.0.1
 */
function woo_min_max_check_activation() {

    if ( ! class_exists( 'Woocommerce' ) ) {

        // is this plugin active?
        if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
            // deactivate the plugin
            deactivate_plugins( plugin_basename( __FILE__ ) );
            // unset activation notice
            unset( $_GET['activate'] );

            /**
             * Display Notice if WooCommerce is not activated
             * 
             * @package WooCommerce - Minimum/Maximum Quantities
             * @since 2.0.1
             */
            function woo_min_max_admin_notices() {

                echo '<div class="error notice is-dismissible">';
                echo "<p><strong>" . esc_html__('WooCommerce needs to be activated to be able to use Minimum/Maximum Quantities.', 'woo_min_max_quantities') . "</strong></p>";
                echo '</div>';
            }

            // display notice
            add_action( 'admin_notices', 'woo_min_max_admin_notices' );
        }
    }
}

// Check WooCommerce plugin is Activated or not
add_action( 'admin_init', 'woo_min_max_check_activation' );

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 1.0.0
 */
function woo_min_max_quantites_load_text_domain() {

    // Set filter for plugin's languages directory
    $woo_min_max_quantities_lang_dir = dirname(plugin_basename(__FILE__)) . '/languages/';
    $woo_min_max_quantities_lang_dir = apply_filters( 'woo_min_max_languages_directory', $woo_min_max_quantities_lang_dir );

    // Traditional WordPress plugin locale filter
    $locale = apply_filters( 'plugin_locale', get_locale(), 'woo_min_max_quantities' );
    $mofile = sprintf('%1$s-%2$s.mo', 'woo_min_max_quantities', $locale);

    // Setup paths to current locale file
    $mofile_local = $woo_min_max_quantities_lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/' . WOO_MIN_MAX_QUANTITIES_PLUGIN_BASENAME . '/' . $mofile;

    if (file_exists($mofile_global)) { // Look in global /wp-content/languages/woo-min-max-quantities folder
        load_textdomain( 'woo_min_max_quantities', $mofile_global );
    } elseif (file_exists($mofile_local)) { // Look in local /wp-content/plugins/woo-min-max-quantities/languages/ folder
        load_textdomain( 'woo_min_max_quantities', $mofile_local );
    } else { // Load the default language files
        load_plugin_textdomain( 'woo_min_max_quantities', false, $woo_min_max_quantities_lang_dir );
    }
}

/**
 * Add plugin action links
 *
 * Adds a Settings, Support and Docs link to the plugin list.
 *
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 1.0.0
 */
function woo_min_max_quantites_add_plugin_links( $links ) {
    $plugin_links = array(
        '<a href="admin.php?page=wc-settings&tab=min_max_quantites">' . esc_html__('Settings', 'woo_min_max_quantities') . '</a>',
        '<a href="http://documents.wpeliteplugins.com/woocommerce-min-max-quantities/">' . esc_html__('Docs', 'woo_min_max_quantities') . '</a>'
    );

    return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'woo_min_max_quantites_add_plugin_links' );

//add action to load plugin
add_action( 'plugins_loaded', 'woo_min_max_quantities_plugin_loaded' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after dependent plugin is loaded successfully
 * 
 * @package WooCommerce - Minimum/Maximum Quantities
 * @since 1.0.0
 */
function woo_min_max_quantities_plugin_loaded() {

    //check Woocommerce is activated or not
    if ( class_exists( 'Woocommerce' ) ) {

        // load first plugin text domain
        woo_min_max_quantites_load_text_domain();

        /**
         * Deactivation Hook
         * 
         * Register plugin deactivation hook.
         * 
         * @package WooCommerce - Minimum/Maximum Quantities
         * @since 1.0.0
         */
        register_deactivation_hook(__FILE__, 'woo_min_max_quantites_uninstall');

        /**
         * Plugin Setup (On Deactivation)
         * 
         * Delete  plugin options.
         * 
         * @package WooCommerce - Minimum/Maximum Quantities
         * @since 1.0.0
         */
        function woo_min_max_quantites_uninstall() {
            // do something on uninstall
        }

        //global variables
        global $woo_min_max_quantities_scripts, $woo_min_max_quantities_admin, $woo_min_max_quantities_public, $woo_min_max_quantities_settings_tabs;

        // loads the Misc Functions file
        require_once ( WOO_MIN_MAX_QUANTITIES_DIR . '/includes/woo-min-max-quantities-functions.php' );

        // Script Class to manage all scripts and styles
        include_once( WOO_MIN_MAX_QUANTITIES_DIR . '/includes/class-woo-min-max-qunatities-scripts.php' );
        $woo_min_max_quantities_scripts = new WOO_Min_Max_Quantities_Scripts();
        $woo_min_max_quantities_scripts->add_hooks();

        //Public Class to handles most of functionalities of public side
        require_once( WOO_MIN_MAX_QUANTITIES_DIR . '/includes/class-woo-min-max-qunatities-public.php' );
        $woo_min_max_quantities_public = new WOO_Min_Max_Quantities_Public();
        $woo_min_max_quantities_public->add_hooks();

        //Admin Pages Class for admin side
        require_once( WOO_MIN_MAX_QUANTITIES_ADMIN . '/class-woo-min-max-qunatities-admin.php' );
        $woo_min_max_quantities_admin = new WOO_Min_Max_Quantities_Admin();
        $woo_min_max_quantities_admin->add_hooks();

        //Settings Tab class for handling settings tab content
        require_once( WOO_MIN_MAX_QUANTITIES_ADMIN . '/class-woo-min-max-qunatities-admin-settings-tabs.php' );
        $woo_min_max_quantities_settings_tabs = new WOO_Min_Max_Quantities_Settings_Tabs();
        $woo_min_max_quantities_settings_tabs->add_hooks();
    } //end if to check class Woocommerce is exist or not
}

//end if to check plugin loaded is called or not

//check WPElite Plugins Updater is activated
if ( class_exists( 'WPElitePlugins_Upd_Admin' ) ) {

    // Plugin updates
    wpeliteplugins_queue_update( plugin_basename( __FILE__ ), WOO_MIN_MAX_QUANTITIES_PLUGIN_KEY );

    /**
     * Include Auto Updating Files
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.6
     */
    require_once( WPELITEPLUGINS_UPD_DIR . '/updates/class-plugin-update-checker.php' ); // auto updating

    $WPEliteWooMinMaxUpdateChecker = new WPElitePluginsUpdateChecker(
        'http://wpeliteplugins.com/Updates/woo-min-max-quantities/license-info.php', 
        __FILE__, 
        WOO_MIN_MAX_QUANTITIES_PLUGIN_KEY
    );

    /**
     * Auto Update
     * 
     * Get the license key and add it to the update checker.
     * 
     * @package WooCommerce - Minimum/Maximum Quantities
     * @since 1.0.6
     */
    function woo_min_max_quantities_add_secret_key( $query ) {

        $plugin_key = WOO_MIN_MAX_QUANTITIES_PLUGIN_KEY;

        $query['lickey'] = wpeliteplugins_get_plugin_purchase_code( $plugin_key );
        return $query;
    }

    $WPEliteWooMinMaxUpdateChecker->addQueryArgFilter( 'woo_min_max_quantities_add_secret_key' );
} // end check WPElitePlugins Updater is activated