<?php
/**
 * This class handles pro version functionalities.
 *
 * @package JupiterX\Pro
 *
 * @since 1.6.0
 */

if ( ! class_exists( 'JupiterX_Pro' ) ) {
	/**
	 * Jupiter Pro class.
	 *
	 * @since 1.6.0
	 */
	class JupiterX_Pro {

		/**
		 * Jupiter Pro instance.
		 *
		 * @since 1.6.0
		 *
		 * @access private
		 * @var JupiterX_Pro
		 */
		private static $instance;

		/**
		 * The plugin status.
		 *
		 * @since 1.6.0
		 *
		 * @access private
		 * @var boolean
		 */
		private $active = false;

		/**
		 * Returns JupiterX_Pro instance.
		 *
		 * @since 1.6.0
		 *
		 * @return JupiterX_Pro
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.6.0
		 */
		public function __construct() {
			$this->define_constants();

			// To ensure utility functions are loaded first.
			add_action( 'jupiterx_before_load_api', function() {
				$this->load_files( [
					'api/functions',
				] );

				if ( ! jupiterx_is_registered() ) {
					add_action( 'admin_notices', [ $this, 'api_key_notice' ] );
					return;
				}

				$this->init();
			} );
		}

		/**
		 * Defines constants used by the plugin.
		 *
		 * @since 1.6.0
		 */
		public function define_constants() {
			// Define paths.
			define( 'JUPITERX_PRO_PATH', JUPITERX_PATH . 'pro/' );

			// Define urls.
			define( 'JUPITERX_PRO_URL', JUPITERX_URL . 'pro/' );
		}

		/**
		 * Admin API key notice.
		 *
		 * @since 1.6.0
		 */
		public function api_key_notice() {
			if ( current_user_can( 'manage_options' ) ) {
				?>
				<div class="notice notice-error">
					<p>
						<?php
						printf(
							/* translators: Link to Control Panel page */
							esc_html__( 'Jupiter X - Please go to %s and enter your API key to complete registration and unlock its features.', 'jupiterx' ),
							'<a href="' . esc_url( admin_url( 'admin.php?page=jupiterx' ) ) . '">' . esc_html__( 'Control Panel', 'jupiterx' ) . '</a>'
						);
						?>
					</p>
				</div>
				<?php
			}
		}

		/**
		 * Initializes the plugin.
		 *
		 * @since 1.6.0
		 */
		public function init() {
			$this->active = true;

			$this->load_files( [
				'control-panel/functions',
				'customizer/functions',
			] );

			/**
			 * Fires after all files have been loaded.
			 *
			 * @since 1.6.0
			 *
			 * @param JupiterX_Pro
			 */
			do_action( 'jupiterx_pro_init', $this );
		}

		/**
		 * Loads specified PHP files from the plugin includes directory.
		 *
		 * @since 1.6.0
		 *
		 * @param array $file_names The names of the files to be loaded in the includes directory.
		 */
		public function load_files( $file_names = array() ) {
			foreach ( $file_names as $file_name ) {
				$path = JUPITERX_PRO_PATH . 'includes/' . $file_name . '.php';

				if ( file_exists( $path ) ) {
					require_once $path;
				}
			}
		}

		/**
		 * Get plugin status.
		 *
		 * @since 1.6.0
		 *
		 * @return boolean
		 */
		public function is_active() {
			return $this->active;
		}
	}
}

if ( function_exists( 'jupiterx_pro' ) ) {
	add_action( 'jupiterx_init', 'jupiterx_deprecated_pro_plugin', 15 );
	/**
	 * Generate admin notice for deprecated pro plugin.
	 *
	 * @since 1.14.0
	 */
	function jupiterx_deprecated_pro_plugin() {
		$plugin_instance = jupiterx_pro();

		// Remove generated notices by the pro plugin.
		remove_action( 'admin_notices', [ $plugin_instance, 'theme_version_notice' ] );
		remove_action( 'admin_notices', [ $plugin_instance, 'api_key_notice' ] );

		add_action( 'admin_notices', function() {
			if ( current_user_can( 'update_plugins' ) ) {
				$plugin = 'jupiterx-pro/jupiterx-pro.php';
				$plugin = str_replace( '\/', '%2F', $plugin );
				$action = 'deactivate';
				$url    = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
				$url    = wp_nonce_url( $url, $action . '-plugin_' . $plugin );

				?>
				<div class="notice notice-error">
					<p>
						<?php
						printf(
							/* translators: Theme name */
							esc_html__( '%1$s - We have detected a deprecated %2$s plugin which causes conflict in your JupiterX theme. We strongly recommend you to remove the plugin now.', 'jupiterx' ),
							'<strong>' . esc_html__( 'Jupiter X', 'jupiterx' ) . '</strong>',
							'<strong>' . esc_html__( 'Jupiter X Pro', 'jupiterx' ) . '</strong>'
						);
						?>
					</p>
					<p><a class="button button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Deactivate JupiterX Pro Plugin Now', 'jupiterx' ); ?></a></p>
				</div>
				<?php
			}
		} );
	}
} else {
	/**
	 * Returns the Jupiter Pro application instance.
	 *
	 * @since 1.6.0
	 *
	 * @return JupiterX_Pro
	 */
	function jupiterx_pro() {
		return JupiterX_Pro::get_instance();
	}

	/**
	 * Initializes the Jupiter Pro application.
	 *
	 * @since 1.6.0
	 */
	add_action( 'jupiterx_init', function() {
		jupiterx_pro();
	}, 4 );
}
