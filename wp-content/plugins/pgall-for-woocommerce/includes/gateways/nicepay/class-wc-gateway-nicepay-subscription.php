<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Nicepay_Subscription' ) ) {

		class WC_Gateway_Nicepay_Subscription extends WC_Gateway_Nicepay {

			const PAY_METHOD_BILLKEY = "BILLKEY";
			const PAY_METHOD_BILL = "BILL";
			const ACTION_PAYMENT = "PYO";
			const ACTION_CANCEL = "CLO";

			public function __construct() {
				$this->id = 'nicepay_subscription';

				parent::__construct();

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( '나이스페이 정기결제', 'pgall-for-woocommerce' );
					$this->description = __( '나이스페이 정기결제를 진행합니다.', 'pgall-for-woocommerce' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}

				$this->countries = array ( 'KR' );
				$this->supports  = array (
					'products',
					'subscriptions',
					'multiple_subscriptions',
					'subscription_cancellation',
					'subscription_suspension',
					'subscription_reactivation',
					'subscription_amount_changes',
					'subscription_date_changes',
					'subscription_payment_method_change_customer',
					'pafw',
					'refunds',
					'pafw_additional_charge',
					'pafw_bill_key_management'
				);

				add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array ( $this, 'woocommerce_scheduled_subscription_payment' ), 10, 2 );
				add_action( 'woocommerce_subscription_status_cancelled', array ( $this, 'cancel_subscription' ) );
				add_action( 'woocommerce_subscription_cancelled_' . $this->id, array ( $this, 'cancel_subscription' ) );

				add_action( 'woocommerce_subscriptions_pre_update_payment_method', array ( $this, 'maybe_remove_subscription_cancelled_callback' ), 10, 3 );
				add_action( 'woocommerce_subscription_payment_method_updated', array ( $this, 'maybe_reattach_subscription_cancelled_callback' ), 10, 3 );
			}

			function adjust_settings() {
				$this->settings['merchant_id']    = $this->settings['subscription_merchant_id'];
				$this->settings['merchant_key']   = $this->settings['subscription_merchant_key'];
				$this->settings['cancel_pw']      = $this->settings['subscription_cancel_pw'];
				$this->settings['operation_mode'] = $this->settings['operation_mode_subscription'];
				$this->settings['test_user_id']   = $this->settings['test_user_id_subscription'];
			}

			public function payment_fields() {
				if ( $this->is_available() ) {
					$gateway = $this;
					ob_start();
					include( 'templates/form-payment-fields.php' );
					ob_end_flush();
				}
			}

			function load_library() {
				require_once PAFW()->plugin_path() . '/lib/nicepay_subscription/lib/NicepayLite.php';
			}

			function check_requirement() {

				parent::check_requirement();

				if ( ! file_exists( PAFW()->plugin_path() . "/lib/nicepay_subscription/lib/NicepayLite.php" ) ) {
					throw new Exception( __( '[ERR-PAFW-0003] NicepayLite.php 파일이 없습니다. 사이트 관리자에게 문의하여 주십시오.', 'pgall-for-woocommerce' ) );
				}
			}
			public function issue_bill_key( $order, $payment_info ) {
				$this->check_requirement();

				$this->load_library();

				$subscriptions = array ();

				$user_id = $order ? pafw_get_object_property( $order, 'customer_id' ) : get_current_user_id();

				if ( 'subscription' == pafw_get( $this->settings, 'management_batch_key', 'subscription' ) ) {
					if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order ) ) {
						$subscriptions = array ( $order );
					} else {
						$subscriptions = wcs_get_subscriptions_for_order( pafw_get_object_property( $order, 'id' ), array ( 'order_type' => 'any' ) );

						if ( empty( $subscriptions ) ) {
							throw new Exception( __( '[오류] 정기결제 관련 정보를 찾을 수 없습니다.', 'pgall-for-woocommerce' ), '1001' );
						}
					}
				}

				$nicepay                = new NicepayLite;
				$nicepay->m_LicenseKey  = pafw_get( $this->settings, 'merchant_key' );
				$nicepay->m_NicepayHome = $this->get_nicepay_log_path();
				$nicepay->m_MID         = pafw_get( $this->settings, 'merchant_id' );
				$nicepay->m_PayMethod   = self::PAY_METHOD_BILLKEY;
				$nicepay->m_ssl         = true;
				$nicepay->m_ActionType  = self::ACTION_PAYMENT;
				$nicepay->m_CardNo      = $payment_info['pafw_nicepay_card_no'];
				$nicepay->m_ExpYear     = substr( $payment_info['pafw_nicepay_expiry_year'], 2, 2 );
				$nicepay->m_ExpMonth    = $payment_info['pafw_nicepay_expiry_month'];
				$nicepay->m_IDNo        = $payment_info['pafw_nicepay_cert_no'];
				$nicepay->m_CardPw      = $payment_info['pafw_nicepay_card_pw'];
				$nicepay->m_MallIP      = $_SERVER['SERVER_ADDR'];
				$nicepay->m_charSet     = 'UTF8';

				if ( $order ) {
					$nicepay->m_BuyerName  = pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) );
					$nicepay->m_BuyerTel   = pafw_get_customer_phone_number( $order );
					$nicepay->m_BuyerEmail = pafw_get_object_property( $order, 'billing_email' );
				} else {
					$nicepay->m_BuyerName  = pafw_remove_emoji( get_user_meta( get_current_user_id(), 'billing_last_name', true ) . get_user_meta( get_current_user_id(), 'billing_first_name', true ) );
					$nicepay->m_BuyerTel   = pafw_get_customer_phone_number( null, get_current_user_id() );
					$nicepay->m_BuyerEmail = get_user_meta( get_current_user_id(), 'billing_email', true );
				}

				$nicepay->startAction();

				if ( 'F100' == $nicepay->m_ResultData['ResultCode'] ) {
					foreach ( $subscriptions as $each_subscription ) {
						$this->props = array ();

						pafw_update_meta_data( $each_subscription, '_pafw_payment_method', $this->id );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'auth_date' ), $nicepay->m_ResultData['AuthDate'] );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'bill_key' ), $nicepay->m_ResultData['BID'] );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'card_code' ), $nicepay->m_ResultData['CardCode'] );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'card_name' ), $nicepay->m_ResultData['CardName'] );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'card_num' ), $nicepay->m_ResultData['CardNo'] );
					}
					if ( 'user' == pafw_get( $this->settings, 'management_batch_key', 'subscription' ) ) {
						update_user_meta( $user_id, '_pafw_payment_method', $this->id );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'auth_date' ), $nicepay->m_ResultData['AuthDate'] );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'bill_key' ), $nicepay->m_ResultData['BID'] );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'card_code' ), $nicepay->m_ResultData['CardCode'] );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'card_name' ), $nicepay->m_ResultData['CardName'] );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'card_num' ), $nicepay->m_ResultData['CardNo'] );
					}

					if ( ! is_null( $order ) ) {
						$this->add_payment_log( $order, '[ 인증키 발급 성공 ]', array (
							'인증키'  => $nicepay->m_ResultData['BID'],
							'카드사'  => $nicepay->m_ResultData['CardName'],
							'카드번호' => $nicepay->m_ResultData['CardNo']
						) );
					}

					return $nicepay->m_ResultData['BID'];
				} else {
					$resultCode = $nicepay->m_ResultData['ResultCode'];
					$resultMsg  = pafw_convert_to_utf8( $nicepay->m_ResultData["ResultMsg"] );

					if ( empty( $resultCode ) && ! empty( $_REQUEST['ResultCode'] ) ) {
						$resultCode = $_REQUEST['ResultCode'];
					}
					if ( empty( $resultMsg ) && ! empty( $_REQUEST['ResultMsg'] ) ) {
						$resultMsg = pafw_convert_to_utf8( $_REQUEST['ResultMsg'] );
					}
					throw new PAFW_Exception( __( sprintf( '인증키 발급 실패 - %s', $resultMsg ), 'pgall-for-woocommerce' ), '1002', $resultCode );
				}
			}
			public function cancel_bill_key( $bill_key ) {
				$this->check_requirement();

				$this->load_library();

				$nicepay                = new NicepayLite;
				$nicepay->m_LicenseKey  = pafw_get( $this->settings, 'merchant_key' );
				$nicepay->m_NicepayHome = $this->get_nicepay_log_path();
				$nicepay->m_MID         = pafw_get( $this->settings, 'merchant_id' );
				$nicepay->m_PayMethod   = self::PAY_METHOD_BILLKEY;
				$nicepay->m_BillKey     = $bill_key;         // 빌키
				$nicepay->m_ssl         = "true";           // 보안접속 여부
				$nicepay->m_ActionType  = "PYO";            // 서비스모드 설정(결제(PY0), 취소(CL0)
				$nicepay->m_debug       = "DEBUG";          // 로그 타입 설정
				$nicepay->m_charSet     = "UTF8";           // 인코딩
				$nicepay->m_CancelFlg   = "1";

				$nicepay->startAction();

				if ( 'F101' == $nicepay->m_ResultData['ResultCode'] ) {
					return true;
				} else {
					throw new PAFW_Exception( __( sprintf( '빌링키 취소 실패 - %s', pafw_convert_to_utf8( $nicepay->m_ResultData["ResultMsg"] ) ), 'pgall-for-woocommerce' ), '1002', $nicepay->m_ResultData['ResultCode'] );
				}
			}
			public function request_subscription_payment( $order, $amount_to_charge, $params = array () ) {
				$this->check_requirement();

				$this->load_library();

				$is_renewal = pafw_get( $params, 'is_renewal', false );
				$is_additional_charge = pafw_get( $params, 'is_additional_charge', false );

				$bill_key = $this->get_bill_key( $order, $is_renewal );

				if ( 'yes' == pafw_get( $params, 'nicepay_issue_bill_key' ) || empty( $bill_key ) ) {
					if ( ! $is_renewal && ! empty( $params ) ) {
						$bill_key = $this->issue_bill_key( $order, $params );
					} else {
						throw new Exception( __( '빌키 정보가 없습니다.', 'pgall-for-woocommerce' ), '5001' );
					}
				}

				if ( $amount_to_charge > 0 ) {
					if ( ! $is_additional_charge ) {
						$tax_free_amount = $this->get_tax_free_amount( $order );
						$total_tax       = $this->get_total_tax( $order );
					} else {
						$tax_free_amount = 0;
						$total_tax       = $this->get_total_tax( null, $amount_to_charge );
					}

					$nicepay                 = new NicepayLite;
					$nicepay->m_MID          = pafw_get( $this->settings, 'merchant_id' );
					$nicepay->m_LicenseKey   = pafw_get( $this->settings, 'merchant_key' );
					$nicepay->m_NicepayHome  = $this->get_nicepay_log_path();
					$nicepay->m_ssl          = true;
					$nicepay->m_ActionType   = self::ACTION_PAYMENT;
					$nicepay->m_NetCancelPW  = pafw_get( $this->settings, 'cancel_pw' );
					$nicepay->m_debug        = pafw_get( $this->settings, 'debug' );
					$nicepay->m_PayMethod    = self::PAY_METHOD_BILL;
					$nicepay->m_charSet      = 'UTF8';
					$nicepay->m_MallIP       = $_SERVER['SERVER_ADDR'];
					$nicepay->m_BillKey      = $bill_key;
					$nicepay->m_BuyerName    = pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) );
					$nicepay->m_Amt          = $amount_to_charge;
					$nicepay->m_SupplyAmt    = $amount_to_charge - $tax_free_amount - $total_tax;
					$nicepay->m_GoodsVat     = $total_tax;
					$nicepay->m_ServiceAmt   = 0;
					$nicepay->m_TaxFreeAmt   = $tax_free_amount;
					$nicepay->m_Moid         = $this->get_txnid( $order );
					$nicepay->m_GoodsName    = $this->make_product_info( $order );
					$nicepay->m_CardQuota    = pafw_get( $params, 'card_quota', '00' );
					$nicepay->m_NetCancelAmt = $amount_to_charge;

					$nicepay->startAction();

					if ( '3001' == $nicepay->m_ResultData['ResultCode'] ) {
						if ( ! $is_additional_charge ) {
							$this->props = array ();

							pafw_update_meta_data( $order, '_pafw_payment_method', $this->id );
							pafw_update_meta_data( $order, '_pafw_auth_date', $nicepay->m_ResultData['AuthDate'] );
							pafw_update_meta_data( $order, '_pafw_bill_key', $nicepay->m_ResultData['BID'] );
							pafw_update_meta_data( $order, '_pafw_card_code', $nicepay->m_ResultData['CardCode'] );
							pafw_update_meta_data( $order, '_pafw_card_name', $nicepay->m_ResultData['CardName'] );
							pafw_update_meta_data( $order, '_pafw_card_num', $nicepay->m_ResultData['CardNo'] );
							pafw_update_meta_data( $order, '_pafw_card_quota', $nicepay->m_ResultData['CardQuota'] );
							pafw_update_meta_data( $order, "_pafw_total_price", $amount_to_charge );
							pafw_update_meta_data( $order, "_pafw_txnid", $nicepay->m_ResultData['TID'] );

							if ( ! is_null( $order ) ) {
								$this->add_payment_log( $order, '[ 정기결제 성공 ]', array (
									'거래번호' => $nicepay->m_ResultData['TID'],
									'승인번호' => $nicepay->m_ResultData['AuthCode']
								) );

								$this->payment_complete( $order, $nicepay->m_ResultData['TID'] );
							}
						} else {
							do_action( 'pafw_payment_action', 'completed', $amount_to_charge, $order, $this );
						}

						return array (
							'transaction_id' => $nicepay->m_ResultData['TID'],
							'auth_date'      => '20' . $nicepay->m_ResultData['AuthDate']
						);
					} else {
						$message = sprintf( '정기결제 실패 : %s', $nicepay->m_ResultData['ResultMsg'] );
						throw new PAFW_Exception( $message, '1003', $nicepay->m_ResultData['ResultCode'] );
					}
				} else {
					if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order ) ) {
						return null;
					}

					$this->payment_complete( $order, '' );
				}
			}
			function process_payment( $order_id ) {
				$order = wc_get_order( $order_id );

				do_action( 'pafw_process_payment', $order );

				return $this->process_subscription_payment( $order_id, pafw_get_object_property( $order, 'order_key' ) );
			}
			function process_order_pay() {
				$params = array ();
				parse_str( $_REQUEST['data'], $params );

				$_REQUEST = array_merge( $_REQUEST, $params );

				$result = $this->process_subscription_payment( $_REQUEST['order_id'], $_REQUEST['order_key'] );

				if ( $result ) {
					wp_send_json_success( $result );
				} else {
					$message = wc_get_notices( 'error' );
					wc_clear_notices();
					wp_send_json_error( implode( "\n", $message ) );
				}
			}
			public function cancel_request( $order, $msg, $code = "1" ) {

				$this->check_requirement();

				$this->load_library();

				$transaction_id = $this->get_transaction_id( $order );

				$nicepay                = new NicepayLite;
				$nicepay->m_MID         = pafw_get( $this->settings, 'merchant_id' );
				$nicepay->m_LicenseKey  = pafw_get( $this->settings, 'merchant_key' );
				$nicepay->m_NicepayHome = $this->get_nicepay_log_path();
				$nicepay->m_ssl         = true;
				$nicepay->m_TID         = $transaction_id;
				$nicepay->m_CancelAmt   = $order->get_total();
				$nicepay->m_CancelMsg   = $msg;
				$nicepay->m_CancelPwd   = pafw_get( $this->settings, 'cancel_pw' );
				$nicepay->m_ActionType  = self::ACTION_CANCEL;
				$nicepay->m_MallIP      = $_SERVER['SERVER_ADDR'];
				$nicepay->m_charSet     = 'UTF8';

				$nicepay->startAction();

				if ( '2001' == $nicepay->m_ResultData['ResultCode'] ) {

					if ( class_exists( 'WC_Subscriptions_Manager' ) ) {
						WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order );
					}

					do_action( 'pafw_payment_action', 'cancelled', $order->get_total(), $order, $this );

					return true;
				} else {
					throw new Exception( sprintf( '주문취소중 오류가 발생했습니다. [%s] %s', $nicepay->m_ResultData['ResultCode'], $nicepay->m_ResultData['ResultMsg'] ) );
				}
			}
			public function add_meta_boxes( $order ) {
				parent::add_meta_boxes( $order );

				if ( $this->supports( 'pafw_additional_charge' ) ) {
					add_meta_box(
						'pafw-order-additional-charge',
						__( '나이스페이 추가과금 <span class="pafw-powerd"><a target="_blank" href="https://www.codemshop.com/">Powered by CodeM</a></span>', 'pgall-for-woocommerce' ),
						array ( $this, 'add_meta_box_additional_charge' ),
						'shop_order',
						'side',
						'high'
					);
				}
			}

			public function subscription_cancel_additional_charge() {
				check_ajax_referer( 'pgall-for-woocommerce' );

				if ( ! current_user_can( 'publish_shop_orders' ) ) {
					throw new Exception( __( '주문 관리 권한이 없습니다.', 'pgall-for-woocommerce' ) );
				}

				$this->load_library();

				$order = wc_get_order( $_REQUEST['order_id'] );

				$nicepay                = new NicepayLite;
				$nicepay->m_MID         = pafw_get( $this->settings, 'merchant_id' );
				$nicepay->m_LicenseKey  = pafw_get( $this->settings, 'merchant_key' );
				$nicepay->m_NicepayHome = $this->get_nicepay_log_path();
				$nicepay->m_ssl         = true;
				$nicepay->m_TID         = $_REQUEST['tid'];
				$nicepay->m_CancelAmt   = $_REQUEST['amount'];
				$nicepay->m_CancelMsg   = __( '추가과금취소', 'pgall-for-woocommerce' );
				$nicepay->m_CancelPwd   = pafw_get( $this->settings, 'cancel_pw' );
				$nicepay->m_ActionType  = self::ACTION_CANCEL;
				$nicepay->m_MallIP      = $_SERVER['SERVER_ADDR'];
				$nicepay->m_charSet     = 'UTF8';

				$nicepay->startAction();

				if ( '2001' == $nicepay->m_ResultData['ResultCode'] ) {

					do_action( 'pafw_payment_action', 'cancelled', $_REQUEST['amount'], $order, $this );

					$this->add_payment_log( $order, '[ 추가 과금 취소 성공 ]', array (
						'거래요청번호' => $_REQUEST['tid'],
						'취소금액'   => wc_price( $_REQUEST['amount'], array ( 'currency' => $order->get_currency() ) )
					) );

					$history = pafw_get_meta( $order, '_pafw_additional_charge_history' );

					$history[ $_REQUEST['tid'] ]['status'] = 'CANCELED';

					pafw_update_meta_data( $order, '_pafw_additional_charge_history', $history );

					wp_send_json_success( '추가 과금 취소 요청이 정상적으로 처리되었습니다.' );
				} else {
					throw new Exception( sprintf( '추가 과금 취소중 오류가 발생했습니다. [%s] %s', $nicepay->m_ResultData['ResultCode'], $nicepay->m_ResultData['ResultMsg'] ) );
				}
			}
		}

	}
}
