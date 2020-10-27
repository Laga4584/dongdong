<?php
namespace JupiterX_Core\Raven\Modules\Forms;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;
use JupiterX_Core\Raven\Modules\Forms\Fields;
use JupiterX_Core\Raven\Modules\Forms\Actions;
use JupiterX_Core\Raven\Modules\Forms\Classes\Ajax_Handler;
use JupiterX_Core\Raven\Utils;

class Module extends Module_Base {

	public static $field_types = [];

	public static $action_types = [];

	public static $messages = [];

	public function __construct() {
		parent::__construct();

		$this->register_field_types();

		$this->register_action_types();

		$this->set_messages();

		// Download hooks.
		add_action( 'admin_post_raven_download_file', [ Utils::class, 'handle_file_download' ] );
		add_action( 'admin_post_nopriv_raven_download_file', [ Utils::class, 'handle_file_download' ] );

		new Ajax_Handler();
	}

	public function get_widgets() {
		return [ 'form' ];
	}

	public static function get_field_types() {
		return [
			'text' => __( 'Text', 'jupiterx-core' ),
			'email' => __( 'Email', 'jupiterx-core' ),
			'select' => __( 'Select', 'jupiterx-core' ),
			'textarea' => __( 'Textarea', 'jupiterx-core' ),
			'tel' => __( 'Tel', 'jupiterx-core' ),
			'number' => __( 'Number', 'jupiterx-core' ),
			'date' => __( 'Date', 'jupiterx-core' ),
			'time' => __( 'Time', 'jupiterx-core' ),
			'checkbox' => __( 'Checkbox', 'jupiterx-core' ),
			'radio' => __( 'Radio', 'jupiterx-core' ),
			'acceptance' => __( 'Acceptance', 'jupiterx-core' ),
			'recaptcha' => __( 'reCAPTCHA', 'jupiterx-core' ),
			'recaptcha_v3' => __( 'reCAPTCHA v3', 'jupiterx-core' ),
			'address' => __( 'Address', 'jupiterx-core' ),
		];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private function register_field_types() {
		foreach ( self::get_field_types() as $field_key => $field_value ) {
			$class_name = __NAMESPACE__ . '\Fields\\' . ucfirst( $field_key );

			self::$field_types[ $field_key ] = new $class_name();
		}
	}

	public static function get_action_types() {
		return [
			'email' => __( 'Email', 'jupiterx-core' ),
			'mailchimp' => __( 'MailChimp', 'jupiterx-core' ),
			'redirect' => __( 'Redirect', 'jupiterx-core' ),
			'slack' => __( 'Slack', 'jupiterx-core' ),
			'hubspot' => __( 'Hubspot', 'jupiterx-core' ),
			'download' => __( 'Download', 'jupiterx-core' ),
			'webhook' => __( 'Webhook', 'jupiterx-core' ),
		];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private function register_action_types() {
		foreach ( self::get_action_types() as $action_key => $action_value ) {
			$class_name = __NAMESPACE__ . '\Actions\\' . ucfirst( $action_key );

			self::$action_types[ $action_key ] = new $class_name();
		}
	}

	public function set_messages() {
		self::$messages = [
			'success' => __( 'The form was sent successfully!', 'jupiterx-core' ),
			'error' => __( 'Please check the errors.', 'jupiterx-core' ),
			'required' => __( 'Required', 'jupiterx-core' ),
			'subscriber' => __( 'Subscriber already exists.', 'jupiterx-core' ),
		];
	}

	public static function render_field( $widget, $field ) {
		self::$field_types[ $field['type'] ]->render( $widget, $field );
	}

	public static function find_element_recursive( $elements, $form_id ) {
		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	public function translations() {
		return [
			'validation' => [
				'required' => __( 'Please fill in this field', 'jupiterx-core' ),
				'invalidEmail' => __( 'The value is not a valid email address', 'jupiterx-core' ),
				'invalidPhone' => __( 'The value should only consist numbers and phone characters (-, +, (), etc)', 'jupiterx-core' ),
				'invalidNumber' => __( 'The value is not a valid number', 'jupiterx-core' ),
				'invalidMaxValue' => __( 'Value must be less than or equal to MAX_VALUE', 'jupiterx-core' ),
				'invalidMinValue' => __( 'Value must be greater than or equal to MIN_VALUE', 'jupiterx-core' ),
			],
		];
	}
}
