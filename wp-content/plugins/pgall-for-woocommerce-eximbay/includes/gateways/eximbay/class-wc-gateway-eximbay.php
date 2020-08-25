<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	class WC_Gateway_Eximbay extends PAFW_Payment_Gateway {

		static $pay_method = array (
			'P000' => 'CreditCard',
			'P101' => 'VISA',
			'P102' => 'MasterCard',
			'P103' => 'AMEX',
			'P104' => 'JCB',
			'P105' => 'UnionPay 비인증',
			'P110' => 'BC카드',
			'P111' => 'KB카드',
			'P112' => '하나카드(구 외환)',
			'P113' => '삼성카드',
			'P114' => '신한카드',
			'P115' => '현대카드',
			'P116' => '롯데카드',
			'P117' => '농협카드',
			'P118' => '하나카드(구 SK)',
			'P119' => '씨티카드',
			'P120' => '우리카드',
			'P121' => '수협카드',
			'P122' => '제주카드',
			'P123' => '전북카드',
			'P124' => '광주카드',
			'P125' => '카카오뱅크',
			'P126' => '케이뱅크',
			'P127' => '미래에셋대우',
			'P128' => '코나카드',
			'P001' => 'PayPal',
			'P002' => 'CUP (UnionPay)',
			'P003' => 'Alipay',
			'P141' => 'WeChat (PC)',
			'P142' => 'WeChat (Mobile)',
			'P143' => 'WeChat (OA)',
			'P006' => '일본 편의점, 인터넷뱅킹 결제',
			'P007' => 'Molpay',
			'P171' => 'Molpay (말레이시아)',
			'P172' => 'Molpay (베트남)',
			'P173' => 'Molpay (태국)',
			'P010' => 'BestPay',
			'P301' => 'BankPay',
			'P303' => 'TOSS',
			'P011' => 'Yandex',
		);
		public $paymethod = '';
		public $issuercountry = '';

		public function __construct() {
			parent::__construct();

			$this->master_id = 'eximbay';
			$this->pg_title     = __( '엑심베이', 'pgall-for-woocommerce-eximbay' );
			$this->method_title = __( '엑심베이', 'pgall-for-woocommerce-eximbay' );
		}
		function __get( $key ) {
			$value = isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : '';

			return $value;
		}

		public function is_available() {
			$is_available = ( 'yes' === $this->enabled );

			if ( WC()->cart && 0 < $this->get_order_total() && 0 < $this->max_amount && $this->max_amount < $this->get_order_total() ) {
				$is_available = false;
			}

			return $is_available;
		}

		function get_request_url() {
			if ( 'production' == pafw_get( $this->settings, 'operation_mode', 'sandbox' ) ) {
				return 'https://secureapi.eximbay.com/Gateway/BasicProcessor.krp';
			} else {
				return 'https://secureapi.test.eximbay.com/Gateway/BasicProcessor.krp';
			}
		}

		function get_refund_url() {
			if ( 'production' == pafw_get( $this->settings, 'operation_mode', 'sandbox' ) ) {
				return 'https://secureapi.eximbay.com/Gateway/DirectProcessor.krp';
			} else {
				return 'https://secureapi.test.eximbay.com/Gateway/DirectProcessor.krp';
			}
		}

		public function get_transaction_url( $order ) {
			$query_string = sprintf( 'transid=%s&ref=%s', $order->get_transaction_id(), $this->get_txnid( $order ) );

			if ( 'production' == pafw_get( $this->settings, 'operation_mode', 'sandbox' ) ) {
				if ( wp_is_mobile() ) {
					return 'https://secureapi.eximbay.com/Gateway/BasicProcessor/2.x/invoice/mdown.jsp?' . $query_string;
				} else {
					return 'https://secureapi.eximbay.com/Gateway/BasicProcessor/2.x/invoice/down.jsp?' . $query_string;
				}
			} else {
				if ( wp_is_mobile() ) {
					return 'https://secureapi.test.eximbay.com/Gateway/BasicProcessor/2.x/invoice/mdown.jsp?' . $query_string;
				} else {
					return 'https://secureapi.test.eximbay.com/Gateway/BasicProcessor/2.x/invoice/down.jsp?' . $query_string;
				}
			}
		}

		public static function enqueue_frontend_script() {
			$options = get_option( 'pafw_mshop_eximbay' );

			wp_enqueue_script( 'eximbayfw-payment', PAFWEB()->plugin_url() . '/assets/gateways/eximbay/js/payment.js', array (), PAFW_VERSION, 'yes' == get_option( 'pafw-script-footer', 'no' ) );
			wp_localize_script( 'eximbayfw-payment', '_eximbayfw', array (
				'payment_window_mode' => pafw_get( $options, 'payment_window_mode', 'popup' )
			) );

			return 'eximbayfw-payment';
		}
		function cancel_request( $order, $msg, $code = "1" ) {
			$refund_id = pafw_get_object_property( $order, 'id' ) . '_' . date( 'YmdHis' );

			$params = array (
				'ver'        => '230',
				'mid'        => pafw_get( $this->settings, 'merchant_id' ),
				'txntype'    => 'REFUND',
				'refundtype' => 'F',
				'ref'        => $this->get_txnid( $order ),
				'cur'        => $order->get_currency(),
				'amt'        => $order->get_total(),
				'refundamt'  => $order->get_total(),
				'transid'    => $order->get_transaction_id(),
				'refundid'   => $refund_id,
				'reason'     => $msg,
				'lang'       => pafw_get( $this->settings, 'language_code', 'KO' ),
				'charset'    => 'UTF-8'
			);

			$params['fgkey'] = $this->make_fgkey( $params );

			$cl = curl_init();

			curl_setopt( $cl, CURLOPT_URL, $this->get_refund_url() );
			curl_setopt( $cl, CURLOPT_CONNECTTIMEOUT, 10 );
			curl_setopt( $cl, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $cl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $cl, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $cl, CURLOPT_MAXREDIRS, 5 );
			curl_setopt( $cl, CURLOPT_POST, 1 );
			curl_setopt( $cl, CURLOPT_POSTFIELDS, http_build_query( $params ) );

			$response = curl_exec( $cl );
			curl_close( $cl );

			$result = array ();
			parse_str( $response, $result );

			if ( '0000' == pafw_get( $result, 'rescode' ) ) {

				do_action( 'pafw_payment_action', 'cancelled', $order->get_total(), $order, $this );

				pafw_update_meta_data( $order, '_pafw_refund_id', $refund_id );
				pafw_update_meta_data( $order, '_pafw_refund_trans_id', pafw_get( $result, 'refundtransid' ) );

				return "success";
			} else {
				throw new Exception( sprintf( '[%s] %s', pafw_get( $result, 'rescode' ), pafw_get( $result, 'resmsg' ) ) );
			}
		}

		function process_return() {
			try {
				$txnid    = pafw_get( $_POST, 'ref' );
				$order_id = $this->get_order_id_from_txnid( $txnid );
				$order    = wc_get_order( $order_id );
				if ( ! $order || ! $this->validate_txnid( $order, $txnid ) ) {
					throw new PAFW_Exception( __( '주문정보가 올바르지 않습니다.', 'pgall-for-woocommerce-eximbay' ), '2002', 'PAFW-2002' );
				}
				if ( $order->get_total() != pafw_get( $_POST, 'amt' ) ) {
					throw new PAFW_Exception( sprintf( __( '주문금액과 결제금액이 틀립니다. [%s], [%s]', 'pgall-for-woocommerce-eximbay' ), $order->get_total(), pafw_get( $_POST, 'amt' ) ), '2003', 'PAFW-2003' );
				}

				$res_code = pafw_get( $_POST, 'rescode' );
				$res_msg  = pafw_get( $_POST, 'resmsg' );
				$fgkey    = pafw_get( $_POST, 'fgkey' );
				if ( '0000' != $res_code ) {
					throw new PAFW_Exception( $res_msg, '0000', $res_code );
				}
				if ( strtolower( $fgkey ) != $this->make_fgkey( $_POST ) ) {
					throw new PAFW_Exception( __( 'Transaction ID가 유효하지 않습니다.', 'pgall-for-woocommerce-eximbay' ), '2004', 'PAFW-2004' );
				}
			} catch ( PAFW_Exception $e ) {
				$res_code = $e->getErrorCode();
				$res_msg  = $e->getMessage();
			}

			ob_start();
			include( 'templates/return.php' );
			ob_end_flush();
			die();
		}

		function make_fgkey( $params ) {
			$secret_key     = pafw_get( $this->settings, 'secret_key', "289F40E6640124B2628640168C3C5464" );
			$sorting_params = "";
			$hash_map       = array ();

			foreach ( $params as $key => $value ) {
				if ( "fgkey" == $key ) {
					continue;
				}
				$hash_map[ $key ] = $value;
			}

			$size = count( $hash_map );
			ksort( $hash_map );
			$counter = 0;
			foreach ( $hash_map as $key => $val ) {
				if ( $counter == $size - 1 ) {
					$sorting_params .= $key . "=" . $val;
				} else {
					$sorting_params .= $key . "=" . $val . "&";
				}
				++$counter;
			}

			$link_buf = $secret_key . "?" . $sorting_params;

			return hash( "sha256", $link_buf );
		}
		function process_status() {
			try {

				$txnid    = pafw_get( $_POST, 'ref' );
				$order_id = $this->get_order_id_from_txnid( $txnid );
				$order    = wc_get_order( $order_id );
				if ( ! $order || ! $this->validate_txnid( $order, $txnid ) ) {
					throw new PAFW_Exception( __( '주문정보가 올바르지 않습니다.', 'pgall-for-woocommerce-eximbay' ), '2002', 'PAFW-2002' );
				}

				$this->has_enough_stock( $order );
				if ( $order->get_total() != pafw_get( $_POST, 'amt' ) ) {
					throw new PAFW_Exception( sprintf( __( '주문금액과 결제금액이 틀립니다. [%s], [%s]', 'pgall-for-woocommerce-eximbay' ), $order->get_total(), pafw_get( $_POST, 'amt' ) ), '2003', 'PAFW-2003' );
				}

				$res_code = pafw_get( $_POST, 'rescode' );
				$res_msg  = pafw_get( $_POST, 'resmsg' );
				$fgkey    = pafw_get( $_POST, 'fgkey' );
				if ( '0000' != $res_code ) {
					throw new PAFW_Exception( sprintf( __( '결제 과정에서 오류가 발생했습니다. [%s] %s', 'pgall-for-woocommerce-eximbay' ), $res_code, $res_msg ), '2004', 'PAFW-2004' );
				}
				if ( strtolower( $fgkey ) != $this->make_fgkey( $_POST ) ) {
					throw new PAFW_Exception( __( 'Transaction ID가 유효하지 않습니다.', 'pgall-for-woocommerce-eximbay' ), '2004', 'PAFW-2004' );
				}
				pafw_update_meta_data( $order, '_pafw_payed_date', pafw_get( $_POST, 'resdt' ) );
				pafw_update_meta_data( $order, "_pafw_txnid", $txnid );
				pafw_update_meta_data( $order, "_pafw_total_price", pafw_get( $_POST, 'amt' ) );

				pafw_update_meta_data( $order, "_pafw_payment_method", pafw_get( $_POST, 'paymethod' ) );
				pafw_update_meta_data( $order, '_payment_method_title', $this->title . ' - ' . self::$pay_method[ pafw_get( $_POST, 'paymethod' ) ] );
				pafw_update_meta_data( $order, "_pafw_card_num", pafw_get( $_POST, 'cardno1' ) . '-****-****-' . pafw_get( $_POST, 'cardno4' ) );
				pafw_update_meta_data( $order, "_pafw_card_name", pafw_get( $_POST, 'cardholder' ) );

				if ( 'Registered' == pafw_get( $_POST, 'status' ) ) {
					$this->add_payment_log( $order, sprintf( '입금대기중 - <a target="_blank" href="%s">입금안내보기</a>', pafw_get( $_POST, 'paymentURL' ) ) );

					pafw_reduce_order_stock( $order );
					$order->update_status( 'on-hold' );

					pafw_update_meta_data( $order, "_pafw_payment_url", pafw_get( $_POST, 'paymentURL' ) );

					pafweb_register_cancel_unpaid_order_action( $order->get_id(), pafw_get( $this->settings, 'payment_due', 7 ) );
				} else {
					if ( ! empty( pafw_get( $_POST, 'authcode' ) ) ) {
						$this->add_payment_log( $order, '[ 결제 승인 완료 ]', array (
							'승인번호' => pafw_get( $_POST, 'authcode' )
						) );
					} else if ( 'on-hold' == $order->get_status() ) {
						$this->add_payment_log( $order, '[ 입금완료 ]' );
					}

					$this->payment_complete( $order, pafw_get( $_POST, 'transid' ) );
				}

				echo 'rescode=0000&resmsg=Success';
				die();
			} catch ( Exception $e ) {
				$error_code = '';
				if ( $e instanceof PAFW_Exception ) {
					$error_code = $e->getErrorCode();
				}

				$message = sprintf( __( '[PAFW-ERR-%s] %s', 'pgall-for-woocommerce-eximbay' ), $e->getCode(), $e->getMessage() );

				if ( $order ) {
					$order->add_order_note( $message );
					if ( empty( pafw_get_object_property( $order, 'paid_date' ) ) ) {
						$order->update_status( 'failed', __( '엑심베이 결제내역을 확인하신 후, 고객에게 연락을 해주시기 바랍니다.', 'pgall-for-woocommerce-eximbay' ) );
					}
				}

				do_action( 'pafw_payment_fail', $order, ! empty( $error_code ) ? $error_code : $e->getCode(), $e->getMessage() );

				echo 'rescode=' . $e->getCode() . '&resmsg=' . $e->getMessage();
				die();
			}
		}

		function process_finish() {
			try {

				$txnid    = pafw_get( $_POST, 'ref' );
				$order_id = $this->get_order_id_from_txnid( $txnid );
				$order    = wc_get_order( $order_id );
				if ( ! $order || ! $this->validate_txnid( $order, $txnid ) ) {
					throw new PAFW_Exception( __( '주문정보가 올바르지 않습니다.', 'pgall-for-woocommerce-eximbay' ), '2002', 'PAFW-2002' );
				}
				if ( $order->get_total() != pafw_get( $_POST, 'amt' ) ) {
					throw new PAFW_Exception( sprintf( __( '주문금액과 결제금액이 틀립니다. [%s], [%s]', 'pgall-for-woocommerce-eximbay' ), $order->get_total(), pafw_get( $_POST, 'amt' ) ), '2003', 'PAFW-2003' );
				}

				$res_code = pafw_get( $_POST, 'rescode' );
				$res_msg  = pafw_get( $_POST, 'resmsg' );
				if ( '0000' != $res_code ) {
					throw new PAFW_Exception( sprintf( __( '결제 과정에서 오류가 발생했습니다. [%s] %s', 'pgall-for-woocommerce-eximbay' ), $res_code, $res_msg ), '2004', 'PAFW-2004' );
				}

				wp_safe_redirect( $order->get_checkout_order_received_url() );
			} catch ( Exception $e ) {
				$error_code = '';
				if ( $e instanceof PAFW_Exception ) {
					$error_code = $e->getErrorCode();
				}

				$message = sprintf( __( '[PAFW-ERR-%s] %s', 'pgall-for-woocommerce-eximbay' ), $e->getCode(), $e->getMessage() );

				wc_add_notice( $message, 'error' );

				wp_safe_redirect( pafw_get( $_REQUEST, 'param1', $_SERVER['HTTP_REFERER'] ) );
			}
			die();
		}
		function process_payment_response() {
			try {
				$this->add_log( 'Process Payment Response : ' . $_REQUEST['type'] );
				$this->add_log( print_r( $_REQUEST, true ) );

				if ( empty( $_REQUEST['type'] ) ) {
					throw new PAFW_Exception( __( '잘못된 요청입니다.', 'pgall-for-woocommerce-eximbay' ), '1001', 'PAFW-1001' );
				}

				switch ( $_REQUEST['type'] ) {
					case 'request' :
						ob_start();
						include( 'templates/request.php' );
						ob_end_flush();
						die();
						break;
					case 'return' :
						$this->process_return();
						break;
					case 'status' :
						$this->process_status();
						break;
					case 'finish' :
						$this->process_finish();
						break;
				}

			} catch ( Exception $e ) {
				$error_code = '';
				if ( $e instanceof PAFW_Exception ) {
					$error_code = $e->getErrorCode();
				}

				$message = sprintf( __( '[PAFW-ERR-%s] %s', 'pgall-for-woocommerce-eximbay' ), $e->getCode(), $e->getMessage() );
				$this->add_log( "[오류] " . $message . "\n" . print_r( $_REQUEST, true ) );

				wc_add_notice( $message, 'error' );

				$error_message = $message;
				$redirect_url  = wc_get_page_permalink( 'checkout' );
				ob_start();
				include( 'templates/payment_error.php' );
				echo ob_get_clean();
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

		function get_order_pay_form() {
			$result = self::get_payment_form();

			if ( isset( $result['result'] ) && 'success' === $result['result'] ) {
				wp_send_json_success( $result['payment_form'] );
			} else {
				$notices = wc_get_notices( 'error' );
				if ( ! empty( $notices ) ) {
					wc_clear_notices();
					wp_send_json_error( implode( '<br>', $notices ) );
				} else {
					wp_send_json_error( __( '잘못된 요청입니다.', 'pgall-for-woocommerce-eximbay' ) );
				}
			}
		}
		function get_payment_form( $order_id = null, $order_key = null ) {

			try {
				$this->check_requirement();

				$order = $this->get_order( $order_id, $order_key );

				pafw_set_browser_information( $order );
				$this->has_enough_stock( $order );
				$order->set_payment_method( $this );

				if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
					$order->save();
				}

				ob_start();
				include( 'templates/payment_form.php' );
				$form_tag = ob_get_clean();

				return array (
					'result'       => 'success',
					'payment_form' => '<div data-id="mshop-payment-form" style="display:none">' . $form_tag . '</div>'
				);
			} catch ( Exception $e ) {
				wc_add_notice( $e->getMessage(), 'error' );
			}
		}

		public function get_merchant_id() {
			return pafw_get( $this->settings, 'merchant_id' );
		}
		public function thankyou_page( $order_id ) {
			$order = wc_get_order( $order_id );

			do_action( 'pafw_thankyou_page', $order );

			wc_get_template( 'pafw/thankyou_page.php', array ( 'order' => $order, 'payment_method' => $this->id, 'title' => $this->title ), '', PAFWEB()->template_path() );
		}

		public function woocommerce_email_before_order_table( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( $order && 'on-hold' == $order->get_status() && $this->id == pafw_get_object_property( $order, 'payment_method' ) ) {
				wc_get_template( 'pafw/vbank_acc_info.php', array ( 'order' => $order ), '', PAFWEB()->template_path() );
			}
		}

		function woocommerce_view_order( $order_id, $order ) {
			wc_get_template( 'pafw/vbank_acc_info.php', array ( 'order' => $order ), '', PAFWEB()->template_path() );
		}
	}
}