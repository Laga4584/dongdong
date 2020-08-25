<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Gateway_Kicc_VBank' ) ) :

	class WC_Gateway_Kicc_VBank extends WC_Gateway_KICC {

		public function __construct( $id = '' ) {
			if ( empty( $id ) ) {
				$id = 'kicc_vbank';
			}

			$this->id = $id;

			parent::__construct();

			$this->pay_type = '22';

			$this->mgr_txtype = array (
				'full'    => '40',
				'partial' => '40',
				'refund'  => '60'
			);

			$this->mgr_subtype = array (
				'cancel' => '',
				'refund' => 'RF01'
			);

			$this->receipt_code = '03';

			if ( empty( $this->settings['title'] ) ) {
				$this->title       = __( '가상계좌', 'pgall-for-woocommerce' );
				$this->description = __( '가상계좌 안내를 통해 무통장입금을 할 수 있습니다.', 'pgall-for-woocommerce' );
			} else {
				$this->title       = $this->settings['title'];
				$this->description = $this->settings['description'];
			}

			$this->supports[] = 'pafw-vbank';
			$this->supports[] = 'pafw-vbank-refund';
		}

		public function get_vbank_list() {
			return array (
				"003" => "기업은행",
				"004" => "국민은행",
				"011" => "농협중앙회",
				"020" => "우리은행",
				"023" => "SC제일은행",
				"026" => "신한은행",
				"031" => "대구은행",
				"032" => "부산은행",
				"039" => "경남은행",
				"071" => "우체국",
				"081" => "하나은행"
			);
		}
		public function process_payment_result( $order, $easyPay ) {
			$deposit_nm = explode( '_', pafw_convert_to_utf8( $easyPay->_easypay_resdata["deposit_nm"] ) );

			$transaction_id = $easyPay->_easypay_resdata["cno"];
			$bank_name      = pafw_convert_to_utf8( $easyPay->_easypay_resdata["bank_nm"] );
			$depositor      = pafw_get( $deposit_nm, '0' );
			$account        = $easyPay->_easypay_resdata["account_no"];
			$va_name        = pafw_get( $deposit_nm, '1' );
			$va_date        = $easyPay->_easypay_resdata["expire_date"];
			$cash_authno    = $easyPay->_easypay_resdata["cash_auth_no"];

			pafw_update_meta_data( $order, '_pafw_vacc_tid', $transaction_id );
			pafw_update_meta_data( $order, '_pafw_vacc_num', $account );
			pafw_update_meta_data( $order, '_pafw_vacc_bank_code', '00' );
			pafw_update_meta_data( $order, '_pafw_vacc_bank_name', $bank_name );
			pafw_update_meta_data( $order, '_pafw_vacc_holder', $depositor );
			pafw_update_meta_data( $order, '_pafw_vacc_depositor', $va_name );
			pafw_update_meta_data( $order, '_pafw_vacc_date', $va_date );
			pafw_update_meta_data( $order, '_pafw_cash_receipts', ! empty( $cash_authno ) ? '발행' : '미발행' );

			$this->add_payment_log( $order, '[ 가상계좌 입금 대기중 ]', array (
				'거래번호' => $transaction_id
			) );

			//가상계좌 주문 접수시 재고 차감여부 확인
			pafw_reduce_order_stock( $order );

			$order->update_status( $this->settings['order_status_after_vbank_payment'] );

			if ( version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
				$order->set_date_paid( null );
				$order->save();
			}
		}
		protected function process_escrow_notification( $order ) {
			$this->add_log( 'process_escrow_notification' );

			try {
				if ( '0000' !== pafw_get( $_POST, 'res_cd' ) ) {
					throw new Exception( pafw_convert_to_utf8( pafw_get( $_POST, 'res_msg' ) ) );
				}

				$order = wc_get_order( pafw_get( $_POST, 'order_no' ) );

				if ( ! $order || '40' != pafw_get( $_POST, 'noti_type' ) || pafw_get( $_POST, 'memb_id' ) != pafw_get( $this->settings, 'merchant_id' ) || pafw_get( $_POST, 'cno' ) != $this->get_transaction_id( $order ) ) {
					throw new Exception( __( '잘못된 요청입니다.', 'pgall-for-woocommerce' ) );
				}


				switch ( pafw_get( $_POST, 'stat_cd' ) ) {
					case 'ES04' :
						$this->process_vbank_notification();
						break;
					default:
						throw new Exception( __( '잘못된 요청입니다.', 'pgall-for-woocommerce' ) );
				}
			} catch ( Exception $e ) {
				$this->add_log( $e->getMessage() . "\n" . print_r( $_REQUEST, true ) );
				die( 'res_cd=5001^res_msg=FAIL' );
			}
		}
		protected function process_vbank_notification() {
			$this->add_log( 'process_vbank_notification' );

			try {
				if ( '0000' !== pafw_get( $_POST, 'res_cd' ) ) {
					throw new Exception( pafw_convert_to_utf8( pafw_get( $_POST, 'res_msg' ) ) );
				}

				$order = wc_get_order( pafw_get( $_POST, 'order_no' ) );

				$noti_type = pafw_get( $_POST, 'noti_type' );

				if ( '30' != $noti_type && ( '40' == $noti_type && 'ES04' != pafw_get( $_POST, 'stat_cd' ) ) ) {
					throw new Exception( __( '잘못된 요청입니다.', 'pgall-for-woocommerce' ) );
				}

				if ( ! $order || pafw_get( $_POST, 'memb_id' ) != pafw_get( $this->settings, 'merchant_id' ) || pafw_get( $_POST, 'cno' ) != $this->get_transaction_id( $order ) ) {
					throw new Exception( __( '잘못된 요청입니다.', 'pgall-for-woocommerce' ) );
				}

				pafw_update_meta_data( $order, '_pafw_vbank_noti_received', 'yes' );
				pafw_update_meta_data( $order, '_pafw_vbank_noti_transaction_date', pafw_get( $_POST, 'tran_date' ) );
				pafw_update_meta_data( $order, '_pafw_vbank_noti_deposit_bank', pafw_convert_to_utf8( pafw_get( $_POST, 'bank_nm' ) ) );
				pafw_update_meta_data( $order, '_pafw_vbank_noti_depositor', pafw_convert_to_utf8( pafw_get( $_POST, 'deposit_nm' ) ) );

				$this->add_payment_log( $order, '[ 가상계좌 입금완료 ]', array (
					'입금시각'  => pafw_get( $_POST, 'tran_date' ),
					'통보아이디' => pafw_get( $_POST, 'tlf_sno' )
				) );

				$order->payment_complete( pafw_get( $_POST, 'cno' ) );
				$order->update_status( $this->settings['order_status_after_payment'] );

				//WC3.0 관련 가상계좌 입금통보시 결제 완료 시간 갱신 처리
				if ( version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
					$order->set_date_paid( current_time( 'timestamp', true ) );
					$order->save();
				}

				do_action( 'pafw_payment_action', 'completed', $order->get_total(), $order, $this );

				die( 'res_cd=0000^res_msg=SUCCESS' );
			} catch ( Exception $e ) {
				$this->add_log( $e->getMessage() . "\n" . print_r( $_REQUEST, true ) );
				die( 'res_cd=5001^res_msg=FAIL' );
			}
		}
		function vbank_refund_request( $mgr_txtype = '', $mgr_subtype = '' ) {

			if ( empty( $mgr_txtype ) ) {
				$mgr_txtype = pafw_get( $this->mgr_txtype, 'refund' );
			}
			if ( empty( $mgr_subtype ) ) {
				$mgr_subtype = pafw_get( $this->mgr_subtype, 'refund' );
			}

			$this->check_shop_order_capability();

			$order = $this->get_order();

			$vbank_lists = $this->get_vbank_list();
			$_REQUEST['refund_acc_num'] = str_replace( '-', '', $_REQUEST['refund_acc_num'] );
			pafw_update_meta_data( $order, '_pafw_vbank_refund_bank_code', $_REQUEST['refund_bank_code'] );
			pafw_update_meta_data( $order, '_pafw_vbank_refund_bank_name', $vbank_lists[ $_REQUEST['refund_bank_code'] ] );
			pafw_update_meta_data( $order, '_pafw_vbank_refund_acc_num', $_REQUEST['refund_acc_num'] );
			pafw_update_meta_data( $order, '_pafw_vbank_refund_acc_name', $_REQUEST['refund_acc_name'] );
			pafw_update_meta_data( $order, '_pafw_vbank_refund_reason', $_REQUEST['refund_reason'] );

			$this->add_log( '가상계좌 환불 처리 시작.' );

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
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_txtype", $mgr_txtype );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_subtype", $mgr_subtype );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_amt", $order->get_total() );
			$easyPay->set_easypay_deli_us( $mgr_data, "org_cno", $this->get_transaction_id( $order ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_msg", $_REQUEST['refund_reason'] );
			$easyPay->set_easypay_deli_us( $mgr_data, "req_ip", $client_ip );
			$easyPay->set_easypay_deli_us( $mgr_data, "req_id", get_current_user_id() );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_bank_cd", $_REQUEST['refund_bank_code'] );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_account", $_REQUEST['refund_acc_num'] );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_depositor", urlencode( $_REQUEST['refund_acc_name'] ) );

			$easyPay->easypay_exec( $this->get_merchant_id(), WC_Gateway_KICC::TRAN_CD_NOR_MGR, $order->get_id(), $client_ip, '' );

			$res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
			$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

			if ( $res_cd == '0000' ) {
				do_action( 'pafw_payment_action', 'cancelled', $order->get_total(), $order, $this );

				$order->update_status( 'refunded', __( '관리자의 요청으로 주문건의 가상계좌 환불처리가 완료되었습니다.', 'pgall-for-woocommerce' ) );
				pafw_update_meta_data( $order, '_pafw_vbank_refunded', 'yes' );
				pafw_update_meta_data( $order, '_pafw_order_cancelled', 'yes' );
				pafw_update_meta_data( $order, '_pafw_cancel_date', current_time( 'mysql' ) );
				$this->add_log( sprintf( '가상계좌 환불처리 요청 성공. 주문번호 : %s', pafw_get_object_property( $order, 'id' ) ) );

				wp_send_json_success( __( '관리자의 요청으로 주문건의 가상계좌 환불처리가 완료되었습니다.', 'pgall-for-woocommerce' ) );
			} else {
				$order->add_order_note( sprintf( __( '가상계좌 환불처리가 실패하였습니다. 결과코드 : %s, 처리메시지 : %s', 'pgall-for-woocommerce' ), $res_cd, $res_msg ) );
				throw new Exception( sprintf( __( '가상계좌 환불처리가 실패하였습니다. 결과코드 : %s, 처리메시지 : %s', 'pgall-for-woocommerce' ), $res_cd, $res_msg ) );
			}
		}
	}

endif;