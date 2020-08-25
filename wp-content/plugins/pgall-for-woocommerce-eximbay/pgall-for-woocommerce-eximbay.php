<?php

/*
Plugin Name: PGALL 워드프레스 결제 - 엑심베이 익스텐션
Plugin URI: 
Description: 엑심베이 결제 수단을 지원하는 PGALL 워드프레스 결제 플러그인의 익스텐션입니다.
Version: 1.2.12
Author: CodeMShop
Author URI: www.codemshop.com
License: GPLv2 or later
*/


//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PGALL_For_WooCommerce_EximBay' ) ) {
	class PGALL_For_WooCommerce_EximBay {

		private static $_instance = null;
		protected $slug;
		protected $version = '1.2.12';
		protected $plugin_url;
		protected $plugin_path;
		protected $license_manager;
		public function __construct() {
			$this->slug = 'pgall-for-woocommerce-eximbay';

			if ( ! defined( 'PAFWEB_PLUGIN_FILE' ) ) {
				define( 'PAFWEB_PLUGIN_FILE', __FILE__ );
			}

			if ( ! defined( 'PAFWEB_VERSION' ) ) {
				define( 'PAFWEB_VERSION', $this->version );
			}

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if ( true ||  is_plugin_active( 'pgall-for-woocommerce/pgall-for-woocommerce.php' ) ) {
			    $this->init_update();

				require_once( 'includes/class-pafweb-autoloader.php' );

				add_action( 'woocommerce_init', array ( $this, 'init' ), 0 );
				add_action( 'plugins_loaded', array ( $this, 'load_plugin_textdomain' ) );

				add_filter( 'pafw_supported_gateway_ids', array ( $this, 'add_gateway_id' ) );
			}else{
				add_action( 'admin_notices', array ( $this, 'admin_notices' ) );
			}
		}
		public function plugin_url() {
			if ( $this->plugin_url ) {
				return $this->plugin_url;
			}

			return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
		}
		public function plugin_path() {
			if ( $this->plugin_path ) {
				return $this->plugin_path;
			}

			return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		}
		public function template_path() {
			return $this->plugin_path() . '/templates/';
		}

		public function init() {
			$this->includes();
		}

		function init_update() {
			require 'includes/admin/update/LicenseManager.php';

			$this->license_manager = new PAFWEB_LicenseManager( $this->slug, __DIR__, __FILE__ );
		}
		function includes() {
			require_once( 'includes/pafweb-functions.php' );


			if ( is_admin() ) {
				$this->admin_includes();
			}

			if ( defined( 'DOING_AJAX' ) ) {
				$this->ajax_includes();
			}
		}
		public function admin_includes() {
		}
		public function ajax_includes() {
			require_once( 'includes/class-pafweb-ajax.php' );
		}

		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'pgall-for-woocommerce-eximbay', false, dirname( plugin_basename( __FILE__ ) ) . "/languages/" );
		}

		public function slug() {
			return $this->slug;
		}

		public function add_gateway_id( $gateway_ids ) {
			$gateway_ids[] = 'eximbay';

			return $gateway_ids;
		}

		function admin_notices() {
			?>
			<div class="notice notice-error">
				<p><?php _e( '엑심베이 결제를 이용하시려면 <a target="_blank" href="/wp-admin/plugin-install.php?s=PGALL&tab=search&type=term">PGALL 워드프레스 결제 플러그인</a>이 설치되어 있어야 합니다.', 'pgall-for-woocommerce-eximbay' ); ?></p>
			</div>
			<?php
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
	function PAFWEB() {
		return PGALL_For_WooCommerce_EximBay::instance();
	}

	return PAFWEB();

}
