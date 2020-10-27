<?php
/**
 * Add Ajax Handler.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Classes;

use Elementor\Plugin as Elementor;
use JupiterX_Core\Raven\Modules\Forms\Module;
use JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

/**
 * Ajax Handler.
 *
 * Initializing the ajax handler class for handling form ajax requests.
 *
 * @since 1.0.0
 */
class Ajax_Handler {

	/**
	 * Response.
	 *
	 * Holds all the responses.
	 *
	 * @access private
	 *
	 * @var array
	 */
	public $response = [
		'message' => [],
		'errors' => [],
		'admin_errors' => [],
	];

	/**
	 * Form.
	 *
	 * Holds the form settings.
	 *
	 * @access private
	 *
	 * @var array
	 */
	public $form;

	/**
	 * Record.
	 *
	 * Holds a record of a form.
	 *
	 * @access private
	 *
	 * @var array
	 */
	public $record;

	/**
	 * Is success.
	 *
	 * Holds the reponse state.
	 *
	 * @access private
	 *
	 * @var array
	 */
	public $is_success = true;

	/**
	 * Ajax handler constructor.
	 *
	 * Initializing the ajax handler class by hooking in ajax actions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_ajax_raven_form_frontend', [ $this, 'handle_frontend' ] );
		add_action( 'wp_ajax_nopriv_raven_form_frontend', [ $this, 'handle_frontend' ] );
		add_action( 'wp_ajax_raven_form_editor', [ $this, 'handle_editor' ] );
	}

	/**
	 * Handle frontend requests.
	 *
	 * Handle the form submit in frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function handle_frontend() {
		$post_id      = filter_input( INPUT_POST, 'post_id' );
		$form_id      = filter_input( INPUT_POST, 'form_id' );
		$this->record = $_POST; // @codingStandardsIgnoreLine

		// Convert array data to string. Used for checkbox.
		foreach ( $this->record['fields'] as $_id => $field ) {
			if ( is_array( $field ) ) {
				$this->record['fields'][ $_id ] = implode( ', ', $field );
			}
		}

		$form_meta  = Elementor::$instance->documents->get( $post_id )->get_elements_data();
		$this->form = Module::find_element_recursive( $form_meta, $form_id );

		$this
			->set_custom_messages()
			->validate_form()
			->validate_fields()
			->run_actions()
			->send_response();
	}

	/**
	 * Handle editor requests.
	 *
	 * Handle the form requests in editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function handle_editor() {
		$action  = filter_input( INPUT_POST, 'service' );
		$request = filter_input( INPUT_POST, 'request' );
		$params  = filter_input( INPUT_POST, 'params', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$class_name = 'JupiterX_Core\Raven\Modules\Forms\Actions\\' . ucfirst( $action );
		call_user_func( [ $class_name, $request ], $this, empty( $params ) ? [] : $params );

		$this->send_response();
	}

	/**
	 * Set success.
	 *
	 * Set form state to success/error.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param boolean $bool True or false.
	 */
	public function set_success( $bool ) {
		$this->is_success = $bool;
		return $this;
	}

	/**
	 * Validate form.
	 *
	 * Validate the form based on form ID.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_custom_messages() {
		$form = $this->form;

		if ( ! $form ) {
			return $this;
		}

		if ( empty( $form['settings']['messages_custom'] ) ) {
			return $this;
		}

		Module::$messages = [
			'success' => $form['settings']['messages_success'],
			'error' => $form['settings']['messages_error'],
			'required' => $form['settings']['messages_required'],
			'subscriber' => $form['settings']['messages_subscriber'],
		];

		return $this;
	}

	/**
	 * Validate form.
	 *
	 * Validate the form based on form ID.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	private function validate_form() {
		if ( $this->form ) {
			return $this;
		}

		$this
			->add_response( 'message', __( 'There\'s something wrong. The form is not valid.', 'jupiterx-core' ) )
			->set_success( false )
			->send_response();

		return $this;
	}

	/**
	 * Validate form fields.
	 *
	 * Validate form fields based on the settings.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	private function validate_fields() {
		$form_fields = $this->form['settings']['fields'];

		foreach ( $form_fields as $field ) {
			$field['type'] = empty( $field['type'] ) ? 'text' : $field['type'];
			$class_name    = 'JupiterX_Core\Raven\Modules\Forms\Fields\\' . ucfirst( $field['type'] );

			$class_name::validate_required( $this, $field );
			$class_name::validate( $this, $field );
		}

		if ( ! empty( $this->response['errors'] ) ) {
			$this->send_response();
		}

		return $this;
	}

	/**
	 * Run actions.
	 *
	 * Run all the specified actions.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function run_actions() {
		if ( empty( $this->form['settings']['actions'] ) ) {
			return $this;
		}

		$actions = $this->form['settings']['actions'];

		foreach ( $actions as $action ) {
			$class_name = 'JupiterX_Core\Raven\Modules\Forms\Actions\\' . ucfirst( $action );

			$class_name::run( $this );
		}

		return $this;
	}

	/**
	 * Add response.
	 *
	 * Add response to ajax response.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $type Response type.
	 * @param string $text Response text.
	 * @param string $text_key Response text key.
	 */
	public function add_response( $type, $text = '', $text_key = '' ) {
		if ( ! empty( $text_key ) ) {
			$this->response[ $type ][ $text_key ] = $text;
			return $this;
		}

		$this->response[ $type ][] = $text;
		return $this;
	}

	/**
	 * Send response.
	 *
	 * Send success/fail response.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function send_response() {
		if ( ! current_user_can( 'administrator' ) ) {
			unset( $this->response['admin_errors'] );
		}

		if ( $this->is_success ) {
			$this->add_response( 'message', Module::$messages['success'] );
			wp_send_json_success( $this->response );
		}

		if ( ! empty( $this->response['errors'] ) ) {
			$this->add_response( 'message', Module::$messages['error'] );
		}

		wp_send_json_error( $this->response );
	}
}
