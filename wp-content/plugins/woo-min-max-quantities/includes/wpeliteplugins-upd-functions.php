<?php

/**
 * Load installer for the WPElite Plugins Updater.
 * @return $api Object
 */
if (!class_exists('WPElitePlugins_Upd_Admin') && !function_exists('wpeliteplugins_updater_install')) {

    function wpeliteplugins_updater_install($api, $action, $args) {
       
        $download_url = 'https://wpeliteplugins.s3.amazonaws.com/plugins/WPELITEPUPD/wpeliteplugins-updater.zip';

        if ('plugin_information' != $action ||
                false !== $api ||
                !isset($args->slug) ||
                'wpeliteplugins-updater' != $args->slug
        )
            return $api;

        $api = new stdClass();
        $api->name = 'WPElite Plugins Updater';
        $api->version = '1.0.0';
        $api->download_link = esc_url($download_url);

        return $api;
    }

    add_filter('plugins_api', 'wpeliteplugins_updater_install', 10, 3);
}

/**
 * WPElite Plugins Updater Installation Prompts
 */
if (!class_exists('WPElitePlugins_Upd_Admin') && !function_exists('wpeliteplugins_updater_notice')) {

    /**
     * Display a notice if the "WPElite Plugisn Updater" plugin hasn't been installed.
     * @return void
     */
    function wpeliteplugins_updater_notice() {

        if ( is_plugin_active( 'wpeliteplugins-updater/wpeliteplugins-updater.php' ) )
            return;        

        //initilize variables
        $message = $dismiss_url = $dismiss_link = '';

        $dismiss_notice = get_site_option('dismiss_install_wpeliteplugins_notice');

        if ( !$dismiss_notice ) { //if notice dismissed
            
            $slug = 'wpeliteplugins-updater';
            $install_url = wp_nonce_url( 
                self_admin_url('update.php?action=install-plugin&plugin=' . $slug), 
                'install-plugin_' . $slug
            );
            $message = '<a href="' . esc_url( $install_url ) . '">Install the WPElitePlugins Updater plugin</a> to get feature updates and premium support for your WPElitePlugins.';
            $dismiss_url = add_query_arg(
                'action', 'install-wpeliteplugins-dismiss', 
                add_query_arg(
                    'nonce', 
                    wp_create_nonce('install-wpeliteplugins-dismiss')
                )
            );
            $dismiss_link = '<p class="alignright"><a href="' . esc_url( $dismiss_url ) . '">' . 'Dismiss' . '</a></p>';
        }

        $is_downloaded = false;
        $plugins = array_keys(get_plugins());

        foreach ($plugins as $plugin) {
            if ( strpos( $plugin, 'wpeliteplugins-updater.php') !== false ) {
                $is_downloaded = true;
                $activate_url = add_query_arg(
                    array(
                        'action' => 'activate',
                        'plugin' => urlencode('wpeliteplugins-updater/wpeliteplugins-updater.php'),
                        'plugin_status' => 'all',
                        'paged' => 1,
                        '_wpnonce' => urlencode(wp_create_nonce('activate-plugin_wpeliteplugins-updater/wpeliteplugins-updater.php') )
                    ),
                    'plugins.php' 
                );               
                $message = '<a href="' . esc_url( network_admin_url( $activate_url ) ) . '">Activate the WPElitePlugins Updater plugin</a> to get feature updates and premium support for your WPElitePlugins.';
                $dismiss_link = '';
            }
        }

        if ( !empty( $message ) ) {//If message is not empty
            echo '<div class="notice notice-success is-dismissible">';
                echo '<p class="alignleft">' . $message . '</p>';
                echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                echo  $dismiss_link;
                echo '<div class="clear"></div>';
            echo '</div>' . "\n";
        }
    }

    add_action('admin_notices', 'wpeliteplugins_updater_notice');

    /**
     * Dismiss Install WPElitePlugins notification
     * 
     */
    function wpeliteplugins_updater_dismiss_install_notification() {

        if (isset($_GET['action']) && ( 'install-wpeliteplugins-dismiss' == $_GET['action'] ) && isset($_GET['nonce']) && check_admin_referer('install-wpeliteplugins-dismiss', 'nonce')) {

            update_site_option('dismiss_install_wpeliteplugins_notice', true);
            $redirect_url = remove_query_arg('action', remove_query_arg('nonce', $_SERVER['REQUEST_URI']));
            wp_safe_redirect($redirect_url);
            exit;
        }
    }

    add_action('admin_init', 'wpeliteplugins_updater_dismiss_install_notification');
}