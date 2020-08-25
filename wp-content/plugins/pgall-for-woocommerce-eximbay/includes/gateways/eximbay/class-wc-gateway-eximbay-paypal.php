<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Eximbay_Paypal' ) ) {

		class WC_Gateway_Eximbay_Paypal extends WC_Gateway_Eximbay{

			public function __construct() {

				$this->id = 'eximbay_paypal';
				$this->paymethod = 'P001';

				parent::__construct();

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( '페이팔', 'pgall-for-woocommerce-eximbay' );
					$this->description = __( '페이팔로 결제합니다.', 'pgall-for-woocommerce-eximbay' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}

			}
		}
	}

} // class_exists function end
