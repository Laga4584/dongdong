<?php
/**
 * This class handles survey notification bar notice.
 *
 * @since 1.17.1
 *
 * @package JupiterX\Framework\Admin\Notices
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manager class.
 *
 * @since 1.17.1
 *
 * @package JupiterX\Framework\Admin\Notices
 */
class JupiterX_Admin_Notice_Survey_Notification_Bar {

	/**
	 * API endpoint.
	 *
	 * @var string
	 */
	public $api_url = 'https://themes.artbees.net/wp-json/product/survey';

	/**
	 * Current user id.
	 *
	 * @var integer
	 */
	public $user_id;

	/**
	 * Meta key.
	 */
	const META_KEY = 'survey_notification_bar';

	/**
	 * Class Constructor.
	 *
	 * @since 1.17.1
	 * @access public
	 */
	public function __construct() {
		add_action( 'publish_page', [ $this, 'delete_elementor_pages_transient' ] );
		add_action( 'admin_notices', [ $this, 'admin_notice' ] );
		add_action( 'wp_ajax_jupiterx_dismiss_survey_notification_bar_notice', [ $this, 'dismiss_notice' ] );

		$this->user_id = get_current_user_id();
	}

	/**
	 * Dismiss notice
	 *
	 * @since 1.17.1
	 * @access public
	 *
	 * @return void
	 */
	public function dismiss_notice() {
		check_ajax_referer( 'jupiterx_dismiss_survey_notification_bar_notice' );

		update_user_meta( $this->user_id, self::META_KEY . '_dismissed', 1 );

		wp_send_json_success();
	}

	/**
	 * Register notice.
	 *
	 * @since 1.17.1
	 * @access public
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function admin_notice() {
		if ( function_exists( 'jupiterx_is_white_label' ) && jupiterx_is_white_label() ) {
			return;
		}

		$cached_info = get_user_meta( $this->user_id, self::META_KEY, true );

		if ( empty( $cached_info ) ) {
			$cached_info = [];
		}

		$info = $cached_info;

		if ( false === get_transient( 'jupiterx_survey_notification_bar_cached' ) ) {
			$info = $this->get_info();
		}

		if ( ! $this->validate_info( $info ) ) {
			return;
		}

		if ( empty( $cached_info ) || $cached_info['id'] !== $info['id'] ) {
			update_user_meta( $this->user_id, self::META_KEY, $info );

			set_transient( 'jupiterx_survey_notification_bar_cached', 1, 24 * HOUR_IN_SECONDS );
		}

		if ( empty( $cached_info ) ) {
			$cached_info = $info;
		}

		if ( ! $this->show_notice( $cached_info, $info ) ) {
			return;
		}

		$message = $info['survey_content'];

		$message .= sprintf(
			'<span style="display: block; margin: 0.7em 0.7em 0 0; clear: both;">' .
			'<a class="button-secondary jupiterx-survey-notification-bar-notice-cta" href="%1$s" target="_blank">%2$s</a></span>',
			$info['url'],
			$info['cta_text']
		);

		$nonce = wp_create_nonce( 'jupiterx_dismiss_survey_notification_bar_notice' );

		$logo = '<div class="jupiterx-survey-notification-bar-notice-logo">
			<img src="' . esc_url( JUPITERX_ADMIN_ASSETS_URL . 'images/jupiterx-notice-logo.png' ) . '" alt="' . esc_attr( __( 'Jupiter X', 'jupiterx' ) ) . '" />
		</div>';

		printf( '<div data-nonce="%s" class="jupiterx-survey-notification-bar-notice notice notice-warning is-dismissible"><div class="jupiterx-survey-notification-bar-notice-inner">%s<div class="jupiterx-survey-notification-bar-notice-content">%s</div></div></div>', $nonce, $logo, $message ); // phpcs:ignore WordPress.Security
	}

	/**
	 * Show admin notice if not dismissed, product registered, has atleast elementor 5 pages & status is not hide.
	 *
	 * @since 1.17.1
	 * @access public
	 *
	 * @param array $cached_info Cached info.
	 * @param array $info New info.
	 *
	 * @return boolean
	 */
	public function show_notice( $cached_info, $info ) {
		if ( empty( $cached_info ) || empty( $info ) ) {
			return false;
		}

		$force = $cached_info['id'] !== $info['id'];

		if ( $force ) {
			delete_user_meta( $this->user_id, self::META_KEY . '_dismissed' );
		}

		if ( strval( 1 ) === get_user_meta( $this->user_id, self::META_KEY . '_dismissed', true ) ) {
			return false;
		}

		if ( ! jupiterx_is_pro() ) {
			return false;
		}

		if ( ! $this->has_elementor_pages() ) {
			return false;
		}

		return 'hide' !== $info['status'];
	}

	/**
	 * Check user has atleast 5 elementor pages.
	 *
	 * @since 1.17.1
	 * @access public
	 *
	 * @return boolean
	 */
	public function has_elementor_pages() {
		$pages = get_transient( 'jupiterx_elementor_pages_count' );

		if ( false !== $pages ) {
			return $pages >= 5;
		}

		$args = [
			'post_type' => 'page',
			'meta_key' => '_elementor_edit_mode', // phpcs:ignore WordPress.DB
			'meta_value' => 'builder', // phpcs:ignore WordPress.DB
			'no_found_rows' => true,
		];

		$pages = get_posts( $args );
		$count = count( $pages );

		set_transient( 'jupiterx_elementor_pages_count', $count, WEEK_IN_SECONDS );

		return $count >= 5;
	}

	/**
	 * Delete transient.
	 *
	 * @since 1.17.1
	 * @access public
	 *
	 * @return void
	 */
	public function delete_elementor_pages_transient() {
		delete_transient( 'jupiterx_elementor_pages_count' );
	}

	/**
	 * Get notice details from API.
	 *
	 * @since 1.17.1
	 * @access public
	 *
	 * @return mixed
	 */
	public function get_info() {
		$url = sprintf( $this->api_url, '' );

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return [];
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return [];
		}

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Validate notice info from API.
	 *
	 * @since 1.17.1
	 * @access public
	 *
	 * @param array $info Info from API.
	 *
	 * @return boolean
	 */
	public function validate_info( $info ) {
		if ( ! is_array( $info ) ) {
			return false;
		}

		$fields = [
			'survey_content',
			'url',
			'cta_text',
			'id',
			'status',
		];

		foreach ( $fields as $field ) {
			if ( empty( $info[ $field ] ) ) {
				return false;
			}
		}

		return true;
	}
}

new JupiterX_Admin_Notice_Survey_Notification_Bar();
