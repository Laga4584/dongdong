<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class PAFWEB_Autoloader {
	private $include_path = '';
	public function __construct() {
		if ( function_exists( "__autoload" ) ) {
			spl_autoload_register( "__autoload" );
		}

		spl_autoload_register( array ( $this, 'autoload' ) );

		$this->include_path = untrailingslashit( plugin_dir_path( PAFWEB_PLUGIN_FILE ) ) . '/includes/';
	}
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once( $path );

			return true;
		}

		return false;
	}
	public function autoload( $class ) {
		$class = strtolower( $class );

		if ( strpos( $class, 'pafw_' ) === false && strpos( $class, 'pafweb_' ) === false && strpos( $class, 'wc_gateway_' ) === false ) {
			return;
		}

		$file = $this->get_file_name_from_class( $class );
		$path = '';

		if ( strpos( $class, 'pafweb_admin' ) === 0 ) {
			$path = $this->include_path . 'admin/';
		}

		$paths = explode( '_', $class );

		if ( strpos( $class, 'pafw_settings' ) === 0 && count( $paths ) > 2 ) {
			$path = $this->include_path . 'admin/settings/' . $paths[2] . '/';
		} else if ( strpos( $class, 'wc_gateway_' ) === 0 && ( $paths[2] == 'pafw' || $paths[2] == 'eximbay' ) ) {
			$path = $this->include_path . 'gateways/' . $paths[2] . '/';
		}

		if ( empty( $path ) || ( ! $this->load_file( $path . $file ) && strpos( $class, 'pafw' ) === 0 ) ) {
			$this->load_file( $this->include_path . $file );
		}
	}

}

new PAFWEB_Autoloader();