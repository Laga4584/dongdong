<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	class WC_Gateway_KICC extends PAFW_Payment_Gateway {

		public $pay_type;

		const TRAN_CD_NOR_PAYMENT = "00101000";   // 승인(일반, 에스크로)
		const TRAN_CD_NOR_MGR = "00201000";   // 변경(일반, 에스크로)

		public $mgr_txtype = array ();
		public $mgr_subtype = array ();

		public $receipt_code;
		public $dlv_companies;

		protected $key_for_test = array (
			'T5102001'
		);

		public function __construct() {
			$this->master_id = 'kicc';

			$this->pg_title     = __( 'KICC', 'pgall-for-woocommerce' );
			$this->method_title = __( 'KICC', 'pgall-for-woocommerce' );

			$this->dlv_companies = array (
				'DC01' => __( '대한통운', 'pgall-for-woocommerce' ),
				'DC02' => __( 'CJGLS', 'pgall-for-woocommerce' ),
			);

			parent::__construct();
		}

		public static function enqueue_frontend_script() {
			$options = get_option( 'pafw_mshop_kicc' );

			if ( 'sandbox' === pafw_get( $options, 'operation_mode', 'sandbox' ) ) {
				wp_enqueue_script( 'pafw-kicc', 'https://testpg.easypay.co.kr/webpay/EasypayCard_Web.js', array (), PAFW_VERSION );
			} else {
				wp_enqueue_script( 'pafw-kicc', 'https://pg.easypay.co.kr/webpay/EasypayCard_Web.js', array (), PAFW_VERSION );
			}
		}

		public function get_transaction_url( $order ) {
			if ( 'sandbox' === pafw_get( $this->settings, 'operation_mode', 'sandbox' ) ) {
				$receipt_url = 'http://testoffice.easypay.co.kr/receipt/ReceiptBranch.jsp';
			} else {
				$receipt_url = 'https://office.easypay.co.kr/receipt/ReceiptBranch.jsp';
			}

			$receipt_url = add_query_arg( array (
				'controlNo' => $this->get_transaction_id( $order ),
				'payment'   => $this->receipt_code
			), $receipt_url );

			return apply_filters( 'woocommerce_get_transaction_url', $receipt_url, $order, $this );
		}
		function home_dir() {
			return PAFW()->plugin_path() . '/lib/kicc';
		}

		function get_gateway_url() {
			if ( 'sandbox' === pafw_get( $this->settings, 'operation_mode', 'sandbox' ) ) {
				return 'testgw.easypay.co.kr';
			} else {
				return 'gw.easypay.co.kr';
			}
		}

		public function get_log_dir() {
			$upload_dir = wp_upload_dir();

			$path = $upload_dir['basedir'] . '/kicc_log/';

			wp_mkdir_p( $path );

			if ( ! file_exists( $path . '/.htaccess' ) ) {
				$pfile = fopen( $path . '/.htaccess', "w" );
				$txt   = "<Files *.log>\nRequire all denied\n</Files>\n";
				fwrite( $pfile, $txt );
				$txt = "<Files *.log>\ndeny from all\n</Files>\n";
				fwrite( $pfile, $txt );
				fclose( $pfile );
			}

			return $path;
		}
		public function request_payment() {
			$this->add_log( 'Request Payment' );

			try {
				require_once $this->home_dir() . '/easypay_client.php';
				$this->check_requirement();
				//[헤더]
				$tr_cd     = $this->get_post_params( 'tr_cd' );
				$trace_no  = $this->get_post_params( 'trace_no' );
				$order_no  = $this->get_post_params( 'order_no' );
				$g_mall_id = $this->get_merchant_id();
				//[공통]
				$encrypt_data = $this->get_post_params( 'encrypt_data' );
				$sessionkey   = $this->get_post_params( 'sessionkey' );
				$easyPay = new EasyPay_Client();
				$easyPay->clearup_msg();

				$easyPay->set_home_dir( $this->home_dir() );
				$easyPay->set_gw_url( $this->get_gateway_url() );
				$easyPay->set_gw_port( 80 );
				$easyPay->set_log_dir( $this->get_log_dir() );
				$easyPay->set_log_level( 1 );
				$easyPay->set_cert_file( $this->home_dir() . '/pg_cert.pem' );
				$client_ip = $easyPay->get_remote_addr();

				$easyPay->set_trace_no( $trace_no );
				$easyPay->set_snd_key( $sessionkey );
				$easyPay->set_enc_data( $encrypt_data );

				$opt = "option value";
				$easyPay->easypay_exec( $this->get_merchant_id(), $tr_cd, $order_no, $client_ip, $opt );

				$res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
				$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

				if ( $res_cd == "0000" ) {
					$order_no = $easyPay->_easypay_resdata["order_no"];
					$order    = wc_get_order( $order_no );

					if ( $order ) {
						pafw_update_meta_data( $order, "_pafw_payment_method", $this->get_title() );
						pafw_update_meta_data( $order, "_pafw_txnid", $easyPay->_easypay_resdata["cno"] );
						pafw_update_meta_data( $order, "_pafw_payed_date", $easyPay->_easypay_resdata["tran_date"] );
						pafw_update_meta_data( $order, "_pafw_total_price", $easyPay->_easypay_resdata["amount"] );

						$this->process_payment_result( $order, $easyPay );

						$this->payment_complete( $order, $easyPay->_easypay_resdata["cno"] );

						if ( wp_is_mobile() ) {
							wp_safe_redirect( $order->get_checkout_order_received_url() );
							die();
						} else {
							ob_start();
							$redirect_url = $order->get_checkout_order_received_url();
							include_once( 'templates/redirect.php' );
							ob_end_flush();
							die();
						}
					} else {
						throw new Exception( __( '주문이 존재하지 않습니다.', 'pgall-for-woocommerce' ) );
					}
				} else {
					throw new PAFW_Exception( $res_msg, '3002', $res_cd );
				}
			} catch ( Exception $e ) {
				$error_code = '';
				if ( $e instanceof PAFW_Exception ) {
					$error_code = $e->getErrorCode();
				}

				$message = sprintf( __( '[PAFW-ERR-%s] %s', 'pgall-for-woocommerce' ), $e->getCode(), $e->getMessage() );

				$this->add_log( "[오류] " . $message . "\n" . print_r( $_REQUEST, true ) );

				if ( $order ) {
					$order->add_order_note( $message );
					if ( empty( pafw_get_object_property( $order, 'paid_date' ) ) ) {
						$order->update_status( 'failed', __( 'KICC 결제내역을 확인하신 후, 고객에게 연락을 해주시기 바랍니다.', 'pgall-for-woocommerce' ) );
					}
				}

				do_action( 'pafw_payment_fail', $order, ! empty( $error_code ) ? $error_code : $e->getCode(), $e->getMessage() );

				$this->close_payment_window( $message );
			}

		}

		function process_order_pay() {
			wp_send_json_success( $this->process_payment( pafw_get( $_REQUEST, 'order_id' ) ) );
		}

		function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );

			do_action( 'pafw_process_payment', $order );

			ob_start();
			?>
            <div>
                <input type="hidden" name="type" value="request">
                <input type="hidden" name="order_id" value="<?php echo $order->get_id(); ?>">
                <input type="hidden" name="order_key" value="<?php echo $order->get_order_key(); ?>">
                <input type="hidden" name="EP_mall_id" value="<?php echo pafw_get( $this->settings, 'merchant_id' ); ?>">
                <input type="hidden" name="EP_order_no" value="<?php echo pafw_get_object_property( $order, 'id' ); ?>">
                <input type="hidden" name="EP_product_amt" value="<?php echo $order->get_total(); ?>">
                <input type="hidden" name="EP_kiccpopup_close" value="N">
            </div>
			<?php
			$form = ob_get_clean();

			return array (
				'result'       => 'success',
				'request_form' => $form,
				'request_url'  => untrailingslashit( WC()->api_request_url( get_class( $this ), pafw_check_ssl() ) )
			);
		}
		public function get_payment_form( $order_id, $order_key ) {
			$this->check_requirement();

			$this->permission_process();

			$order = $this->get_order( $order_id, $order_key );

			pafw_set_browser_information( $order );
			$this->has_enough_stock( $order );
			$order->set_payment_method( $this );
			if ( is_callable( array ( $order, 'save' ) ) ) {
				$order->save();
			}

			ob_start();
			include( 'templates/payment-form' . ( wp_is_mobile() ? '-mobile' : '' ) . '.php' );

			return ob_get_clean();
		}
		function cancel_request( $order, $msg, $code = "1" ) {
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
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_txtype", pafw_get( $this->mgr_txtype, 'full' ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_subtype", pafw_get( $this->mgr_subtype, 'cancel' ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_amt", $order->get_total() );

			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_tax_amt", (string) $this->get_tax_amount( $order ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_free_amt", (string) $this->get_tax_free_amount( $order ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_vat_amt", (string) $this->get_total_tax( $order ) );

			$easyPay->set_easypay_deli_us( $mgr_data, "org_cno", $this->get_transaction_id( $order ) );
			$easyPay->set_easypay_deli_us( $mgr_data, "mgr_msg", $msg );
			$easyPay->set_easypay_deli_us( $mgr_data, "req_ip", $client_ip );
			$easyPay->set_easypay_deli_us( $mgr_data, "req_id", get_current_user_id() );

			$easyPay->easypay_exec( $this->get_merchant_id(), WC_Gateway_KICC::TRAN_CD_NOR_MGR, $order->get_id(), $client_ip, '' );

			$res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
			$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

			if ( $res_cd == '0000' ) {
				do_action( 'pafw_payment_action', 'cancelled', $order->get_total(), $order, $this );

				return "success";
			} else {
				throw new Exception( '주문취소중 오류가 발생했습니다. [' . $res_cd . '] ' . $res_msg );
			}
		}

		function send_common_return_response() {
			header( 'HTTP/1.1 200 OK' );
			header( "Content-Type: text; charset=euc-kr" );
			header( "Cache-Control: no-cache" );
			header( "Pragma: no-cache" );

			echo '<html><body><form><input type="hidden" name="result" value="0000"></form></body></html>';
			die();
		}
		function get_post_params( $param ) {
			return pafw_get( $_POST, wp_is_mobile() ? 'sp_' . $param : 'EP_' . $param );
		}

		function close_payment_window( $message ) {
			if ( wp_is_mobile() ) {
				wc_add_notice( $message, 'error' );
				wp_safe_redirect( wc_get_page_permalink( 'checkout' ) );
				die();
			} else {
				ob_start();
				include( 'templates/close-payment.php' );
				ob_end_flush();
				die();
			}
		}

		function process_payment_response() {
			$this->add_log( "Process Payment Response" );
			$this->add_log( print_r( $_REQUEST, true ) );

			if ( ! empty( $_REQUEST ) ) {

				if ( ! empty( $_REQUEST['type'] ) ) {
					switch ( $_REQUEST['type'] ) {
						case 'request' :
							echo $this->get_payment_form( $_REQUEST['order_id'], $_REQUEST['order_key'] );
							die();
							break;
						case 'payment' :
							if ( '0000' === $this->get_post_params( 'res_cd' ) ) {
								$this->request_payment();
							} else if ( 'W002' === $this->get_post_params( 'res_cd' ) ) {
								do_action( 'pafw_payment_cancel' );

								$this->close_payment_window( __( '고객이 결제를 취소했습니다.', 'pgall-for-woocommerce' ) );
							} else {
								$res_code    = $this->get_post_params( 'res_cd' );
								$res_message = urldecode( $this->get_post_params( 'res_msg' ) );

								do_action( 'pafw_payment_fail', null, $res_code, $res_message );

								$this->close_payment_window( sprintf( '[PAFW-ERR-%s] %s', $res_code, $res_message ) );
							}

							break;
						case 'vbank_noti' :
							if ( 'Y' == pafw_get( $_POST, 'escrow_yn' ) ) {
								$this->process_escrow_notification();
							} else {
								$this->process_vbank_notification();
							}
							break;
						case "common_return" :
							$this->process_common_return();
							break;
						default :
							$this->add_log( "Request Type 없음 종료.\n" . print_r( $_REQUEST, true ) );
							break;
					}
				} else {
					$this->add_log( "Request Type 없음 종료.\n" . print_r( $_REQUEST, true ) );
					wp_die( __( '결제 요청 실패 : 관리자에게 문의하세요!', 'pgall-for-woocommerce' ) );
				}
			} else {
				$this->add_log( "Request 없음 종료.\n" . print_r( $_REQUEST, true ) );
				wp_die( __( '결제 요청 실패 : 관리자에게 문의하세요!', 'pgall-for-woocommerce' ) );
			}
		}

		function process_common_return() {
		}

		function cancel_payment_request_by_user() {
			do_action( 'pafw_payment_cancel' );
			wp_send_json_success();
		}
		function is_test_key() {
			return in_array( pafw_get( $this->settings, 'merchant_id' ), $this->key_for_test );
		}

		public function get_receipt_popup_params() {
			return array (
				'name'     => 'MEMB_POP_RECEIPT',
				'features' => 'toolbar=0,scroll=1,menubar=0,status=0,resizable=0,width=380,height=700'
			);
		}
		protected function permission_process() {
			chmod( PAFW()->plugin_path() . '/lib/kicc/bin/ep_cli', '0755' );
			chmod( PAFW()->plugin_path() . '/lib/kicc/bin/linux_64/ep_cli', '0755' );
		}

		public function get_merchant_id() {
			return pafw_get( $this->settings, 'merchant_id' );
		}
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			$order = wc_get_order( $order_id );

			if ( $order ) {
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
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_txtype", $this->mgr_txtype['partial'] );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_subtype", pafw_get( $this->mgr_subtype, 'cancel' ) );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_amt", $amount );

				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_tax_flg", 'TG01' );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_tax_amt", (string) $this->get_tax_amount( $order, $amount ) );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_free_amt", (string) $this->get_tax_free_amount( $order, $amount ) );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_vat_amt", (string) $this->get_total_tax( $order, $amount ) );

				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_rem_amt", (string) ( $order->get_total() - $order->get_total_refunded() + $amount ) );
				$easyPay->set_easypay_deli_us( $mgr_data, "org_cno", $this->get_transaction_id( $order ) );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_msg", empty( $reason ) ? __( '관리자 요청에 의한 부분취소', 'pgall-for-woocommerce' ) : $reason );
				$easyPay->set_easypay_deli_us( $mgr_data, "req_ip", $client_ip );
				$easyPay->set_easypay_deli_us( $mgr_data, "req_id", get_current_user_id() );

				$easyPay->easypay_exec( $this->get_merchant_id(), WC_Gateway_KICC::TRAN_CD_NOR_MGR, $order->get_id(), $client_ip, '' );

				$res_cd  = $easyPay->_easypay_resdata["res_cd"];
				$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

				if ( '0000' === $res_cd ) {
					do_action( 'pafw_payment_action', 'cancelled', $amount, $order, $this );

					if ( $order->get_total() - $order->get_total_refunded() <= 0 ) {
						pafw_update_meta_data( $order, '_pafw_order_cancelled', 'yes' );
						pafw_update_meta_data( $order, '_pafw_cancel_date', current_time( 'mysql' ) );

						if ( class_exists( 'WC_Subscriptions_Manager' ) ) {
							WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order );
						}
					}

					$this->add_payment_log( $order, '[ 결제 취소 성공 ]', array (
						'거래요청번호' => $easyPay->_easypay_resdata["cno"],
						'취소금액'   => wc_price( $amount, array ( 'currency' => $order->get_currency() ) )
					) );

					return true;
				} else {
					throw new Exception( $res_msg );
				}
			}

			return false;
		}
	}

}