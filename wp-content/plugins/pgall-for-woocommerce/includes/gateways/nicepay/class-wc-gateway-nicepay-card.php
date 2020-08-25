<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Nicepay_Card' ) ) {

		class WC_Gateway_Nicepay_Card extends WC_Gateway_Nicepay {

			public function __construct() {
				$this->id = 'nicepay_card';

				parent::__construct();

				$this->settings['paymethod'] = 'CARD';

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( '신용카드', 'pgall-for-woocommerce' );
					$this->description = __( '카드사를 통해 결제를 진행합니다.', 'pgall-for-woocommerce' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}

				$this->success_code = '3001';

				$this->supports[] = 'refunds';
			}
			public function process_standard( $order, $nicepay ) {
				pafw_update_meta_data( $order, "_pafw_card_num", $nicepay->m_ResultData['CardNo'] );
				pafw_update_meta_data( $order, "_pafw_card_code", $nicepay->m_ResultData['CardCode'] );
				pafw_update_meta_data( $order, "_pafw_card_name", pafw_convert_to_utf8( $nicepay->m_ResultData['CardName'] ) );

				$this->add_payment_log( $order, '[ 결제 승인 완료 ]', array (
					'거래번호' => $nicepay->m_ResultData['TID']
				) );
			}
		}
	}

} // class_exists function end
