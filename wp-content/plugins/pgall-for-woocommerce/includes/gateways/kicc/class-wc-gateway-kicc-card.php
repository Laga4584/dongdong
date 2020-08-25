<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Gateway_Kicc_Card' ) ) :

	class WC_Gateway_Kicc_Card extends WC_Gateway_KICC {

		public function __construct() {
			$this->id = 'kicc_card';

			parent::__construct();

			$this->pay_type = '11';

			$this->mgr_txtype = array (
				'full'    => '40',
				'partial' => '32'
			);

			$this->receipt_code = '01';

			if ( empty( $this->settings['title'] ) ) {
				$this->title       = __( '신용카드 결제', 'pgall-for-woocommerce' );
				$this->description = __( '구글크롬, IE, Safari 에서 결제 가능한 웹표준 결제 입니다 결제를 진행해 주세요.', 'pgall-for-woocommerce' );
			} else {
				$this->title       = $this->settings['title'];
				$this->description = $this->settings['description'];
			}

			$this->supports[] = 'refunds';
		}
		public function process_payment_result( $order, $easyPay ) {
			$transaction_id = $easyPay->_easypay_resdata["cno"];
			$card_no        = $easyPay->_easypay_resdata["card_no"];
			$card_cd        = $easyPay->_easypay_resdata["issuer_cd"];
			$card_name      = pafw_convert_to_utf8( $easyPay->_easypay_resdata["issuer_nm"] );
			$acqu_cd        = $easyPay->_easypay_resdata["card_gubun"];

			pafw_update_meta_data( $order, "_pafw_card_num", $card_no );          //카드번호
			pafw_update_meta_data( $order, "_pafw_card_code", $card_cd );        //신용카드사 코드
			pafw_update_meta_data( $order, "_pafw_card_bank_code", $acqu_cd );        //신용카드 발급사 코드
			pafw_update_meta_data( $order, "_pafw_card_name", $card_name );    //신용카드사명

			$this->add_payment_log( $order, '[ 결제 승인 완료 ]', array (
				'거래번호' => $transaction_id
			) );
		}

	}

endif;