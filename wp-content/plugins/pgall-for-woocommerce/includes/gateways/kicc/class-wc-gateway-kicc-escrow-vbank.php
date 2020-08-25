<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Gateway_Kicc_Escrow_Vbank' ) ) :

	class WC_Gateway_Kicc_Escrow_Vbank extends WC_Gateway_Kicc_VBank {

		public function __construct() {
			parent::__construct( 'kicc_escrow_vbank' );

			$this->mgr_txtype = array (
				'full'   => '61',
				'refund' => '61'
			);

			$this->mgr_subtype = array (
				'cancel'   => 'ES02',
				'refund'   => 'ES05',
				'shipping' => 'ES07'
			);

			$this->receipt_code = '07';

			if ( empty( $this->settings['title'] ) ) {
				$this->title       = __( '에스크로 가상계좌', 'pgall-for-woocommerce' );
				$this->description = __( '가상계좌 안내를 통해 무통장입금을 할 수 있습니다.', 'pgall-for-woocommerce' );
			} else {
				$this->title       = $this->settings['title'];
				$this->description = $this->settings['description'];
			}

			$this->supports[] = 'pafw-escrow';

			if ( ! empty( pafw_get( $this->settings, 'delivery_company_code' ) ) ) {
				$this->settings['delivery_company_name'] = $this->dlv_companies[ pafw_get( $this->settings, 'delivery_company_code' ) ];
			}
		}

		function vbank_refund_request( $mgr_txtype = '', $mgr_subtype = '' ) {
			$this->check_shop_order_capability();

			$order = $this->get_order();

			$mgr_txtype  = pafw_get( $this->mgr_txtype, 'refund' );
			$mgr_subtype = pafw_get( $this->mgr_subtype, 'refund' );

			if ( 'yes' == pafw_get_meta( $order, '_pafw_escrow_register_delivery_info' ) ) {
				$mgr_subtype = 'ES10';
			}

			parent::vbank_refund_request( $mgr_txtype, $mgr_subtype );
		}
		function escrow_register_delivery_info() {
			$this->check_shop_order_capability();

			$order = $this->get_order();
			$escrow_type     = pafw_get( $_POST, 'escrow_type' );
			$tracking_number = pafw_get( $_POST, 'tracking_number' );

			if ( empty( $tracking_number ) || empty( $escrow_type ) ) {
				throw new Exception( __( '필수 파라미터가 누락되었습니다.', 'pgall-for-woocommerce' ) );
			}

			require_once $this->home_dir() . '/easypay_client.php';

			$easyPay = new EasyPay_Client();
			$easyPay->clearup_msg();

			$easyPay->set_home_dir( $this->home_dir() );
			$easyPay->set_gw_url( $this->get_gateway_url() );
			$easyPay->set_gw_port( 80 );
			$easyPay->set_log_dir( $this->get_log_dir() );
			$easyPay->set_log_level( 1 );
			$easyPay->set_cert_file( $this->home_dir() . '/pg_cert.pem' );
			$client_ip = $easyPay->get_remote_addr();

			$mgr_data = $easyPay->set_easypay_item( "mgr_data" );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_txtype", '61' );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_subtype", pafw_get( $this->mgr_subtype, 'shipping' ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "org_cno", $this->get_transaction_id( $order ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "req_ip", $client_ip );
			$easyPay->set_easypay_deli_us( $mgr_data, "req_id", get_current_user_id() );
			$easyPay->set_easypay_deli_us( $mgr_data, "deli_cd", 'DE02' );
			$easyPay->set_easypay_deli_us( $mgr_data, "deli_corp_cd", pafw_get( $this->settings, 'delivery_company_code' ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "deli_invoice", $tracking_number );

			$easyPay->easypay_exec( $this->get_merchant_id(), WC_Gateway_KICC::TRAN_CD_NOR_MGR, $order->get_id(), $client_ip, '' );

			$res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
			$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

			if ( $res_cd == '0000' ) {
				pafw_update_meta_data( $order, '_pafw_escrow_tracking_number', $tracking_number );
				pafw_update_meta_data( $order, '_pafw_escrow_register_delivery_info', 'yes' );
				pafw_update_meta_data( $order, '_pafw_escrow_register_delivery_time', current_time( 'mysql' ) );

				$order->add_order_note( __( '판매자님께서 고객님의 에스크로 결제 주문을 배송 등록 또는 수정 처리하였습니다.', 'pgall-for-woocommerce' ), true );
				$order->update_status( $this->order_status_after_enter_shipping_number );

				wp_send_json_success( __( '배송등록이 처리되었습니다.', 'pgall-for-woocommerce' ) );
			} else {
				throw new Exception( sprintf( __( '배송등록중 오류가 발생했습니다. [%s] %s', 'pgall-for-woocommerce' ), $res_cd, $res_msg ) );
			}
		}
	}

endif;