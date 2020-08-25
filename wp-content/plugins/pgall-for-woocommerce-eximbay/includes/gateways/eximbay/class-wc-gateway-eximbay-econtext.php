<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Eximbay_Econtext' ) ) {

		class WC_Gateway_Eximbay_Econtext extends WC_Gateway_Eximbay {

			public function __construct() {

				$this->id        = 'eximbay_econtext';
				$this->paymethod = 'P006';

				parent::__construct();

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( '일본 편의점', 'pgall-for-woocommerce-eximbay' );
					$this->description = __( '일본 편의점(Econtext) 결제수단으로 결제합니다.', 'pgall-for-woocommerce-eximbay' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}
			}
		}
	}

}
