<?php
/**
 * Add form Email action.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

use JupiterX_Core\Raven\Utils;

defined( 'ABSPATH' ) || die();

/**
 * Email Action.
 *
 * Initializing the emil action by extending action base.
 *
 * @since 1.0.0
 */
class Email extends Action_Base {

	/**
	 * Update controls.
	 *
	 * Add Email section.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {

		$widget->start_controls_section(
			'section_email',
			[
				'label' => __( 'Email', 'jupiterx-core' ),
				'condition' => [
					'actions' => 'email',
				],
			]
		);

		$widget->add_control(
			'email_to',
			[
				'label' => __( 'To', 'jupiterx-core' ),
				'type' => 'text',
				'default' => get_bloginfo( 'admin_email' ),
				'placeholder' => get_bloginfo( 'admin_email' ),
				'title' => __( 'Separate emails with commas', 'jupiterx-core' ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_subject',
			[
				'label' => __( 'Email Subject', 'jupiterx-core' ),
				'type' => 'text',
				'default' => 'New message from "' . get_bloginfo( 'name' ) . '"',
				'placeholder' => 'New message from "' . get_bloginfo( 'name' ) . '"',
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_from',
			[
				'label' => __( 'From Email', 'jupiterx-core' ),
				'type' => 'text',
				'default' => 'email@' . Utils::get_site_domain(),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_name',
			[
				'label' => __( 'From Name', 'jupiterx-core' ),
				'type' => 'text',
				'default' => get_bloginfo( 'name' ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_reply_to_options',
			[
				'label' => __( 'Reply-To', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'custom',
				'label_block' => true,
				'render_type' => 'ui',
				'options' => [],
			]
		);

		$widget->add_control(
			'email_reply_to',
			[
				'type' => 'text',
				'default' => 'email@' . Utils::get_site_domain(),
				'render_type' => 'ui',
				'show_label' => false,
				'label_block' => true,
				'condition' => [
					'email_reply_to_options' => 'custom',
				],
			]
		);

		$widget->add_control(
			'email_cc',
			[
				'label' => __( 'Cc', 'jupiterx-core' ),
				'type' => 'text',
				'title' => __( 'Separate emails with commas', 'jupiterx-core' ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'email_bcc',
			[
				'label' => __( 'Bcc', 'jupiterx-core' ),
				'type' => 'text',
				'title' => __( 'Separate emails with commas', 'jupiterx-core' ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'confirmation',
			[
				'label' => __( 'Confirmation', 'jupiterx-core' ),
				'type' => 'switcher',
				'description' => __( 'Send a copy of email to the one who submits the form.', 'jupiterx-core' ),
				'yes' => __( 'Yes', 'jupiterx-core' ),
				'no' => __( 'No', 'jupiterx-core' ),
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * Run action.
	 *
	 * Send email.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public static function run( $ajax_handler ) {
		$email_to               = $ajax_handler->form['settings']['email_to'];
		$email_subject          = $ajax_handler->form['settings']['email_subject'];
		$email_name             = $ajax_handler->form['settings']['email_name'];
		$email_from             = $ajax_handler->form['settings']['email_from'];
		$email_reply_to_options = ! empty( $ajax_handler->form['settings']['email_reply_to_options'] ) ? $ajax_handler->form['settings']['email_reply_to_options'] : 'custom';
		$email_reply_to         = $ajax_handler->form['settings']['email_reply_to'];
		$email_cc               = ! empty( $ajax_handler->form['settings']['email_cc'] ) ? explode( ',', $ajax_handler->form['settings']['email_cc'] ) : [];
		$email_bcc              = ! empty( $ajax_handler->form['settings']['email_bcc'] ) ? explode( ',', $ajax_handler->form['settings']['email_bcc'] ) : [];
		$confirmation           = ! empty( $ajax_handler->form['settings']['confirmation'] ) ? $ajax_handler->form['settings']['confirmation'] : false;
		$body                   = '';

		// Body.
		foreach ( $ajax_handler->form['settings']['fields'] as $field ) {
			$body .= $field['label'] . ': ' . $ajax_handler->record['fields'][ $field['_id'] ] . '<br>';
		}

		$body .= '<br> ---- <br><br>';
		/* translators: %s: Today date */
		$body .= sprintf( __( 'Date: %s', 'jupiterx-core' ), date( 'F j, Y' ) ) . '<br>';
		/* translators: %s: Today time */
		$body .= sprintf( __( 'Time: %s', 'jupiterx-core' ), date( 'h:i A' ) ) . '<br>';
		/* translators: %s: Referrer URL */
		$body .= sprintf( __( 'Page URL: %s', 'jupiterx-core' ), $ajax_handler->record['referrer'] ) . '<br>';

		$headers[] = 'Content-Type: text/html';
		$headers[] = 'charset=UTF-8';
		$headers[] = 'From: ' . $email_name . ' <' . $email_from . '>';

		if ( 'custom' !== $email_reply_to_options ) {
			$email_reply_to = $ajax_handler->record['fields'][ $email_reply_to_options ];
		}

		if ( ! empty( $email_reply_to ) ) {
			$headers[] = 'Reply-To: ' . $email_name . '<' . $email_reply_to . '>';
		}

		if ( ! empty( $email_cc ) ) {
			foreach ( $email_cc as $email ) {
				$headers[] = 'Cc: ' . $email;
			}
		}

		if ( ! empty( $email_bcc ) ) {
			foreach ( $email_bcc as $email ) {
				$headers[] = 'Bcc: ' . $email;
			}
		}

		wp_mail( $email_to, $email_subject, $body, $headers );

		if ( 'yes' === $confirmation ) {
			self::send_confirmation_email( $ajax_handler, $email_name, $email_from, $body );
		}

		$ajax_handler->add_response( 'success', 'Email sent.' );
	}

	/**
	 * Send confirmation email.
	 *
	 * @since 1.9.5
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param string $email_name Email name.
	 * @param string $email_from Email from.
	 * @param string $body Email body.
	 */
	private static function send_confirmation_email( $ajax_handler, $email_name, $email_from, $body ) {
		$headers[] = 'Content-Type: text/html';
		$headers[] = 'charset=UTF-8';
		$headers[] = 'From: ' . $email_name . ' <' . $email_from . '>';

		// Email field.
		$email = array_filter( $ajax_handler->form['settings']['fields'], function( $field ) {
			return 'email' === $field['type'];
		} );

		// First email field.
		$email = reset( $email );

		// Email address.
		$email_to = $ajax_handler->record['fields'][ $email['_id'] ];

		wp_mail( $email_to, esc_html__( 'We received your email', 'jupiterx-core' ), $body, $headers );
	}

}
