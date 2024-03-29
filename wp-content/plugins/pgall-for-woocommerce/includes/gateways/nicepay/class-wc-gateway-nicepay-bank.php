<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Nicepay_Bank' ) ) {

		class WC_Gateway_Nicepay_Bank extends WC_Gateway_Nicepay {

			public function __construct() {
				$this->id = 'nicepay_bank';

				parent::__construct();

				$this->settings['paymethod'] = 'BANK';

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( '실시간계좌이체', 'pgall-for-woocommerce' );
					$this->description = __( '계좌에서 바로 결제하는 실시간 계좌이체 입니다.', 'pgall-for-woocommerce' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}

				$this->success_code = '4000';
			}
			public function process_standard( $order, $nicepay ) {
				pafw_update_meta_data( $order, '_pafw_bank_code', $nicepay->m_ResultData['BankCode'] );
				pafw_update_meta_data( $order, '_pafw_bank_name', pafw_convert_to_utf8( $nicepay->m_ResultData['BankName'] ) );
				pafw_update_meta_data( $order, '_pafw_cash_receipts', '0' != $nicepay->m_ResultData['RcptType'] ? '발행' : '미발행' );

				$this->add_payment_log( $order, '[ 결제 승인 완료 ]', array (
					'거래번호' => $nicepay->m_ResultData['TID']
				) );
			}
		}
	}

} // class_exists function end
