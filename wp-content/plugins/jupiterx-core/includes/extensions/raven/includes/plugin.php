<?php
/**
 * Add extensions main class.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;
use Elementor\Utils as ElementorUtils;
use Elementor\Settings;

/**
 * Plugin class.
 *
 * @since 1.0.0
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
final class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Plugin
	 */
	public static $instance;

	/**
	 * Modules.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var object
	 */
	public $modules = [];

	/**
	 * Core Modules.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @var array
	 */
	public $core_modules = [];

	/**
	 * The plugin name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_name;

	/**
	 * The plugin version number.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_version;

	/**
	 * The minimum Elementor version number required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $minimum_elementor_version = '2.0.0';

	/**
	 * The plugin directory.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_path;

	/**
	 * The plugin URL.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_url;

	/**
	 * The plugin assets URL.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public static $plugin_assets_url;

	/**
	 * Disables class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'jupiterx-core' ), '1.0.0' );
	}

	/**
	 * Disables unserializing of the class.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'jupiterx-core' ), '1.0.0' );
	}

	/**
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
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
	 * @access private
	 */
	private function __construct() {
		add_action( 'plugins_loaded', [ $this, 'check_elementor_version' ] );
	}

	/**
	 * Checks Elementor version compatibility.
	 *
	 * First checks if Elementor is installed and active,
	 * then checks Elementor version compatibility.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function check_elementor_version() {
		if ( ! class_exists( '\\JupiterX_Core\\Raven\\Utils' ) ) {
			if ( empty( self::$plugin_path ) ) {
				self::$plugin_path = trailingslashit( plugin_dir_path( JUPITERX_CORE_RAVEN__FILE__ ) );
			}
			// Requires Utils class.
			require_once self::$plugin_path . 'includes/utils.php';
		}

		if ( ! class_exists( 'Elementor\Plugin' ) ) {
			return;
		}

		// Check for the minimum required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::$minimum_elementor_version, '>=' ) ) {
			if ( current_user_can( 'update_plugins' ) ) {
				add_action( 'admin_notices',
				[ $this, 'admin_notice_minimum_elementor_version' ] );
			}
			// don't go further.
			return;
		}

		spl_autoload_register( [ $this, 'autoload' ] );

		$this->define_constants();
		$this->add_hooks();
	}

	/**
	 * Displays notice on the admin dashboard if Elementor version is lower than the
	 * required minimum.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security
		}

		$message = sprintf(
			'<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">'
			/* translators: 1: Plugin name 2: Elementor */
			. esc_html__( '%1$s requires version %3$s or greater of %2$s plugin.', 'jupiterx-core' )
			. '</span>',
			'<strong>' . esc_html__( 'JupiterX Core', 'jupiterx-core' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'jupiterx-core' ) . '</strong>',
			self::$minimum_elementor_version
		);

		$file_path   = 'elementor/elementor.php';
		$update_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );

		$message .= sprintf(
			'<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">' .
			'<a class="button-primary" href="%1$s">%2$s</a></span>',
			$update_link, esc_html__( 'Update Elementor Now', 'jupiterx-core' )
		);

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	}

	/**
	 * Autoload classes based on namesapce.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $class Name of class.
	 */
	public function autoload( $class ) {

		// Return if Raven name space is not set.
		if ( class_exists( $class ) || 0 !== stripos( $class, __NAMESPACE__ ) ) {
			return;
		}

		/**
		 * Prepare filename.
		 *
		 * @todo Refactor to use preg_replace.
		 */
		$filename = str_replace( __NAMESPACE__ . '\\', '', $class );
		$filename = str_replace( '\\', DIRECTORY_SEPARATOR, $filename );
		$filename = str_replace( '_', '-', $filename );
		$filename = self::$plugin_path . 'includes/' . strtolower( $filename ) . '.php';

		// Return if file is not found.
		if ( ! file_exists( $filename ) ) {
			return;
		}

		include $filename;
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_constants() {
		$plugin_data = get_file_data( JUPITERX_CORE_RAVEN__FILE__, array( 'Plugin Name', 'Version' ), 'jupiterx-core' );

		self::$plugin_name       = array_shift( $plugin_data );
		self::$plugin_version    = array_shift( $plugin_data );
		self::$plugin_path       = trailingslashit( plugin_dir_path( JUPITERX_CORE_RAVEN__FILE__ ) );
		self::$plugin_url        = trailingslashit( plugin_dir_url( JUPITERX_CORE_RAVEN__FILE__ ) );
		self::$plugin_assets_url = trailingslashit( self::$plugin_url . 'assets' );
	}

	/**
	 * Adds required hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function add_hooks() {
		add_action( 'elementor/init', [ $this, 'init' ], 0 );
		add_action( 'elementor/editor/footer', [ $this, 'editor_templates' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ], 15 );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_enqueue_styles' ], 0 );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_enqueue_scripts' ], 0 );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'preview_enqueue_styles' ], 0 );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'frontend_register_styles' ], 0 );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'frontend_enqueue_styles' ], 0 );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'frontend_register_scripts' ], 0 );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'frontend_enqueue_scripts' ], 0 );
		add_action( 'elementor/theme/register_locations', [ $this, 'jupiterx_register_elementor_locations' ] );

		add_action( 'wp_ajax_raven_sync_libraries', [ $this, 'sync_libraries' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ] );

		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 20 );
		}
	}

	/**
	 * Add support elementor theme locations.
	 *
	 * @since 1.0.0
	 *
	 * @param object $elementor_theme_manager Elementor theme manager object.
	 * @access public
	 */
	public function jupiterx_register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location( 'header' );
		$elementor_theme_manager->register_location( 'footer' );

		if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
			$elementor_theme_manager->register_location( 'single' );
		}
	}


	/**
	 * Register controls with Elementor by raven prefix.
	 * raven-loop-animation, jupiterx-core-raven-parallax-scroll, ...
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $controls_manager The controls manager.
	 */
	public function register_controls( $controls_manager ) {
		/**
		 * List of all controls and group controls.
		 * Credit: goo.gl/hkvhZJ - preg_grep solution
		 */
		$controls       = preg_grep( '/^((?!index.php).)*$/', glob( self::$plugin_path . '/includes/controls/*.php' ) );
		$group_controls = preg_grep( '/^((?!index.php).)*$/', glob( self::$plugin_path . '/includes/controls/group/*.php' ) );

		// Register controls.
		foreach ( $controls as $control ) {

			// Prepare control name.
			$control_name = basename( $control, '.php' );
			$control_name = str_replace( '-', '_', $control_name );

			// Prepare class name.
			$class_name = str_replace( '-', '_', $control_name );
			$class_name = __NAMESPACE__ . '\Controls\\' . $class_name;

			// Register now.
			$controls_manager->register_control( 'raven_' . $control_name, new $class_name() );
		}

		// Register group controls.
		foreach ( $group_controls as $control ) {

			// Prepare control name.
			$control_name = basename( $control, '.php' );

			// Prepare class name.
			$class_name = str_replace( '-', '_', $control_name );
			$class_name = __NAMESPACE__ . '\Controls\Group\\' . $class_name;

			// Register now.
			$controls_manager->add_group_control( 'raven-' . $control_name, new $class_name() );
		}

		$this->jupiterx_icons( $controls_manager );
	}

	/**
	 * Adds Jupiter X icon to existing icon control in Elementor
	 *
	 * @since 1.0.0
	 *
	 * @param object $controls_manager Control manager instance.
	 *
	 * @return void
	 */
	public function jupiterx_icons( $controls_manager ) {

		$elementor_icons = $controls_manager->get_control( 'icon' )->get_settings( 'options' );

		$jupiterx_icons = array_merge(
			$elementor_icons,
			array(
				'jupiterx-icon-creative-market'  => 'creative-market',
				'jupiterx-icon-long-arrow'       => 'long-arrow',
				'jupiterx-icon-search-1'         => 'search-1',
				'jupiterx-icon-search-2'         => 'search-2',
				'jupiterx-icon-search-3'         => 'search-3',
				'jupiterx-icon-search-4'         => 'search-4',
				'jupiterx-icon-share-email'      => 'share-email',
				'jupiterx-icon-shopping-cart-1'  => 'shopping-cart-1',
				'jupiterx-icon-shopping-cart-2'  => 'shopping-cart-2',
				'jupiterx-icon-shopping-cart-3'  => 'shopping-cart-3',
				'jupiterx-icon-shopping-cart-4'  => 'shopping-cart-4',
				'jupiterx-icon-shopping-cart-5'  => 'shopping-cart-5',
				'jupiterx-icon-shopping-cart-6'  => 'shopping-cart-6',
				'jupiterx-icon-shopping-cart-7'  => 'shopping-cart-7',
				'jupiterx-icon-shopping-cart-8'  => 'shopping-cart-8',
				'jupiterx-icon-shopping-cart-9'  => 'shopping-cart-9',
				'jupiterx-icon-shopping-cart-10' => 'shopping-cart-10',
				'jupiterx-icon-zillow'           => 'zillow',
				'jupiterx-icon-zomato'           => 'zomato',
			)
		);

		$controls_manager->get_control( 'icon' )->set_settings( 'options', $jupiterx_icons );
	}

	/**
	 * Register modules.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_modules() {
		$modules = [
			'custom-scripts',
			'alert',
			'button',
			'categories',
			'column',
			'countdown',
			'counter',
			'divider',
			'flex-spacer',
			'forms',
			'heading',
			'icon',
			'image',
			'image-gallery',
			'nav-menu',
			'photo-album',
			'photo-roller',
			'posts',
			'post-content',
			'post-comments',
			'post-meta',
			'products',
			'search-form',
			'shopping-cart',
			'site-logo',
			'tabs',
			'video',
			'breadcrumbs',
		];

		foreach ( $modules as $module_name ) {
			// Prepare class name.
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '_', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\Modules\\' . $class_name . '\Module';

			// Register.
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::get_instance();
			}
		}

		$core_modules = [
			'template',
			'compatibility',
			'dynamic-tags',
		];

		if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
			$core_modules[] = 'library';
		}

		foreach ( $core_modules as $core_module_name ) {
			// Prepare class name.
			$class_name = str_replace( '-', ' ', $core_module_name );
			$class_name = str_replace( ' ', '_', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\Core\\' . $class_name . '\Module';

			// Register.
			$this->core_modules[ $core_module_name ] = new $class_name();
		}
	}

	/**
	 * Adds actions after Elementor init.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		// Register modules.
		$this->register_modules();

		// Add this category, after basic category.
		Elementor::$instance->elements_manager->add_category(
			'jupiterx-core-raven-elements',
			[
				'title' => __( 'Jupiter X Elements', 'jupiterx-core' ),
				'icon'  => 'fa fa-plug',
			],
			1
		);

		// Requires Utils class.
		require_once self::$plugin_path . '/includes/utils.php';

		do_action_deprecated( 'raven/init', [], '1.18.0', 'jupiterx_core_raven_init' );

		do_action( 'jupiterx_core_raven_init' );
	}

	/**
	 * Print editor templates.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function editor_templates() {
		require_once self::$plugin_path . '/includes/editor-templates/templates.php';
	}

	/**
	 * Enqueue styles.
	 *
	 * Enqueue all the editor styles.
	 *
	 * Fires after Elementor editor styles are enqueued.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_enqueue_styles() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_style(
			'jupiterx-core-raven-icons',
			self::$plugin_assets_url . 'lib/raven-icons/css/raven-icons' . $suffix . '.css',
			[],
			self::$plugin_version
		);

		wp_enqueue_style(
			'jupiterx-core-raven-editor',
			self::$plugin_assets_url . 'css/editor' . $suffix . '.css',
			[],
			self::$plugin_version
		);

		wp_enqueue_style(
			'jupiterx-icons',
			self::$plugin_assets_url . 'css/icons' . $suffix . '.css',
			[],
			self::$plugin_version
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * Enqueue all the editor scripts.
	 *
	 * Fires after Elementor editor scripts are enqueued.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_enqueue_scripts() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_script(
			'jupiterx-core-raven-editor',
			self::$plugin_assets_url . 'js/editor' . $suffix . '.js',
			[ 'jquery' ],
			self::$plugin_version,
			true
		);
	}

	/**
	 * Preview styles.
	 *
	 * Preview all the preview styles.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function preview_enqueue_styles() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_style(
			'jupiterx-core-raven-icons',
			self::$plugin_assets_url . 'lib/raven-icons/css/raven-icons' . $suffix . '.css',
			[],
			self::$plugin_version
		);
	}

	/**
	 * Registers styles.
	 *
	 * Registers all the front-end styles.
	 *
	 * Fires after Elementor front-end styles are registered.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function frontend_register_styles() {
		$rtl    = is_rtl() ? '-rtl' : '';
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_register_style(
			'jupiterx-core-raven-frontend',
			self::$plugin_assets_url . 'css/frontend' . $rtl . $suffix . '.css',
			[ 'font-awesome' ],
			self::$plugin_version
		);
	}

	/**
	 * Enqueue all the front-end styles.
	 *
	 * Fires after Elementor front-end styles are enqueued.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function frontend_enqueue_styles() {
		wp_enqueue_style( 'jupiterx-core-raven-frontend' );
	}


	/**
	 * Registers all the front-end scripts.
	 *
	 * Fires after Elementor front-end scripts are registered.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function frontend_register_scripts() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_register_script(
			'jupiterx-core-raven-url-polyfill',
			self::$plugin_assets_url . 'lib/url-polyfill/url-polyfill' . $suffix . '.js',
			[ 'jquery' ],
			'1.1.7',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-parallax-scroll',
			self::$plugin_assets_url . 'lib/parallax-scroll/jquery.parallax-scroll' . $suffix . '.js',
			[ 'jquery' ],
			'1.0.0',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-countdown',
			self::$plugin_assets_url . 'lib/countdown/jquery.countdown' . $suffix . '.js',
			[ 'jquery' ],
			'2.2.0',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-enquire',
			self::$plugin_assets_url . 'lib/enquire/enquire' . $suffix . '.js',
			[],
			'2.1.2',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-savvior',
			self::$plugin_assets_url . 'lib/savvior/savvior' . $suffix . '.js',
			[ 'raven-enquire' ],
			'0.6.0',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-anime',
			self::$plugin_assets_url . 'lib/anime/anime' . $suffix . '.js',
			[],
			'2.2.0',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-stack-motion-effects',
			self::$plugin_assets_url . 'lib/stack-motion-effects/stack-motion-effects' . $suffix . '.js',
			[],
			'1.0.0',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-object-fit',
			self::$plugin_assets_url . 'lib/object-fit/object-fit' . $suffix . '.js',
			[ 'jquery' ],
			'2.1.1',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-smartmenus',
			self::$plugin_assets_url . 'lib/smartmenus/jquery.smartmenus' . $suffix . '.js',
			[ 'jquery' ],
			'1.1.0',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-pagination',
			self::$plugin_assets_url . 'lib/pagination/jquery.twbsPagination' . $suffix . '.js',
			[ 'jquery' ],
			'1.4.2',
			true
		);

		wp_register_script(
			'jupiterx-core-raven-frontend',
			self::$plugin_assets_url . 'js/frontend' . $suffix . '.js',
			[ 'jquery', 'wp-util' ],
			self::$plugin_version,
			true
		);
	}

	/**
	 * Enqueue all the front-end scripts.
	 *
	 * Fires after Elementor front-end scripts are enqueued.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function frontend_enqueue_scripts() {
		wp_enqueue_script( 'jupiterx-core-raven-frontend' );

		foreach ( $this->modules as $module_name => $module_instance ) {
			$translations = $module_instance->translations();

			if ( empty( $translations ) ) {
				continue;
			}

			$module_name = str_replace( '-', ' ', $module_name );
			$module_name = str_replace( ' ', '', ucwords( $module_name ) );

			wp_localize_script(
				'jupiterx-core-raven-frontend',
				'raven' . $module_name . 'Translations',
				$translations
			);
		}
	}

	/**
	 * Register raven admin scripts.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_admin_scripts() {
		$suffix = ElementorUtils::is_script_debug() ? '' : '.min';

		wp_enqueue_script(
			'jupiterx-core-raven-admin',
			self::$plugin_assets_url . 'js/admin' . $suffix . '.js',
			[ 'jquery' ],
			self::$plugin_version,
			true
		);
	}

	/**
	 * Add Raven tab in Elementor Settings page.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $settings Settings.
	 */
	public function register_admin_fields( $settings ) {
		$settings->add_tab(
			'raven', [
				'label' => __( 'Jupiter X', 'jupiterx-core' ),
			]
		);

		$settings->add_section( 'raven', 'raven_google_api_key', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'Google API Key', 'jupiterx-core' ) . '</h2>';
			},
			'fields' => [
				'raven_google_api_key' => [
					'label' => __( 'API Key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
						/* translators: %s: Google Developer Console URL  */
						'desc' => sprintf( __( 'This API key will be used for maps, places. <a href="%s" target="_blank">Get your API key</a>.', 'jupiterx-core' ), 'https://console.developers.google.com' ),
					],
				],
			],
		] );
	}

	/**
	 * Sync libraries.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return void
	 */
	public function sync_libraries() {
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( empty( $_POST['library'] ) ) {
			wp_send_json_error( __( 'library field is missing', 'jupiterx-core' ) );
		}

		// phpcs:ignore WordPress.Security.NonceVerification
		$library = sanitize_text_field( wp_unslash( $_POST['library'] ) );

		if ( 'presets' === $library && isset( $this->core_modules['preset'] ) ) {
			$cached_elements = get_transient( 'raven_presets_elements_cached' );

			delete_transient( 'raven_presets_elements' );
			delete_transient( 'raven_presets_elements_cached' );

			if ( false === $cached_elements ) {
				wp_send_json_success();
			}

			foreach ( $cached_elements as $element ) {
				delete_transient( 'raven_preset_' . $element );
			}

			wp_send_json_success();
		}

		wp_send_json_error( __( 'invalid library value received', 'jupiterx-core' ) );
	}
}

/**
 * Returns the Plugin application instance.
 *
 * @since 1.0.0
 *
 * @return Plugin
 */
function jupiterx_core_raven() {
	return Plugin::get_instance();
}

/**
 * Initializes the JupiterX_Core Raven extension.
 *
 * @since 1.0.0
 */
jupiterx_core_raven();
