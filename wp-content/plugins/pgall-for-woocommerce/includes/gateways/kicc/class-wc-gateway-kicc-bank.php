<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Gateway_Kicc_Bank' ) ) :

	class WC_Gateway_Kicc_Bank extends WC_Gateway_KICC {

		public function __construct() {
			$this->id = 'kicc_bank';

			parent::__construct();

			$this->pay_type = '21';

			$this->mgr_txtype = array (
				'full'    => '40',
				'partial' => '33'
			);

			$this->receipt_code = '02';

			if ( empty( $this->settings['title'] ) ) {
				$this->title       = __( '실시간 계좌이체', 'pgall-for-woocommerce' );
				$this->description = __( '실시간 계좌이체를 통해 결제를 할 수 있습니다.', 'pgall-for-woocommerce' );
			} else {
				$this->title       = $this->settings['title'];
				$this->description = $this->settings['description'];
			}
		}
		public function process_payment_result( $order, $easyPay ) {
			$transaction_id = $easyPay->_easypay_resdata["cno"];
			$bank_code      = $easyPay->_easypay_resdata["bank_cd"];
			$bank_name      = pafw_convert_to_utf8( $easyPay->_easypay_resdata["bank_nm"] );
			$cash_authno    = $easyPay->_easypay_resdata["cash_auth_no"];

			pafw_update_meta_data( $order, '_pafw_bank_code', $bank_code );
			pafw_update_meta_data( $order, '_pafw_bank_name', $bank_name );
			pafw_update_meta_data( $order, '_pafw_cash_receipts', ! empty( $cash_authno ) ? '발행' : '미발행' );

			$this->add_payment_log( $order, '[ 결제 승인 완료 ]', array (
				'거래번호' => $transaction_id
			) );
		}
	}

endif;