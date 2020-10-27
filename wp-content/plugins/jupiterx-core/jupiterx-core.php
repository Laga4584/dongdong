<?php
/**
 * Plugin Name: Jupiter X Core
 * Plugin URI: https://jupiterx.com
 * Description: Adds core functionality to the Jupiter X theme.
 * Version: 1.18.2
 * Author: Artbees
 * Author URI: https://artbees.net
 * Text Domain: jupiterx-core
 * License: GPL2
 *
 * @package JupiterX_Core
 */

defined( 'ABSPATH' ) || die();

/**
 * Jupiter Core class.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
if ( ! class_exists( 'JupiterX_Core' ) ) {

	/**
	 * Jupiter Core class.
	 *
	 * @since 1.0.0
	 */
	class JupiterX_Core {

		/**
		 * Jupiter Core instance.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var JupiterX_Core
		 */
		private static $instance;

		/**
		 * The plugin version number.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $version;

		/**
		 * The plugin basename.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $plugin_basename;

		/**
		 * The plugin name.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $plugin_name;

		/**
		 * The plugin directory.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $plugin_dir;

		/**
		 * The plugin URL.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var string
		 */
		private static $plugin_url;

		/**
		 * The plugin assets URL.
		 *
		 * @since 1.2.0
		 * @access public
		 *
		 * @var string
		 */
		public static $plugin_assets_url;

		/**
		 * Returns JupiterX_Core instance.
		 *
		 * @since 1.0.0
		 *
		 * @return JupiterX_Core
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
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->define_constants();
			$this->load();
		}

		/**
		 * Defines constants used by the plugin.
		 *
		 * @since 1.0.0
		 */
		protected function define_constants() {
			$plugin_data = get_file_data( __FILE__, array( 'Plugin Name', 'Version' ), 'jupiterx-core' );

			self::$plugin_basename   = plugin_basename( __FILE__ );
			self::$plugin_name       = array_shift( $plugin_data );
			self::$version           = array_shift( $plugin_data );
			self::$plugin_dir        = trailingslashit( plugin_dir_path( __FILE__ ) );
			self::$plugin_url        = trailingslashit( plugin_dir_url( __FILE__ ) );
			self::$plugin_assets_url = trailingslashit( self::$plugin_url . 'assets' );
		}

		/**
		 * Loads the plugin.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function load() {
			$this->load_files( [
				'utilities/options',
				'admin/class-auto-updates',
				'extensions/class',
				'admin/class-notices',
			] );

			add_action( 'jupiterx_init', [ $this, 'init' ], 4 );
		}

		/**
		 * Initializes the plugin.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			add_action( 'admin_bar_menu', [ $this, 'extend_admin_bar_menu' ], 100 );
			add_action( 'init', [ $this, 'redirect_page' ] );
			add_action( 'admin_head', [ $this, 'inline_css' ] );
			add_action( 'admin_print_footer_scripts', [ $this, 'inline_js' ] );

			if ( defined( 'JUPITERX_OLD_CONTROL_PANEL' ) ) {
				add_action( 'admin_menu', [ $this, 'menus' ], 15 );
			}

			load_plugin_textdomain( 'jupiterx-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			if ( version_compare( JUPITERX_VERSION, '1.2.0', '>' ) ) {
				$this->load_files( [
					'compiler/functions',
					'compiler/class-compiler',
				] );
			}

			if ( version_compare( JUPITERX_VERSION, '1.4.1', '>' ) ) {
				$this->load_files( [
					'google-tag/functions',
				] );
			}

			if ( version_compare( JUPITERX_VERSION, '1.6.0', '>=' ) ) {
				$this->load_files( [
					'widgets/class',
					'widgets/functions',
					'admin/options',
				] );
			}

			// Load files.
			$this->load_files( [
				'customizer/functions',
				'parse-css/functions',
				'post-type/class',
				'dashboard/class',
				'control-panel/functions',
				'custom-fields/title-bar',
				'updater/functions',
				'widget-area/functions',
				'templates/class',
				'woocommerce/woocommerce-load-more',
				'utilities/load',
			] );

			if ( is_admin() ) {
				if ( ! defined( 'JUPITERX_OLD_CONTROL_PANEL' ) ) {
					$this->load_files( [
						'admin/site-health/site-health',
						'admin/tgmpa/tgmpa-plugin-list',
						'control-panel-2/class',
					] );
				}

				if ( ! class_exists( 'JupiterX_Update_Plugins' ) ) {
					$this->load_files( [
						'admin/update-plugins/class-update-plugins',
					] );
				}
			}

			$this->disable_admin_bar();

			/**
			 * Fires after all files have been loaded.
			 *
			 * @since 1.0.0
			 *
			 * @param JupiterX_Core
			 */
			do_action( 'jupiterx_core_init', $this );
		}

		/**
		 * Register admin menu pages.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 *
		 * @SuppressWarnings(PHPMD.NPathComplexity)
		 */
		public function menus() {
			if ( ! defined( 'JUPITERX_NAME' ) ) {
				return;
			}

			$menu_icon = '';

			if ( function_exists( 'jupiterx_is_white_label' ) ) {
				if ( jupiterx_is_white_label() && jupiterx_get_option( 'white_label_menu_icon' ) ) {
					$menu_icon = jupiterx_get_option( 'white_label_menu_icon' );
				}
			}

			$menu_name = JUPITERX_NAME;

			if ( function_exists( 'jupiterx_is_white_label' ) ) {
				if ( jupiterx_is_white_label() && jupiterx_get_option( 'white_label_text_occurence' ) ) {
					$menu_name = jupiterx_get_option( 'white_label_text_occurence' );
				}
			}

			add_menu_page( $menu_name, $menu_name, 'edit_theme_options', JUPITERX_SLUG, function () {
				include_once JUPITERX_CONTROL_PANEL_PATH . '/views/layout/master.php';
			}, $menu_icon, 3 );

			add_submenu_page( JUPITERX_SLUG, esc_html__( 'Control Panel', 'jupiterx-core' ), esc_html__( 'Control Panel', 'jupiterx-core' ) . $this->warning_badge(), 'edit_theme_options', JUPITERX_SLUG, function() {
				include_once JUPITERX_CONTROL_PANEL_PATH . '/views/layout/master.php';
			} );

			add_submenu_page( JUPITERX_SLUG, esc_html__( 'Customize', 'jupiterx-core' ), esc_html__( 'Customize', 'jupiterx-core' ), 'edit_theme_options', 'customize_theme', [ $this, 'redirect_page' ] );

			if ( function_exists( 'jupiterx_is_white_label' ) ) {
				if ( ! jupiterx_is_white_label() || ( jupiterx_is_white_label() && jupiterx_get_option( 'white_label_menu_help', true ) ) ) {
					add_submenu_page( JUPITERX_SLUG, esc_html__( 'Help', 'jupiterx-core' ), esc_html__( 'Help', 'jupiterx-core' ), 'edit_theme_options', 'jupiterx_help', [ $this, 'redirect_page' ] );
				}
			}

			if ( function_exists( 'jupiterx_is_pro' ) && ! jupiterx_is_pro() && ! jupiterx_is_premium() ) {
				add_submenu_page( JUPITERX_SLUG, esc_html__( 'Upgrade', 'jupiterx-core' ), '<i class="jupiterx-icon-pro"></i>' . esc_html__( 'Upgrade', 'jupiterx-core' ), 'edit_theme_options', 'jupiterx_upgrade', [ $this, 'redirect_page' ] );
			}

			remove_submenu_page( 'themes.php', JUPITERX_SLUG );
		}

		/**
		 * Add useful pages to admin toolbar.
		 *
		 * @since 1.16.0
		 *
		 * @param array $admin_bar The WordPress admin toolbar array.
		 *
		 * @return void
		 */
		public function extend_admin_bar_menu( $admin_bar ) {
			if ( is_admin() ) {
				return;
			}

			$admin_bar->add_menu( [
				'id'     => 'jupiterx-control-panel',
				'parent' => 'site-name',
				'title'  => esc_html__( 'Control Panel', 'jupiterx-core' ),
				'href'   => esc_url( admin_url( 'admin.php?page=jupiterx' ) ),
			]);
		}

		/**
		 * Inline styles for admin pages.
		 *
		 * @since 1.1.0
		 *
		 * @return void
		 *
		 * @todo Move to common admin CSS file.
		 */
		public function inline_css() {
			ob_start();
			?>
			<style type="text/css">
				ul#adminmenu a[href*='admin.php?page=jupiterx_upgrade'],
				ul#adminmenu a.jupiterx_upgrade_submenu_link {
					color: #e24a95;
				}

				ul#adminmenu a[href*='admin.php?page=jupiterx_upgrade'] i.jupiterx-icon-pro,
				ul#adminmenu a.jupiterx_upgrade_submenu_link i.jupiterx-icon-pro {
					position: relative;
					top: 2px;
					display: inline-block;
					width: 15px;
					height: 15px;
					margin-right: 5px;
					font-size: 15px;
				}

				ul#adminmenu a[href*='admin.php?page=jupiterx_upgrade'] i.jupiterx-icon-pro:before,
				ul#adminmenu a.jupiterx_upgrade_submenu_link i.jupiterx-icon-pro:before {
					font-weight: bold;
				}
			</style>
			<?php
			echo ob_get_clean(); // phpcs:ignore
		}

		/**
		 * Inline scripts for admin pages.
		 *
		 * @since 1.1.0
		 *
		 * @return void
		 *
		 * @todo Move to common admin JS file.
		 */
		public function inline_js() {
			ob_start();
			?>
			<script type="text/javascript">
				jQuery(document).ready( function($) {
					$( "ul#adminmenu a[href*='admin.php?page=jupiterx_help']" ).attr( 'target', '_blank' );
					$( "ul#adminmenu a[href*='admin.php?page=jupiterx_upgrade']" )
						.addClass('jupiterx_upgrade_submenu_link')
						.attr( 'target', '_blank' )
						.attr( 'href', 'https://themeforest.net/item/jupiter-multipurpose-responsive-theme/5177775?ref=artbees&utm_source=AdminSideBarUpgradeLink&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign' );
				});
			</script>
			<?php
			echo ob_get_clean(); // phpcs:ignore
		}

		/**
		 * Returns the version number of the plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function version() {
			return self::$version;
		}

		/**
		 * Returns the plugin basename.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function plugin_basename() {
			return self::$plugin_basename;
		}

		/**
		 * Returns the plugin name.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function plugin_name() {
			return self::$plugin_name;
		}

		/**
		 * Returns the plugin directory.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function plugin_dir() {
			return self::$plugin_dir;
		}

		/**
		 * Returns the plugin URL.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function plugin_url() {
			return self::$plugin_url;
		}

		/**
		 * Returns the plugin assets URL.
		 *
		 * @since 1.18.0
		 *
		 * @return string
		 */
		public function plugin_assets_url() {
			return self::$plugin_assets_url;
		}

		/**
		 * Loads all PHP files in a given directory.
		 *
		 * @since 1.0.0
		 *
		 * @param string $directory_name The directory name to load the files.
		 */
		public function load_directory( $directory_name ) {
			$path       = trailingslashit( $this->plugin_dir() . 'includes/' . $directory_name );
			$file_names = glob( $path . '*.php' );
			foreach ( $file_names as $filename ) {
				if ( file_exists( $filename ) ) {
					require_once $filename;
				}
			}
		}

		/**
		 * Loads specified PHP files from the plugin includes directory.
		 *
		 * @since 1.0.0
		 *
		 * @param array $file_names The names of the files to be loaded in the includes directory.
		 */
		public function load_files( $file_names = array() ) {
			foreach ( $file_names as $file_name ) {
				$path = $this->plugin_dir() . 'includes/' . $file_name . '.php';

				if ( file_exists( $path ) ) {
					require_once $path;
				}
			}
		}

		/**
		 * Redirect an admin page.
		 *
		 * @since 1.0.0
		 */
		public function redirect_page() {
			// phpcs:disable
			if ( ! isset( $_GET['page'] ) ) {
				return;
			}

			if ( 'customize_theme' === $_GET['page'] ) {
				wp_redirect( admin_url( 'customize.php' ) );
				exit;
			}

			if ( 'jupiterx_upgrade' === $_GET['page'] ) {
				wp_redirect( admin_url() );
				exit;
			}

			if ( 'jupiterx_help' === $_GET['page'] ) {
				wp_redirect( 'https://themes.artbees.net/support/jupiterx/' );
				exit;
			}
			// phpcs:enable
		}

		/**
		 * Disable admin bar in Elementor preview.
		 *
		 * Admin bar causes spacing issues. Elementor added the same codes but it's not working correctly.
		 * When it's fixed, the codes will be removed.
		 *
		 * @since 1.0.0
		 */
		private function disable_admin_bar() {
			if ( ! empty( $_GET['elementor-preview'] ) ) { // phpcs:ignore
				add_filter( 'show_admin_bar', '__return_false' );
			}
		}

		/**
		 * Get warining badge for premium users.
		 *
		 * @since 1.2.0
		 *
		 * @return string
		 */
		private function warning_badge() {
			if (
				! function_exists( 'jupiterx_is_registered' ) ||
				! function_exists( 'jupiterx_is_premium' )
			) {
				return '';
			}

			if ( ! jupiterx_is_premium() ) {
				return '';
			}

			if ( jupiterx_is_registered() ) {
				return '';
			}

			return sprintf(
				' <img class="jupiterx-premium-warning-badge" src="%1$s" alt="%2$s" width="16" height="16">',
				self::$plugin_assets_url . 'images/warning-badge.svg',
				esc_html__( 'Activate Product', 'jupiterx-core' )
			);
		}
	}
}

/**
 * Returns the Jupiter Core application instance.
 *
 * @since 1.0.0
 *
 * @return JupiterX_Core
 */
function jupiterx_core() {
	return JupiterX_Core::get_instance();
}

/**
 * Initializes the Jupiter Core application.
 *
 * @since 1.0.0
 */
jupiterx_core();
