<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Eximbay_Bestpay' ) ) {

		class WC_Gateway_Eximbay_Bestpay extends WC_Gateway_Eximbay{

			public function __construct() {

				$this->id = 'eximbay_bestpay';
				$this->paymethod = 'P010';

				parent::__construct();

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( 'BestPay', 'pgall-for-woocommerce-eximbay' );
					$this->description = __( 'BestPay로 결제합니다.', 'pgall-for-woocommerce-eximbay' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}

			}
		}
	}

} // class_exists function end
