<?php

namespace Raven\Core\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

class Counter extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'list';
	}

	public function get_fields() {
		return [ 'prefix', 'suffix', 'title' ];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'prefix':
				return esc_html__( 'Raven Counter: Counter number prefix', 'raven' );

			case 'suffix':
				return esc_html__( 'Raven Counter: Counter number suffix', 'raven' );

			case 'title':
				return esc_html__( 'Raven Counter: Title', 'raven' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'prefix':
				return 'LINE';

			case 'suffix':
				return 'LINE';

			case 'title':
				return 'LINE';

			default:
				return '';
		}
	}

}
