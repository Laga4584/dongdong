<?php
/**
 * This class is responsible to managing all JupiterX plugins.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 *
 * @package JupiterX_Core\Control_Panel_2
 */

class JupiterX_Core_Control_Panel_Install_Plugins {

	/**
	 * TGMPA Instance
	 *
	 * @var object
	 */
	protected $tgmpa;

	/**
	 * Artbees API.
	 *
	 * @var string
	 */
	protected $api_url = 'http://artbees.net/api/v2/';

	/**
	 * Class constructor.
	 *
	 * @since 1.18.0
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

		add_action( 'wp_ajax_jupiterx_core_cp_get_plugins', [ $this, 'get_plugins_for_frontend' ] );
	}

	/**
	 * Send a json list of plugins and their data and activation limit status for front-end usage.
	 *
	 * @since 1.18.0
	 */
	public function get_plugins_for_frontend() {
		$plugins = jupiterx_core_get_plugins_from_api();

		if ( isset( $plugins['raven'] ) ) {
			unset( $plugins['raven'] );
		}

		$plugins = jupiterx_core_update_plugins_status( $plugins );

		return wp_send_json( [
			'plugins' => $plugins,
			'bulk_actions' => $this->get_plugin_bulk_actions( $plugins ),
		] );
	}

	/**
	 * Get Plugin Bulk Actions.
	 *
	 * @since 1.18.0
	 *
	 * @param array $plugins Plugins list.
	 *
	 * @return array
	 */
	public function get_plugin_bulk_actions( $plugins ) {
		return [
			'activate_required_plugins' => [
				'url' => admin_url( 'plugins.php' ),
				'action' => 'activate-selected',
				'action2' => -1,
				'_wpnonce' => wp_create_nonce( 'bulk-plugins' ),
				'checked' => $this->get_required_plugins_slug( $plugins, 'basename' ),
			],
			'install_required_plugins' => [
				'url' => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
				'action' => 'tgmpa-bulk-install',
				'action2' => -1,
				'_wpnonce' => wp_create_nonce( 'bulk-plugins' ),
				'tgmpa-page' => 'tgmpa-install-plugins',
				'plugin' => $this->get_required_plugins_slug( $plugins, 'slug' ),
			],
		];
	}

	/**
	 * Get plugin slugs for bulk action.
	 *
	 * @since 1.18.0
	 *
	 * @param array $plugins Plugins list.
	 * @param string $field Plugin slug or basename.
	 *
	 * @return array
	 */
	private function get_required_plugins_slug( $plugins, $field ) {
		$slugs = [];

		if ( ! is_array( $plugins ) ) {
			return slugs;
		}

		foreach ( $plugins as $plugin ) {
			if ( 'true' === $plugin['required'] ) {
				$slugs[] = $plugin[ $field ];
			}
		}

		return $slugs;
	}
}

new JupiterX_Core_Control_Panel_Install_Plugins();
