<?php
namespace Raven\Modules\Forms;

defined( 'ABSPATH' ) || die();

use Raven\Base\Module_base;
use Raven\Modules\Forms\Fields;
use Raven\Modules\Forms\Actions;
use Raven\Modules\Forms\Classes\Ajax_Handler;
use Raven\Utils;

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
			'text' => __( 'Text', 'raven' ),
			'email' => __( 'Email', 'raven' ),
			'select' => __( 'Select', 'raven' ),
			'textarea' => __( 'Textarea', 'raven' ),
			'tel' => __( 'Tel', 'raven' ),
			'number' => __( 'Number', 'raven' ),
			'date' => __( 'Date', 'raven' ),
			'time' => __( 'Time', 'raven' ),
			'checkbox' => __( 'Checkbox', 'raven' ),
			'radio' => __( 'Radio', 'raven' ),
			'acceptance' => __( 'Acceptance', 'raven' ),
			'recaptcha' => __( 'reCAPTCHA', 'raven' ),
			'recaptcha_v3' => __( 'reCAPTCHA v3', 'raven' ),
			'address' => __( 'Address', 'raven' ),
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
			'email' => __( 'Email', 'raven' ),
			'mailchimp' => __( 'MailChimp', 'raven' ),
			'redirect' => __( 'Redirect', 'raven' ),
			'slack' => __( 'Slack', 'raven' ),
			'hubspot' => __( 'Hubspot', 'raven' ),
			'download' => __( 'Download', 'raven' ),
			'webhook' => __( 'Webhook', 'raven' ),
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
			'success' => __( 'The form was sent successfully!', 'raven' ),
			'error' => __( 'Please check the errors.', 'raven' ),
			'required' => __( 'Required', 'raven' ),
			'subscriber' => __( 'Subscriber already exists.', 'raven' ),
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
				'required' => __( 'Please fill in this field', 'raven' ),
				'invalidEmail' => __( 'The value is not a valid email address', 'raven' ),
				'invalidPhone' => __( 'The value should only consist numbers and phone characters (-, +, (), etc)', 'raven' ),
				'invalidNumber' => __( 'The value is not a valid number', 'raven' ),
				'invalidMaxValue' => __( 'Value must be less than or equal to MAX_VALUE', 'raven' ),
				'invalidMinValue' => __( 'Value must be greater than or equal to MIN_VALUE', 'raven' ),
			],
		];
	}
}
