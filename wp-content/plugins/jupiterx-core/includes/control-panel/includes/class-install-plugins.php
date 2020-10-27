<?php
/**
 * This class is responsible to manage all jupiters plugin.
 *
 * @package JupiterX_Core\Control_Panel
 */

class JupiterX_Control_Panel_Install_Plugins {

	protected $tgmpa;
	protected $api_url       = 'http://artbees.net/api/v2/';
	protected $theme_name    = 'JupiterX';
	private static $instance = null;

	/**
	 * Class constructor.
	 *
	 * @since 1.9.0
	 */
	public function __construct() {
		if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
			return;
		}

		$menu_items_access = get_site_option( 'menu_items' );
		if ( is_multisite() && ! isset( $menu_items_access['plugins'] ) && ! current_user_can( 'manage_network_plugins' ) ) {
			return;
		}

		$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();

		add_filter( 'jupiterx_control_panel_pane_install_plugins', [ $this, 'view' ] );

		add_action( 'wp_ajax_abb_get_plugins', [ $this, 'get_plugins_for_frontend' ] );
		add_action( 'wp_ajax_abb_deactivate_plugin', [ $this, 'deactivate' ] );
		add_action( 'wp_ajax_abb_update_plugin_checker', [ $this, 'plugin_conflict_checker' ] );
		add_action( 'wp_ajax_jupiterx_plugins_warning_dismiss', [ $this, 'plugin_warning_dismiss' ] );
	}

	/**
	 * Load view from Jupiter X Core plugin.
	 *
	 * @since 1.9.0
	 */
	public function view() {
		return jupiterx_core()->plugin_dir() . 'includes/control-panel/views/install-plugins.php';
	}

	/**
	 * Get class instance.
	 *
	 * @since 1.9.0
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Send a json list of plugins and their data and activation limit status for front-end usage.
	 *
	 * @since 1.9.0
	 */
	public function get_plugins_for_frontend() {
		$plugins = $this->get_plugins_from_api();
		$plugins = $this->update_plugins_status( $plugins );
		$limit   = $this->plugins_threshold();

		return wp_send_json( [ 'plugins' => $plugins, 'limit' => $limit ] );
	}

	/**
	 * Get plugins from Artbees API.
	 *
	 * @since 1.13.0
	 */
	public function get_plugins_from_api() {
		$response = wp_remote_get( 'https://themes.artbees.net/wp-json/plugins/v1/list?theme_name=jupiterx&order=ASC&orderby=menu_order' );

		$plugins = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $plugins ) ) {
			return [];
		}

		$plugins_list = [];

		foreach ( $plugins as $key => $plugin ) {
			$plugins_list[ $plugin['slug'] ] = $plugin;
		}

		$repo_plugins = array_filter( $plugins_list, function( $plugin ) {
			return isset( $plugin['source'] ) && 'wp-repo' === $plugin['source'];
		} );

		if ( ! empty( $repo_plugins ) ) {
			$repo_plugins = $this->get_wp_plugins_info( array_column( $repo_plugins, 'slug' ) );

			foreach ( $repo_plugins as $slug => $info ) {
				$plugins_list[ $slug ]['version'] = $info['version'];
				$plugins_list[ $slug ]['desc']    = $info['short_description'];
				$plugins_list[ $slug ]['img_url'] = isset( $info['icons']['1x'] ) ? $info['icons']['1x'] : $info['icons']['default'];
			}
		}

		return apply_filters( 'jupiterx_cp_plugins', $plugins_list );
	}

	/**
	 * Get WP plugins information from WP.org API.
	 *
	 * @since 1.9.0
	 * @since 1.13.0 Get multiple plugins info in a single request.
	 *
	 * @param string $slugs Plugin slugs.
	 */
	public function get_wp_plugins_info( $slugs = [] ) {
		if ( empty( $slugs ) ) {
			return [];
		}

		$wp_api = add_query_arg( [
			'action'  => 'plugin_information',
			'request' => [
				'slugs'  => $slugs,
				'fields' => [
					'icons',
					'short_description',
				],
			]
		], 'https://api.wordpress.org/plugins/info/1.2' );

		$plugins_info = json_decode( wp_remote_retrieve_body( wp_remote_get( $wp_api ) ), true );

		if ( isset( $plugins_info['error'] ) || empty( $plugins_info ) ) {
			return [];
		}

		return $plugins_info;
	}

	/**
	 * Check number of activated plugins in two different groups.
	 *
	 * @since 1.9.0
	 *
	 * @return bool $threshold Wether we are meeting threshold or not.
	 */
	public function plugins_threshold() {

		$plugins   = get_option('active_plugins');
		$threshold = [];

		if ( count( $plugins ) >= 20 ) {
			$threshold[] = 'num';
		}

		$sliders = [
			'LayerSlider/layerslider.php',
			'masterslider/masterslider.php',
			'revslider/revslider.php',
		];

		if ( count( array_intersect( $plugins, $sliders ) ) >= 1 ) {
			$threshold[] = 'sliders';
		}

		$jet_plugins = [
			'jet-blog/jet-blog.php',
			'jet-elements/jet-elements.php',
			'jet-engine/jet-engine.php',
			'jet-menu/jet-menu.php',
			'jet-popup/jet-popup.php',
			'jet-smart-filters/jet-smart-filters.php',
			'jet-tabs/jet-tabs.php',
			'jet-tricks/jet-tricks.php',
			'jet-woo-builder/jet-woo-builder.php',
		];

		if ( count( array_intersect( $plugins, $jet_plugins ) ) >= 4 ) {
			$threshold[] = 'jet-plugins';
		}

		return implode( ',', $threshold );
	}

	/**
	 * Update plugin information to add activation, installation and update status to plugin data.
	 * URL used to add activation/installation URL using TGMPA.
	 *
	 * @since 1.9.0
	 *
	 * @param array $plugins List of plugins.
	 */
	public function update_plugins_status( $plugins = [] ) {

		foreach ( $plugins as $slug => $plugin ) {

			if ( ! isset( $plugins[ $slug ]['basename'] ) || empty( $plugins[ $slug ]['basename'] ) ) {
				$plugins[ $slug ]['basename'] = $this->find_plugin_path( $slug );
			}

			$plugins[ $slug ]['update_needed']    = false;
			$plugins[ $slug ]['installed']        = false;
			$plugins[ $slug ]['active']           = false;
			$plugins[ $slug ]['network_active']   = false;
			$plugins[ $slug ]['install_disabled'] = false;
			$plugins[ $slug ]['is_pro']           = 'true' === $plugins[ $slug ]['pro'];
			$plugins[ $slug ]['server_version']   = $plugins[ $slug ]['version'];
			$plugins[ $slug ]['install_url']      = $this->get_tgmpa_action_url( $slug, 'install' );
			$plugins[ $slug ]['activate_url']     = $this->get_tgmpa_action_url( $slug, 'activate' );
			$plugins[ $slug ]['update_url']       = $this->get_tgmpa_action_url( $slug, 'update' );

			if ( is_plugin_active_for_network( $plugins[ $slug ]['basename'] )  ) {
				if ( ! current_user_can( 'manage_network_plugins' ) ) {
					unset( $plugins[ $slug ] );
					continue;
				}

				$plugins[ $slug ]['network_active'] = true;
			}

			if ( $this->tgmpa->is_plugin_active( $slug ) ) {
				$plugins[ $slug ]['active']    = true;
				$plugins[ $slug ]['installed'] = true;
			} elseif ( $this->tgmpa->is_plugin_installed( $slug ) ) {
				$plugins[ $slug ]['installed'] = true;
			}

			if ( ! jupiterx_is_pro() && 'true' === $plugins[ $slug ]['pro'] && ! $plugins[ $slug ]['installed'] ) {
				$plugins[ $slug ]['pro'] = true;
			} else {
				unset( $plugins[ $slug ]['pro'] );
			}

			if ( ! $plugins[ $slug ]['installed'] && ( is_multisite() && ! current_user_can( 'manage_network_plugins' ) ) ) {
				$plugins[ $slug ]['install_disabled'] = true;
			}

			if ( ! $plugins[ $slug ]['installed'] && ! $plugins[ $slug ]['install_disabled'] ) {
				$plugins[ $slug ]['url'] = $this->get_tgmpa_action_url( $slug, 'install' );
			} else {
				$plugins[ $slug ]['url'] = $this->get_tgmpa_action_url( $slug, 'activate' );
			}

			if ( $plugins[ $slug ]['installed'] ) {
				$plugin_data                 = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . $this->find_plugin_path( $slug ) );
				$plugins[ $slug ]['version'] = $plugin_data['Version'];

				if ( $this->tgmpa->does_plugin_have_update( $slug ) ) {
					$plugins[ $slug ]['update_needed'] = true;
					$plugins[ $slug ]['update_url'] = $this->get_tgmpa_action_url( $slug, 'update' );
				}
			}
		}

		return $plugins;
	}

	/**
	 * Get plugin basename by plugin slug.
	 * Works only for installed plugins.
	 *
	 * @since 1.9.0
	 *
	 * @param string $plugin_slug
	 */
	public function find_plugin_path( $plugin_slug = '' ) {

		$plugins = get_plugins();
		foreach ( $plugins as $plugin_address => $plugin_data ) {

			// Extract slug from address
			if ( strlen( $plugin_address ) == basename( $plugin_address ) ) {
				$slug = strtolower( str_replace( '.php', '', $plugin_address ) );
			} else {
				$slug = strtolower( str_replace( '/' . basename( $plugin_address ), '', $plugin_address ) );
			}
			// Check if slug exists
			if ( strtolower( $plugin_slug ) == $slug ) {
				return $plugin_address;
			}
		}

		return false;
	}

	/**
	 * Get installation/activation URL of a plugin using TGMPA.
	 *
	 * @since 1.9.0
	 *
	 * @param string $slug   Plugin slug.
	 * @param string $action install/activate
	 */
	public function get_tgmpa_action_url( $slug = '', $action = '' ) {
		if ( ! in_array( $action, [ 'install', 'activate', 'update' ], true ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Action is not valid.', 'jupiterx-core' ) ] );
		}

		$nonce_url = wp_nonce_url(
			add_query_arg(
				[
					'plugin'           => urlencode( $slug ),
					'tgmpa-' . $action => $action . '-plugin',
				],
				admin_url( 'themes.php?page=tgmpa-install-plugins' )
			),
			'tgmpa-' . $action,
			'tgmpa-nonce'
		);

		return $nonce_url;
	}

	/**
	 * Deactivate plugin using native WordPress functionalities.
	 *
	 * @since 1.9.0
	 */
	public function deactivate() {
		if ( ! isset( $_POST['slug'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Can\'t deactivate plugin', 'jupiterx-core' ) ] );
		}

		$plugin = $this->find_plugin_path( sanitize_text_field( $_POST['slug'] ) );

		if ( ! current_user_can( 'activate_plugin', $plugin ) ) {
			wp_send_json_error( esc_html__( 'Sorry, you are not allowed to deactivate this plugin.', 'jupiterx-core' ) );
		}

		deactivate_plugins( $plugin );

		wp_send_json_success( esc_html__( 'Deactivated Successfully.', 'jupiterx-core' ) );
	}

	/**
	 * Check for possible conflicts with Themes & Plugins for a specific plugin.
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	public function plugin_conflict_checker() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = wp_unslash( $_POST['plugin'] );

		if ( empty( $plugin_data ) ) {
			wp_json_send_error( __( 'Plugin data is missing', 'jupiterx' ) );
		}

		if ( 'wp-repo' === $plugin_data['version'] ) {
			$wp_updated_plugins = get_site_transient('update_plugins');

			if (
				empty( $wp_updated_plugins ) &&
				empty( $wp_updated_plugins->response[ $plugin_data['basename'] ] )
			) {
				wp_send_json_success();
			}

			$plugin_data['version'] = $wp_updated_plugins
				->response[ $plugin_data['basename'] ]
				->new_version;
		}

		$conflicts = jupiterx_get_plugin_conflicts( $plugin_data, get_plugins() );

		if ( count( $conflicts['plugins'] ) > 0 || count( $conflicts['themes'] ) > 0 ) {
			wp_send_json_error( $conflicts );
		}

		wp_send_json_success();
	}
}

JupiterX_Control_Panel_Install_Plugins::get_instance();
