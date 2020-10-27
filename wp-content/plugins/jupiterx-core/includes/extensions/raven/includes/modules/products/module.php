<?php
namespace JupiterX_Core\Raven\Modules\Products;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_raven_get_render_products', [ $this, 'get_render_products' ] );
		add_action( 'wp_ajax_nopriv_raven_get_render_products', [ $this, 'get_render_products' ] );
	}

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function get_render_products() {
		$post_id  = filter_input( INPUT_POST, 'post_id' );
		$model_id = filter_input( INPUT_POST, 'model_id' );
		$paged    = filter_input( INPUT_POST, 'paged' );
		$category = filter_input( INPUT_POST, 'category' );

		if ( empty( $post_id ) ) {
			wp_send_json_error( new \WP_Error( 'no_post_id', 'No post_id defined.' ) );
		}

		if ( empty( $model_id ) ) {
			wp_send_json_error( new \WP_Error( 'no_model_id', 'No model_id defined.' ) );
		}

		$elementor = \Elementor\Plugin::$instance;

		$meta = $elementor->documents->get( $post_id )->get_elements_data();

		$element = Utils::find_element_recursive( $meta, $model_id );

		if ( ! empty( $paged ) ) {
			$element['settings']['paged'] = intval( $paged );
		}

		if ( ! empty( $category ) ) {
			$element['settings']['category'] = intval( $category );
		}

		$instance = $elementor->elements_manager->create_element_instance( $element );

		if ( ! $instance ) {
			wp_send_json_error();
		}

		$queried_products = $instance->ajax_get_render_products();

		wp_send_json_success( $queried_products );
	}

	public function get_widgets() {
		return [ 'products' ];
	}
}
