<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {
	class WC_Gateway_Nicepay extends PAFW_Payment_Gateway {

		public static $log;

		protected $success_code;

		protected $key_for_test = array (
			'nicepay00m',
			'nictest04m'
		);
		public function __construct() {
			$this->master_id = 'nicepay';

			$this->view_transaction_url = 'https://npg.nicepay.co.kr/issue/IssueLoaderMail.do?TID=%s&type=0';

			$this->pg_title     = __( '나이스페이', 'pgall-for-woocommerce' );
			$this->method_title = __( '나이스페이', 'pgall-for-woocommerce' );

			parent::__construct();
		}

		public static function enqueue_frontend_script() {
			wp_enqueue_script( 'pafw-nicepay', '//web.nicepay.co.kr/v3/webstd/js/nicepay-2.0.js' );

			if ( ! is_checkout_pay_page() ) {
				wp_register_style( 'nfw-style', PAFW()->plugin_url() . '/assets/gateways/nicepay/css/style.css' );
				wp_enqueue_style( 'nfw-style' );
			}
		}

		function load_library() {
			require_once PAFW()->plugin_path() . '/lib/nicepay/NicepayLite.php';
		}
		function check_requirement() {

			parent::check_requirement();

			if ( ! file_exists( PAFW()->plugin_path() . "/lib/nicepay/NicepayLite.php" ) ) {
				throw new Exception( __( '[ERR-PAFW-0003] NicepayLite.php 파일이 없습니다. 사이트 관리자에게 문의하여 주십시오.', 'pgall-for-woocommerce' ) );
			}
		}
		function get_order_from_txnid( $txnid ) {
			$order = wc_get_order( $this->get_order_id_from_txnid( $txnid ) );

			if ( ! $order ) {
				throw new PAFW_Exception( __( '유효하지않은 주문입니다.', 'pgall-for-woocommerce' ), '1001', 'PAFW-1001' );
			}
			$this->validate_order_status( $order );
			if ( ! $this->validate_txnid( $order, $txnid ) ) {
				throw new PAFW_Exception( sprintf( __( '유효하지 않은 주문번호(%s) 입니다.', 'pgall-for-woocommerce' ), $txnid ), '1002', 'PAFW-1002' );
			}

			return $order;
		}
		function get_payment_description( $paymethod ) {
			switch ( $paymethod ) {
				case "card":
					return __( '신용카드', 'pgall-for-woocommerce' );
					break;
				case "bank":
					return __( '실시간계좌이체', 'pgall-for-woocommerce' );
					break;
				case "vbank":
					return __( '가상계좌', 'pgall-for-woocommerce' );
					break;
				case "escrowbank":
					return __( '휴대폰', 'pgall-for-woocommerce' );
					break;
				default:
					return $paymethod;
					break;
			}
		}
		function cancel_request( $order, $msg, $code = "1" ) {
			$this->load_library();

			$nicepay = new NicepayLite;

			$nicepay->m_NicepayHome       = $this->get_nicepay_log_path();
			$nicepay->m_ActionType        = "CLO";
			$nicepay->m_CancelAmt         = $order->get_total();
			$nicepay->m_SupplyAmt         = $this->get_tax_amount( $order );
			$nicepay->m_GoodsVat          = $this->get_total_tax( $order );
			$nicepay->m_ServiceAmt        = 0;
			$nicepay->m_TaxFreeAmt        = $this->get_tax_free_amount( $order );
			$nicepay->m_TID               = $this->get_transaction_id( $order );
			$nicepay->m_CancelMsg         = $msg;
			$nicepay->m_PartialCancelCode = '0';
			$nicepay->m_CancelPwd         = $this->settings['cancel_pw'];
			$nicepay->m_ssl               = "true";
			$nicepay->m_charSet           = "UTF8";

			$nicepay->startAction();

			//취소 처리 결과 확인
			$resultCode = trim( $nicepay->m_ResultData["ResultCode"] );
			$resultMsg  = pafw_convert_to_utf8( $nicepay->m_ResultData["ResultMsg"] );

			if ( $resultCode == "2001" ) {
				do_action( 'pafw_payment_action', 'cancelled', $order->get_total(), $order, $this );

				return "success";
			} else {
				throw new Exception( $resultMsg );
			}
		}
		function process_response_payment( $posted ) {
			$this->check_requirement();

			try {
				$this->load_library();

				$order = $this->get_order_from_txnid( $_REQUEST['Moid'] );
				$this->has_enough_stock( $order );
				$nicepay = new NicepayLite;

				$nicepay->m_NicepayHome = $this->get_nicepay_log_path();               // 로그 디렉토리 설정
				$nicepay->m_ActionType  = "PYO";
				$nicepay->m_charSet     = "UTF8";
				$nicepay->m_ssl         = "true";
				$nicepay->m_Price       = $order->get_total();
				$nicepay->m_MID         = $this->get_merchant_id();
				$nicepay->m_LicenseKey  = $this->settings['merchant_key'];
				$nicepay->m_PayMethod   = $_REQUEST['PayMethod'];
				$nicepay->startAction();

				$result_code    = $nicepay->m_ResultData['ResultCode'];
				$result_message = pafw_convert_to_utf8( $nicepay->m_ResultData['ResultMsg'] );

				$this->add_log( "결제 승인 결과\n" . print_r( array (
						'resultCode' => $result_code,
						'resultMsg'  => $result_message
					), true )
				);

				//성공시 나이스페이로 결제 성공 전달
				if ( $result_code == $this->success_code ) {
					pafw_update_meta_data( $order, "_pafw_payment_method", $this->paymethod );
					pafw_update_meta_data( $order, "_pafw_txnid", $nicepay->m_ResultData['Moid'] );
					pafw_update_meta_data( $order, "_pafw_payed_date", $nicepay->m_ResultData['AuthDate'] );
					pafw_update_meta_data( $order, "_pafw_total_price", intval( $nicepay->m_ResultData['Amt'] ) );

					$this->process_standard( $order, $nicepay );

					$this->payment_complete( $order, $nicepay->m_ResultData['TID'] );
				} else {
					throw new PAFW_Exception( sprintf( __( '결제 승인 요청 과정에서 오류가 발생했습니다. 관리자에게 문의해주세요. 오류코드(%s), 오류메시지(%s)', 'pgall-for-woocommerce' ), $result_code, $result_message ), '3004', $result_code );
				}
			} catch ( Exception $e ) {
				$error_code = '';
				if ( $e instanceof PAFW_Exception ) {
					$error_code = $e->getErrorCode();
				}

				$message = sprintf( __( '[PAFW-ERR-%s] %s', 'pgall-for-woocommerce' ), $e->getCode(), $e->getMessage() );
				$this->add_log( "[오류] " . $message . "\n" . print_r( $_REQUEST, true ) );

				wc_add_notice( $message, 'error' );
				if ( $order ) {
					$order->add_order_note( $message );
					if ( empty( pafw_get_object_property( $order, 'paid_date' ) ) ) {
						$order->update_status( 'failed', __( '나이스페이 결제내역을 확인하신 후, 고객에게 연락을 해주시기 바랍니다.', 'pgall-for-woocommerce' ) );
					}
				}

				do_action( 'pafw_payment_fail', $order, ! empty( $error_code ) ? $error_code : $e->getCode(), $e->getMessage() );

				wp_safe_redirect( wc_get_page_permalink( 'checkout' ) );
				die();
			}
		}

		function process_order_pay() {
			wp_send_json_success( $this->get_payment_form() );
		}

		function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );

			do_action( 'pafw_process_payment', $order );

			return $this->get_payment_form( $order_id, pafw_get_object_property( $order, 'order_key' ) );
		}
		function get_payment_form( $order_id = null, $order_key = null ) {
			try {

				$this->check_requirement();

				$order = $this->get_order( $order_id, $order_key );

				pafw_set_browser_information( $order );
				$this->has_enough_stock( $order );
				$return_url  = $this->get_api_url( 'payment' ); //Return URL 가져오기
				$userid      = get_current_user_id();
				$txnid       = $this->get_txnid( $order );
				$productinfo = $this->make_product_info( $order );
				$order_total = $order->get_total();
				$order->set_payment_method( $this );

				if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
					$order->save();
				}
				$merchantID  = $this->merchant_id;         // 상점아이디
				$merchantKey = $this->merchant_key;        // 상점키
				$goodsCnt    = $order->get_item_count();               // 결제상품개수
				$goodsName   = esc_attr( $productinfo );                 // 결제상품명
				$price       = $order_total;                           // 결제상품금액
				$buyerName   = pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) );
				$buyerTel    = pafw_get_customer_phone_number( $order );      // 구매자연락처
				$buyerEmail  = pafw_get_object_property( $order, 'billing_email' );       // 구매자메일주소
				$moid        = $txnid;                                 // 상품주문번호
				$charset     = 'utf-8';

				$ediDate    = date( "YmdHis" );
				$hashString = bin2hex( hash( 'sha256', $ediDate . $merchantID . $price . $merchantKey, true ) );
				$ip         = $_SERVER['REMOTE_ADDR'];

				ob_start();
				include( 'templates/payment_form' . ( wp_is_mobile() ? '_mobile' : '' ) . '.php' );
				$form_tag = ob_get_clean();

				return array (
					'result'       => 'success',
					'payment_form' => '<div data-id="mshop-payment-form" style="display:none">' . $form_tag . '</div>'
				);
			} catch ( Exception $e ) {
				wc_add_notice( $e->getMessage(), 'error' );
			}
		}
		function process_payment_response() {

			try {
				$this->add_log( 'Process Payment Response : ' . ! empty( $_REQUEST['type'] ) ? $_REQUEST['type'] : '' );

				if ( ! empty( $_REQUEST ) ) {

					header( 'HTTP/1.1 200 OK' );
					header( "Content-Type: text; charset=utf-8" );
					header( "Cache-Control: no-cache" );
					header( "Pragma: no-cache" );

					if ( ! empty( $_REQUEST['type'] ) ) {
						if ( strpos( $_REQUEST['type'], '?' ) !== false ) {
							$return_type      = explode( '?', $_REQUEST['type'] );
							$_REQUEST['type'] = $return_type[0];
						} else {
							$return_type = explode( ',', $_REQUEST['type'] );
						}

						$res_moid = pafw_get( $_REQUEST, 'Moid', pafw_get( $_REQUEST, 'MOID' ) );

						//전달값에서 주문번호 추출
						if ( $res_moid ) {
							$orderid = explode( '_', $res_moid );
						}

						//주문번호 분리하여 주문 로딩
						if ( ! empty( $orderid ) ) {
							$orderid = (int) $orderid[0];
							$order   = wc_get_order( $orderid );
						} else {
							throw new Exception( '주문번호 없음' );
						}

						if ( 'vbank_noti' == $return_type[0] ) {
							$this->process_vbank_notification();
						} else if ( 'payment' == $return_type[0] && '0000' == $_REQUEST['AuthResultCode'] ) {
							$this->process_response_payment( $_POST );
							$this->redirect_page( $orderid );
						} else {
							$message = sprintf( __( '[PAFW-ERR-%s] %s', 'pgall-for-woocommerce' ), $_REQUEST['AuthResultCode'], pafw_convert_to_utf8( $_REQUEST['AuthResultMsg'] ) );
							$this->add_log( "[오류] " . $message . "\n" . print_r( $_REQUEST, true ) );

							wc_add_notice( $message, 'error' );

							$this->redirect_page( $orderid );
						}
					} else {
						throw new Exception( 'Request Type 없음' );
					}
				} else {
					throw new Exception( 'Request 없음' );
				}
			} catch ( Exception $e ) {
				$this->add_log( "[오류] " . $e->getMessage() . "\n" . print_r( $_REQUEST, true ) );
				wp_die( __( '결제 요청 실패 : 관리자에게 문의하세요!', 'pgall-for-woocommerce' ) );
			}
		}
		public static function get_nicepay_log_path() {
			$upload_dir = wp_upload_dir();

			$path = $upload_dir['basedir'] . '/nicepay_log/';
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

		function cancel_payment_request_by_user() {
			do_action( 'pafw_payment_cancel' );
			wp_send_json_success();
		}
		function is_test_key() {
			return in_array( pafw_get( $this->settings, 'merchant_id' ), $this->key_for_test );
		}

		public function get_receipt_popup_params() {
			return array (
				'name'     => 'popupIssue',
				'features' => 'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540'
			);
		}

		public function get_merchant_id() {
			return pafw_get( $this->settings, 'merchant_id' );
		}
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			$order = wc_get_order( $order_id );

			if ( $order ) {
				$this->load_library();

				$nicepay = new NicepayLite;

				$nicepay->m_NicepayHome       = $this->get_nicepay_log_path();
				$nicepay->m_ActionType        = "CLO";
				$nicepay->m_CancelAmt         = $amount;
				$nicepay->m_SupplyAmt         = $this->get_tax_amount( $order, $amount );
				$nicepay->m_GoodsVat          = $this->get_total_tax( $order, $amount );
				$nicepay->m_ServiceAmt        = 0;
				$nicepay->m_TaxFreeAmt        = $this->get_tax_free_amount( $order, $amount );
				$nicepay->m_TID               = $this->get_transaction_id( $order );
				$nicepay->m_CancelMsg         = empty( $reason ) ? __( '관리자 환불', '#PKGNAME##' ) : $reason;
				$nicepay->m_PartialCancelCode = '1';
				$nicepay->m_CancelPwd         = $this->settings['cancel_pw'];
				$nicepay->m_ssl               = "true";
				$nicepay->m_charSet           = "UTF8";

				$nicepay->startAction();

				//취소 처리 결과 확인
				$resultCode = trim( $nicepay->m_ResultData["ResultCode"] );
				$resultMsg  = pafw_convert_to_utf8( $nicepay->m_ResultData["ResultMsg"] );

				if ( $resultCode == "2001" ) {
					do_action( 'pafw_payment_action', 'cancelled', $amount, $order, $this );

					if ( $order->get_total() - $order->get_total_refunded() <= 0 ) {
						pafw_update_meta_data( $order, '_pafw_order_cancelled', 'yes' );
						pafw_update_meta_data( $order, '_pafw_cancel_date', current_time( 'mysql' ) );

						if ( class_exists( 'WC_Subscriptions_Manager' ) ) {
							WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order );
						}
					}

					$this->add_payment_log( $order, '[ 결제 취소 성공 ]', array (
						'거래요청번호' => $nicepay->m_ResultData["TID"],
						'취소금액'   => wc_price( $amount, array ( 'currency' => $order->get_currency() ) )
					) );

					return true;
				} else {
					throw new Exception( $resultMsg );
				}
			}

			return false;
		}
	}
}