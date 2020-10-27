<?php
/**
 * Add form tel field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

/**
 * Tel Field.
 *
 * Initializing the tel field by extending text field.
 *
 * @since 1.0.0
 */
class Tel extends Text {

	/**
	 * Get field pattern.
	 *
	 * Retrieve the field pattern.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field pattern.
	 */
	public function get_pattern() {
		return '^[0-9\-\+\s\(\)]*$';
	}

	/**
	 * Get field title.
	 *
	 * Retrieve the field title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field title.
	 */
	public function get_title() {
		return __( 'The value should only consist numbers and phone characters (-, +, (), etc)', 'jupiterx-core' );
	}

	/**
	 * Validate.
	 *
	 * Check the field based on specific validation rules.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param object $field The field data.
	 */
	public static function validate( $ajax_handler, $field ) {
		$record_field = $ajax_handler->record['fields'][ $field['_id'] ];

		if ( ! empty( $ajax_handler->response['errors'][ $field['_id'] ] ) ) {
			return;
		}

		if ( ! preg_match( '/^[0-9\-\+\s\(\)]*$/', $record_field ) ) {
			$error = __( 'The value should only consist numbers and phone characters (-, +, (), etc)', 'jupiterx-core' );
		}

		if ( empty( $error ) ) {
			return;
		}

		$ajax_handler
			->add_response( 'errors', $error, $field['_id'] )
			->set_success( false );
	}

}
