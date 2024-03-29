<?php
/**
 * Class: Jet_Woo_Builder_Cart_Document
 * Name: Cart Template
 * Slug: jet-woo-builder-cart
 */

use Elementor\Controls_Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Jet_Woo_Builder_Cart_Document extends Jet_Woo_Builder_Document_Base {

	/**
	 * @access public
	 */
	public function get_name() {
		return 'jet-woo-builder-cart';
	}

	/**
	 * @access public
	 * @static
	 */
	public static function get_title() {
		return __( 'Jet Woo Cart Template', 'jet-woo-builder' );
	}

	public function get_preview_as_query_args() {

		jet_woo_builder()->documents->set_current_type( $this->get_name() );

	}

}