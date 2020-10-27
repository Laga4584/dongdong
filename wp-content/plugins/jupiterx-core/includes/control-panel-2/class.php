<?php
/**
 * This class adds new control panel.
 *
 * @package JupiterX_Core\Control_Panel_2
 *
 * @since 1.18.0
 */

/**
 * New control panel.
 *
 * @package JupiterX_Core\Control_Panel_2
 *
 * @since 1.18.0
 */
class JupiterX_Control_Panel_2 {

	const SCREEN_ID = 'jupiterx';

	/**
	 * Components store.
	 *
	 * @since 1.18.0
	 *
	 * @var array
	 */
	private $components = [];

	/**
	 * Constructor.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'init' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
	}

	/**
	 * Initialize.
	 *
	 * @since 1.18.0
	 */
	public function init() {
		jupiterx_core()->load_files( [
			'control-panel-2/includes/logic-messages',
			'control-panel-2/includes/class-helpers',
			'control-panel-2/includes/class-filesystem',
			'control-panel-2/includes/class-db-manager',
			'control-panel-2/includes/class-db-php-manager',
			'control-panel-2/includes/class-export-import-content',
			'control-panel-2/includes/class-install-template',
			'control-panel-2/includes/class-license',
			'control-panel-2/includes/class-theme-upgrades-downgrades',
			'control-panel-2/includes/class-install-plugins',
			'control-panel-2/includes/class-updates-manager',
			'control-panel-2/includes/class-templates',
			'control-panel-2/includes/class-settings',
			'control-panel-2/includes/class-version-control',
			'control-panel-2/includes/class-image-sizes',
			'control-panel-2/includes/class-performance-tips',
		] );

		$this->components['license']   = JupiterX_Core_Control_Panel_License::get_instance();
		$this->components['templates'] = JupiterX_Core_Control_Panel_Templates::get_instance();

		if ( $this->is_current_screen() ) {
			$this->back_compat();
		}
	}

