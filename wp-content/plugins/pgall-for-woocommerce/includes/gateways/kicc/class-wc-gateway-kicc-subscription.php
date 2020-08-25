<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Kicc_Subscription' ) ) {

		class WC_Gateway_Kicc_Subscription extends WC_Gateway_KICC {

			const PAY_METHOD_BILLKEY = "BILLKEY";
			const PAY_METHOD_BILL = "BILL";
			const ACTION_PAYMENT = "PYO";
			const ACTION_CANCEL = "CLO";

			public function __construct() {
				$this->id = 'kicc_subscription';

				parent::__construct();

				$this->mgr_txtype = array(
					'full'    => '40',
					'partial' => '32'
				);

				$this->receipt_code = '01';

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( 'KICC 정기결제', 'pgall-for-woocommerce' );
					$this->description = __( 'KICC 정기결제를 진행합니다.', 'pgall-for-woocommerce' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}

				$this->countries = array( 'KR' );
				$this->supports  = array(
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

				add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'woocommerce_scheduled_subscription_payment' ), 10, 2 );
				add_action( 'woocommerce_subscription_status_cancelled', array( $this, 'cancel_subscription' ) );
				add_action( 'woocommerce_subscription_cancelled_' . $this->id, array( $this, 'cancel_subscription' ) );

				add_action( 'woocommerce_subscriptions_pre_update_payment_method', array( $this, 'maybe_remove_subscription_cancelled_callback' ), 10, 3 );
				add_action( 'woocommerce_subscription_payment_method_updated', array( $this, 'maybe_reattach_subscription_cancelled_callback' ), 10, 3 );
			}
			function add_meta_box_subscriptions( $post ) {
				$subscription = wc_get_order( $post );
				$bill_key     = pafw_get_meta( $subscription, $this->get_subscription_meta_key( 'bill_key' ) );

				include_once( 'views/subscription.php' );
			}

			public function get_subscription_meta_key( $meta_key ) {
				return '_pafw_kicc_' . $meta_key;
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
			public function issue_bill_key( $order, $payment_info ) {
				$this->check_requirement();

				require_once $this->home_dir() . '/easypay_client.php';

				$subscriptions = array();

				$user_id = $order ? pafw_get_object_property( $order, 'customer_id' ) : get_current_user_id();

				if ( 'subscription' == pafw_get( $this->settings, 'management_batch_key', 'subscription' ) ) {
					if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order ) ) {
						$subscriptions = array( $order );
					} else {
						$subscriptions = wcs_get_subscriptions_for_order( pafw_get_object_property( $order, 'id' ), array( 'order_type' => 'any' ) );

						if ( empty( $subscriptions ) ) {
							throw new Exception( __( '[오류] 정기결제 관련 정보를 찾을 수 없습니다.', 'pgall-for-woocommerce' ), '1001' );
						}
					}
				}

				$easyPay = new EasyPay_Client();
				$easyPay->clearup_msg();

				$easyPay->set_home_dir( $this->home_dir() );
				$easyPay->set_gw_url( $this->get_gateway_url() );
				$easyPay->set_gw_port( 80 );
				$easyPay->set_log_dir( $this->get_log_dir() );
				$easyPay->set_log_level( 1 );
				$easyPay->set_cert_file( $this->home_dir() . '/pg_cert.pem' );

				// PK_DATA_PAY
				$pay_data = $easyPay->set_easypay_item( "pay_data" );

				// PK_DATA_COMMON
				$comm_data = $easyPay->set_easypay_item( "common" );

				$easyPay->set_easypay_deli_us( $comm_data, "tot_amt", '100' );
				$easyPay->set_easypay_deli_us( $comm_data, "currency", '00' );
				$easyPay->set_easypay_deli_us( $comm_data, "client_ip", $easyPay->get_remote_addr() );
				$easyPay->set_easypay_deli_rs( $pay_data, $comm_data );

				// PK_DATA_CARD
				$card_data = $easyPay->set_easypay_item( "card" );

				$easyPay->set_easypay_deli_us( $card_data, "card_txtype", '11' );
				$easyPay->set_easypay_deli_us( $card_data, "req_type", '0' );
				$easyPay->set_easypay_deli_us( $card_data, "wcc", '@' );
				$easyPay->set_easypay_deli_us( $card_data, "cert_type", '0' );
				$easyPay->set_easypay_deli_us( $card_data, "card_no", $payment_info['pafw_kicc_card_no'] );
				$easyPay->set_easypay_deli_us( $card_data, "expire_date", substr( $payment_info['pafw_kicc_expiry_year'], 2, 2 ) . $payment_info['pafw_kicc_expiry_month'] );
				$easyPay->set_easypay_deli_us( $card_data, "password", $payment_info['pafw_kicc_card_pw'] );
				$easyPay->set_easypay_deli_us( $card_data, "auth_value", $payment_info['pafw_kicc_cert_no'] );
				$easyPay->set_easypay_deli_us( $card_data, "user_type", $payment_info['pafw_kicc_card_type'] );
				$easyPay->set_easypay_deli_rs( $pay_data, $card_data );

				// PK_DATA_ORDER
				$order_data = $easyPay->set_easypay_item( "order_data" );
				$easyPay->set_easypay_deli_us( $order_data, "order_no", $order ? pafw_get_object_property( $order, 'id' ) : date( 'YmdHis' ) );
				$easyPay->set_easypay_deli_us( $order_data, "memb_user_no", $user_id );
				$easyPay->set_easypay_deli_us( $order_data, "product_nm", $order ? $this->make_product_info( $order ) : '' );
				$easyPay->set_easypay_deli_us( $order_data, "product_amt", '100' );

				$opt = "";

				$easyPay->easypay_exec( $this->get_merchant_id(), self::TRAN_CD_NOR_PAYMENT, pafw_get_object_property( $order, 'id' ), $easyPay->get_remote_addr(), $opt );

				$res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
				$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

				if ( '0000' == $res_cd ) {
					$batch_key = $easyPay->_easypay_resdata["card_no"];    //Card no.(Batch Key)
					$tran_date = $easyPay->_easypay_resdata["tran_date"];    //Authorization date
					$issuer_cd = $easyPay->_easypay_resdata["issuer_cd"];    //Issuer code
					$issuer_nm = pafw_convert_to_utf8( $easyPay->_easypay_resdata["issuer_nm"] );
					$card_no   = $easyPay->_easypay_resdata["card_mask_no"];    //Issuer name
					foreach ( $subscriptions as $each_subscription ) {
						$this->props = array();

						pafw_update_meta_data( $each_subscription, '_pafw_payment_method', $this->id );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'auth_date' ), $tran_date );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'bill_key' ), $batch_key );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'card_code' ), $issuer_cd );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'card_name' ), $issuer_nm );
						pafw_update_meta_data( $each_subscription, $this->get_subscription_meta_key( 'card_num' ), $card_no );
					}
					if ( 'user' == pafw_get( $this->settings, 'management_batch_key', 'subscription' ) ) {
						update_user_meta( $user_id, '_pafw_payment_method', $this->id );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'auth_date' ), $tran_date );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'bill_key' ), $batch_key );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'card_code' ), $issuer_cd );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'card_name' ), $issuer_nm );
						update_user_meta( $user_id, $this->get_subscription_meta_key( 'card_num' ), $card_no );
					}

					if ( ! is_null( $order ) ) {
						$this->add_payment_log( $order, '[ 인증키 발급 성공 ]', array(
							'인증키' => $batch_key,
							'카드사' => $issuer_nm
						) );
					}

					return $batch_key;
				} else {
					throw new PAFW_Exception( __( sprintf( '인증키 발급 실패 - %s', $res_msg ), 'pgall-for-woocommerce' ), '1002', $res_cd );
				}
			}
			public function request_subscription_payment( $order, $amount_to_charge, $params = array() ) {
				$this->check_requirement();

				require_once $this->home_dir() . '/easypay_client.php';

				$is_renewal           = pafw_get( $params, 'is_renewal', false );
				$is_additional_charge = pafw_get( $params, 'is_additional_charge', false );

				$bill_key = $this->get_bill_key( $order, $is_renewal );

				if ( 'yes' == pafw_get( $params, 'kicc_issue_bill_key' ) || empty( $bill_key ) ) {
					if ( ! $is_renewal && ! empty( $params ) ) {
						$bill_key = $this->issue_bill_key( $order, $params );
					} else {
						throw new Exception( __( '빌키 정보가 없습니다.', 'pgall-for-woocommerce' ), '5001' );
					}
				}

				if ( $amount_to_charge > 0 ) {
					$easyPay = new EasyPay_Client();
					$easyPay->clearup_msg();

					$easyPay->set_home_dir( $this->home_dir() );
					$easyPay->set_gw_url( $this->get_gateway_url() );
					$easyPay->set_gw_port( 80 );
					$easyPay->set_log_dir( $this->get_log_dir() );
					$easyPay->set_log_level( 1 );
					$easyPay->set_cert_file( $this->home_dir() . '/pg_cert.pem' );

					// PK_DATA_PAY
					$pay_data = $easyPay->set_easypay_item( "pay_data" );

					// PK_DATA_COMMON
					$comm_data = $easyPay->set_easypay_item( "common" );
					$easyPay->set_easypay_deli_us( $comm_data, "tot_amt", $amount_to_charge );
					$easyPay->set_easypay_deli_us( $comm_data, "currency", '00' );
					$easyPay->set_easypay_deli_us( $comm_data, "client_ip", $easyPay->get_remote_addr() );
					$easyPay->set_easypay_deli_rs( $pay_data, $comm_data );

					$card_data = $easyPay->set_easypay_item( "card" );
					$easyPay->set_easypay_deli_us( $card_data, "card_txtype", '41' );
					$easyPay->set_easypay_deli_us( $card_data, "req_type", '0' );
					$easyPay->set_easypay_deli_us( $card_data, "card_amt", $amount_to_charge );
					$easyPay->set_easypay_deli_us( $card_data, "wcc", '@' );
					$easyPay->set_easypay_deli_us( $card_data, "card_no", $bill_key );
					$easyPay->set_easypay_deli_us( $card_data, "install_period", pafw_get( $params, 'card_quota', '00' ) );
					$easyPay->set_easypay_deli_us( $card_data, "noint", '22' );
					$easyPay->set_easypay_deli_rs( $pay_data, $card_data );

					// PK_DATA_ORDER
					$order_data = $easyPay->set_easypay_item( "order_data" );
					$easyPay->set_easypay_deli_us( $order_data, "order_no", pafw_get_object_property( $order, 'id' ) );
					$easyPay->set_easypay_deli_us( $order_data, "product_nm", $order ? $this->make_product_info( $order ) : '' );
					$easyPay->set_easypay_deli_us( $order_data, "product_amt", $amount_to_charge );
					$easyPay->set_easypay_deli_us( $order_data, "user_nm", $order ? pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) ) : '' );
					$easyPay->set_easypay_deli_us( $order_data, "user_mail", $order ? pafw_get_object_property( $order, 'billing_email' ) : '' );
					$easyPay->set_easypay_deli_us( $order_data, "user_phone1", $order ? pafw_get_object_property( $order, 'billing_phone' ) : '' );

					$opt = "utf-8";

					$easyPay->easypay_exec( $this->get_merchant_id(), self::TRAN_CD_NOR_PAYMENT, pafw_get_object_property( $order, 'id' ), $easyPay->get_remote_addr(), $opt );

					$res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
					$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

					if ( '0000' == $res_cd ) {
						if ( ! $is_additional_charge ) {
							$this->props = array();

							pafw_update_meta_data( $order, '_pafw_payment_method', $this->id );
							pafw_update_meta_data( $order, '_pafw_auth_date', $easyPay->_easypay_resdata["tran_date"] );
							pafw_update_meta_data( $order, '_pafw_bill_key', $bill_key );
							pafw_update_meta_data( $order, '_pafw_card_code', $easyPay->_easypay_resdata["issuer_cd"] );
							pafw_update_meta_data( $order, '_pafw_card_name', pafw_convert_to_utf8( $easyPay->_easypay_resdata["issuer_nm"] ) );
							pafw_update_meta_data( $order, '_pafw_card_num', $easyPay->_easypay_resdata["card_no"] );
							pafw_update_meta_data( $order, '_pafw_card_quota', $easyPay->_easypay_resdata["noint"] );
							pafw_update_meta_data( $order, "_pafw_total_price", $easyPay->_easypay_resdata["amount"] );
							pafw_update_meta_data( $order, "_pafw_txnid", $easyPay->_easypay_resdata["cno"] );

							if ( ! is_null( $order ) ) {
								$this->add_payment_log( $order, '[ 정기결제 성공 ]', array(
									'거래번호' => $easyPay->_easypay_resdata["cno"],
									'승인번호' => $easyPay->_easypay_resdata["auth_no"]
								) );

								$this->payment_complete( $order, $easyPay->_easypay_resdata["cno"] );
							}
						} else {
							do_action( 'pafw_payment_action', 'completed', $amount_to_charge, $order, $this );
						}

						return array(
							'transaction_id' => $easyPay->_easypay_resdata["cno"],
							'auth_date'      => $easyPay->_easypay_resdata["tran_date"]
						);
					} else {
						$message = sprintf( '정기결제 실패 : %s', $res_msg );
						throw new PAFW_Exception( $message, '1003', $res_cd );
					}
				} else {
					if ( function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order ) ) {
						return null;
					}

					$this->payment_complete( $order, '' );
				}
			}
			function process_payment( $order_id ) {
				$this->permission_process();

				$order = wc_get_order( $order_id );

				do_action( 'pafw_process_payment', $order );

				return $this->process_subscription_payment( $order_id, pafw_get_object_property( $order, 'order_key' ) );
			}
			function process_order_pay() {
				$params = array();
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
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_txtype", '40' );
				$easyPay->set_easypay_deli_us( $mgr_data, "org_cno", $this->get_transaction_id( $order ) );
				$easyPay->set_easypay_deli_us( $mgr_data, "order_no", pafw_get_object_property( $order, 'id' ) );
				$easyPay->set_easypay_deli_us( $mgr_data, "req_ip", $client_ip );
				$easyPay->set_easypay_deli_us( $mgr_data, "req_id", get_current_user_id() );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_msg", $msg );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_amt", $order->get_total() );

				$easyPay->easypay_exec( $this->get_merchant_id(), WC_Gateway_KICC::TRAN_CD_NOR_MGR, $order->get_id(), $client_ip, '' );

				$res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
				$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

				if ( $res_cd == '0000' ) {
					if ( class_exists( 'WC_Subscriptions_Manager' ) ) {
						WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order );
					}

					do_action( 'pafw_payment_action', 'cancelled', $order->get_total(), $order, $this );

					return true;
				} else {
					throw new Exception( '주문취소중 오류가 발생했습니다. [' . $res_cd . '] ' . $res_msg );
				}
			}
			public function add_meta_boxes( $order ) {
				parent::add_meta_boxes( $order );

				if ( $this->supports( 'pafw_additional_charge' ) ) {
					add_meta_box(
						'pafw-order-additional-charge',
						__( 'KICC 추가과금 <span class="pafw-powerd"><a target="_blank" href="https://www.codemshop.com/">Powered by CodeM</a></span>', 'pgall-for-woocommerce' ),
						array( $this, 'add_meta_box_additional_charge' ),
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

				require_once $this->home_dir() . '/easypay_client.php';

				$order = wc_get_order( $_REQUEST['order_id'] );

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
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_amt", $_REQUEST['amount'] );
				$easyPay->set_easypay_deli_us( $mgr_data, "org_cno", $_REQUEST['tid'] );
				$easyPay->set_easypay_deli_us( $mgr_data, "mgr_msg", __( '추가과금취소', 'pgall-for-woocommerce' ) );
				$easyPay->set_easypay_deli_us( $mgr_data, "req_ip", $client_ip );
				$easyPay->set_easypay_deli_us( $mgr_data, "req_id", get_current_user_id() );

				$easyPay->easypay_exec( $this->get_merchant_id(), WC_Gateway_KICC::TRAN_CD_NOR_MGR, $order->get_id(), $client_ip, '' );

				$res_cd  = $easyPay->_easypay_resdata["res_cd"];    // 응답코드
				$res_msg = pafw_convert_to_utf8( $easyPay->_easypay_resdata["res_msg"] );

				if ( $res_cd == '0000' ) {
					do_action( 'pafw_payment_action', 'cancelled', $_REQUEST['amount'], $order, $this );

					$this->add_payment_log( $order, '[ 추가 과금 취소 성공 ]', array(
						'거래요청번호' => $_REQUEST['tid'],
						'취소금액'   => wc_price( $_REQUEST['amount'], array( 'currency' => $order->get_currency() ) )
					) );

					$history = pafw_get_meta( $order, '_pafw_additional_charge_history' );

					$history[ $_REQUEST['tid'] ]['status'] = 'CANCELED';

					pafw_update_meta_data( $order, '_pafw_additional_charge_history', $history );

					wp_send_json_success( '추가 과금 취소 요청이 정상적으로 처리되었습니다.' );
				} else {
					throw new Exception( sprintf( '추가 과금 취소중 오류가 발생했습니다. [%s] %s', $res_cd, $res_msg ) );
				}
			}

		}

	}
}
