<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class PAFWEB_Ajax {
	public static function init() {
		self::add_ajax_events();
	}
	public static function add_ajax_events() {

		$ajax_events = array (
		);

		if ( is_admin() ) {
			$ajax_events = array_merge( $ajax_events, array (
				'update_eximbay_settings' => false,
			) );
		}

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_' . PAFW()->slug() . '-' . $ajax_event, array ( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_' . PAFW()->slug() . '-' . $ajax_event, array ( __CLASS__, $ajax_event ) );
			}
		}
	}

	public static function update_eximbay_settings() {
		WC_Gateway_PAFW_Eximbay::update_settings();
	}
}

//초기화 수행
PAFWEB_Ajax::init();