	/**
	 * Run backward compatibility actions.
	 */
	private function back_compat() {
		$this->components['license']->retry_api_key();
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @since 1.18.0
	 */
	public function enqueue_admin_scripts() {
		if ( ! $this->is_current_screen() ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_script(
			'jupiterx-control-panel-2',
			jupiterx_core()->plugin_url() . 'includes/control-panel-2/dist/control-panel.js',
			[ 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ],
			jupiterx_core()->version(),
			true
		);

		wp_localize_script(
			'jupiterx-control-panel-2',
			'jupiterxControlPanel2',
			$this->get_localize_data()
		);

		wp_enqueue_style(
			'jupiterx-control-panel-2',
			jupiterx_core()->plugin_url() . 'includes/control-panel-2/dist/control-panel.css',
			[],
			jupiterx_core()->version()
		);

		wp_set_script_translations( 'jupiterx-control-panel-2', 'jupiterx-core', jupiterx_core()->plugin_dir() . 'languages' );
	}

	/**
	 * Register admin menu.
	 *
	 * @since 1.18.0
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function register_admin_menu() {
		if ( ! defined( 'JUPITERX_NAME' ) ) {
			return;
		}

		$menu_icon = 'dashicons-star-filled';

		if ( function_exists( 'jupiterx_is_white_label' ) ) {
			if ( jupiterx_is_white_label() && jupiterx_get_option( 'white_label_menu_icon' ) ) {
				$menu_icon = jupiterx_get_option( 'white_label_menu_icon' );
			}
		}

		$menu_name = JUPITERX_NAME;

		if ( function_exists( 'jupiterx_is_white_label' ) ) {
			if ( jupiterx_is_white_label() && jupiterx_get_option( 'white_label_text_occurence' ) ) {
				$menu_name = esc_html( jupiterx_get_option( 'white_label_text_occurence' ) );
			}
		}

		add_menu_page(
			$menu_name,
			$menu_name,
			'manage_options',
			self::SCREEN_ID,
			[ $this, 'register_admin_menu_callback' ],
			$menu_icon,
			'3.5'
		);

		add_submenu_page(
			self::SCREEN_ID,
			__( 'Control Panel', 'jupiterx-core' ),
			__( 'Control Panel', 'jupiterx-core' ) . $this->warning_badge(),
			'edit_theme_options',
			self::SCREEN_ID,
			[ $this, 'register_admin_menu_callback' ]
		);

		add_submenu_page(
			self::SCREEN_ID,
			__( 'Customize', 'jupiterx-core' ),
			__( 'Customize', 'jupiterx-core' ),
			'edit_theme_options',
			'customize_theme',
			[ $this, 'redirect_page' ]
		);

		if ( function_exists( 'jupiterx_is_white_label' ) ) {
			if ( ! jupiterx_is_white_label() || ( jupiterx_is_white_label() && jupiterx_get_option( 'white_label_menu_help', true ) ) ) {
				add_submenu_page(
					self::SCREEN_ID,
					__( 'Help', 'jupiterx-core' ),
					__( 'Help', 'jupiterx-core' ),
					'edit_theme_options',
					'jupiterx_help',
					[ $this, 'redirect_page' ]
				);
			}
		}

		if ( function_exists( 'jupiterx_is_pro' ) && ! jupiterx_is_pro() && ! jupiterx_is_premium() ) {
			add_submenu_page(
				self::SCREEN_ID,
				__( 'Upgrade', 'jupiterx-core' ),
				'<i class="jupiterx-icon-pro"></i>' . __( 'Upgrade', 'jupiterx-core' ),
				'edit_theme_options',
				'jupiterx_upgrade',
				[ $this, 'redirect_page' ]
			);
		}

		remove_submenu_page( 'themes.php', self::SCREEN_ID );
	}

	/**
	 * Get warining badge for premium users.
	 *
	 * @since 1.18.0
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
			trailingslashit( jupiterx_core()->plugin_assets_url() ) . 'images/warning-badge.svg',
			esc_html__( 'Activate Product', 'jupiterx-core' )
		);
	}

	/**
	 * Redirect an admin page.
	 *
	 * @since 1.18.0
	 */
	public function redirect_page() {
		if ( empty( jupiterx_get( 'page' ) ) ) {
			return;
		}

		if ( 'customize_theme' === jupiterx_get( 'page' ) ) {
			wp_safe_redirect( admin_url( 'customize.php' ) );
			exit;
		}

		if ( 'jupiterx_upgrade' === jupiterx_get( 'page' ) ) {
			wp_safe_redirect( admin_url() );
			exit;
		}

		if ( 'jupiterx_help' === jupiterx_get( 'page' ) ) {
			wp_safe_redirect( 'https://themes.artbees.net/support/jupiterx/' );
			exit;
		}
	}

	/**
	 * Register admin menu callback.
	 *
	 * @since 1.18.0
	 */
	public function register_admin_menu_callback() {
		?>
		<div id="wrap" class="wrap">
			<h1></h1>
			<div id="jx-cp-root" class="jx-cp"></div>
		</div>
		<?php
	}

	/**
	 * Get localize data.
	 *
	 * @since 1.18.0
	 */
	private function get_localize_data() {
		$data = [
			'nonce' => wp_create_nonce( 'jupiterx_control_panel' ),
			'themeVersion' => $this->get_theme_data( 'Version' ),
			'urls' => [
				'customize' => admin_url( 'customize.php' ),
			],
			'installedPlugins' => array_keys( get_plugins() ),
			'activePlugins' => array_values( get_option( 'active_plugins' ) ),
			'options' => get_option( 'jupiterx', [] ),
			'postTypes' => array_values( jupiterx_get_custom_post_types( 'objects' ) ),
			'themeLicense' => $this->components['license']->get_details(),
			'isPremium' => jupiterx_is_premium(),
			'searchFilters' => $this->components['templates']->get_filters(),
			'templateInstalled' => $this->components['templates']->get_installed(),
			'adminAjaxURL' => admin_url( 'admin-ajax.php' ),
			'siteName' => get_bloginfo( 'name' ),
			'tabs' => $this->get_tabs(),
		];

		return $data;
	}

	/**
	 * Get control panel tabs.
	 *
	 * @since 1.18.0
	 *
	 * @return void
	 */
	private function get_tabs() {
		$tabs = [
			'dashboard' => [
				'id' => 'dashboard',
				'href' => '/',
				'label' => __( 'Dashboard', 'jupiterx-core' ),
				'help' => 'https://themes.artbees.net/docs/registering-the-jupiter-x-theme/',
				'whiteLabel' => true,
			],
			'templates' => [
				'id' => 'templates',
				'href' => '/templates',
				'label' => __( 'Templates', 'jupiterx-core' ),
				'help' => 'https://themes.artbees.net/docs/installing-a-template/',
				'whiteLabel' => true,
			],
			'elementor' => [
				'id' => 'elementor',
				'href' => '/elementor',
				'label' => __( 'Elementor', 'jupiterx-core' ),
				'whiteLabel' => true,
			],
			'plugins' => [
				'id' => 'plugins',
				'href' => '/plugins',
				'label' => __( 'Plugins', 'jupiterx-core' ),
				'help' => 'https://themes.artbees.net/docs/bundled-plugins/',
				'whiteLabel' => true,
			],
			'settings' => [
				'id' => 'settings',
				'href' => '/settings',
				'label' => __( 'Settings', 'jupiterx-core' ),
				'help' => 'https://themes.artbees.net/support/jupiterx/',
				'whiteLabel' => false,
			],
			'updates' => [
				'id' => 'updates',
				'href' => '/updates',
				'label' => __( 'Updates', 'jupiterx-core' ),
				'help' => 'https://themes.artbees.net/docs/updating-jupiter-x-theme-automatically/',
				'whiteLabel' => true,
			],
			'tools' => [
				'id' => 'tools',
				'href' => '/tools',
				'label' => __( 'Tools', 'jupiterx-core' ),
				'whiteLabel' => true,
			],
			'site-health' => [
				'id' => 'siteHealth',
				'href' => admin_url( 'site-health.php' ),
				'label' => __( 'Site Health', 'jupiterx-core' ),
				'whiteLabel' => true,
			],
		];

		// Hide Site Health for WP under 5.2.
		if ( version_compare( get_bloginfo( 'version' ), '5.2', '<' ) ) {
			unset( $tabs['site-health'] );
		}

		// Hide Elementor for now.
		unset( $tabs['elementor'] );

		// Hide Tools if constant is not defined.
		if ( ! $this->show_tab( 'JUPITERX_CONTROL_PANEL_EXPORT_IMPORT' ) ) {
			unset( $tabs['tools'] );
		}

		return array_values( $tabs );
	}

	/**
	 * Get current theme data.
	 *
	 * @since 1.18.0
	 *
	 * @param string $data The theme data.
	 */
	private function get_theme_data( $data ) {
		$current_theme = wp_get_theme();

		return $current_theme->get( $data );
	}

	/**
	 * Check current screen.
	 *
	 * @since 1.18.0
	 *
	 * @return boolean Control panel screen.
	 */
	private function is_current_screen() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.NonceVerification.NoNonceVerification
		return is_admin() && isset( $_GET['page'] ) && self::SCREEN_ID === $_GET['page'];
	}

	/**
	 * Get show tab.
	 *
	 * @param string $constant Constant name.
	 *
	 * @return boolean Tab show.
	 */
	private function show_tab( $constant ) {
		return defined( $constant ) && constant( $constant );
	}
}

new JupiterX_Control_Panel_2();
