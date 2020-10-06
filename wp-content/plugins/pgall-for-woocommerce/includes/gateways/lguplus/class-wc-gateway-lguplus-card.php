<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Lguplus_Card' ) ) {

		class WC_Gateway_Lguplus_Card extends WC_Gateway_Lguplus {

			public function __construct() {
				$this->id = 'lguplus_card';

				parent::__construct();

				$this->settings['paymethod'] = 'SC0010';

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( '신용카드', 'pgall-for-woocommerce' );
					$this->description = __( '카드사를 통해 결제를 진행합니다.', 'pgall-for-woocommerce' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}

				$this->supports[] = 'refunds';
			}
			public function process_payment_success( $order, $xpay ) {
				pafw_update_meta_data( $order, "_pafw_card_num", $xpay->Response( 'LGD_CARDNUM', 0 ) );          //카드번호
				pafw_update_meta_data( $order, "_pafw_card_qouta", $xpay->Response( 'LGD_CARDNOINTEREST_YN', 0 ) );      //할부기간
				pafw_update_meta_data( $order, "_pafw_card_code", $xpay->Response( 'LGD_CARDACQUIRER', 0 ) );        //신용카드사 코드
				pafw_update_meta_data( $order, "_pafw_card_name", mb_convert_encoding( $xpay->Response( 'LGD_FINANCENAME', 0 ), "UTF-8", "CP949" ) );    //신용카드사명

				$this->add_payment_log( $order, '[ 결제 승인 완료 ]', array (
					'LG유플러스 거래번호' => $xpay->Response( 'LGD_TID', 0 ),
					'몰 고유 주문번호'   => $xpay->Response( 'LGD_OID', 0 )
				) );
			}
			public function process_refund( $order_id, $amount = null, $reason = '' ) {
				$order = wc_get_order( $order_id );
				$this->check_requirement();

				require_once( PAFW()->plugin_path() . '/lib/lguplus/lgdacom/XPayClient.php' );

				$CST_PLATFORM = 'production' == $this->operation_mode ? 'service' : 'test';
				$LGD_MID      = ( 'sandbox' == $this->operation_mode ? 't' : '' ) . $this->merchant_id;
				$LGD_TID      = $this->get_transaction_id( $order );

				$xpay                     = new XPayClient( $this->config_path, $CST_PLATFORM );
				$xpay->config[ $LGD_MID ] = $this->merchant_key;

				if ( ! $xpay->Init_TX( $LGD_MID ) ) {
					throw new PAFW_Exception( __( '결제 취소중 오류가 발생했습니다.', 'pgall-for-woocommerce' ), '5002' );
				}

				$xpay->Set( "LGD_TXNAME", "PartialCancel" );
				$xpay->Set( "LGD_TID", $LGD_TID );
				$xpay->Set( "LGD_CANCELAMOUNT", $amount );
				$xpay->Set( "LGD_CANCELTAXFREEAMOUNT", $this->get_tax_free_amount( $order, $amount ) );

				if ( $xpay->TX() ) {
					$success_codes = array ( '0000', 'RF10', ' RF09', ' RF15', ' RF19', ' RF23', ' RF25' );

					if ( in_array( $xpay->Response_Code(), $success_codes ) ) {
						do_action( 'pafw_payment_action', 'cancelled', $amount, $order, $this );

						if ( $order->get_total() - $order->get_total_refunded() <= 0 ) {
							pafw_update_meta_data( $order, '_pafw_order_cancelled', 'yes' );
							pafw_update_meta_data( $order, '_pafw_cancel_date', current_time( 'mysql' ) );

							if ( class_exists( 'WC_Subscriptions_Manager' ) ) {
								WC_Subscriptions_Manager::cancel_subscriptions_for_order( $order );
							}
						}

						$this->add_payment_log( $order, '[ 결제 취소 성공 ]', array (
							'취소금액'   => wc_price( $amount, array ( 'currency' => $order->get_currency() ) )
						) );

						return true;
					} else {
						throw new PAFW_Exception( mb_convert_encoding( $xpay->Response_Msg(), "UTF-8", "EUC-KR" ), '5001', $xpay->Response_Code() );
					}
				} else {
					throw new PAFW_Exception( mb_convert_encoding( $xpay->Response_Msg(), "UTF-8", "EUC-KR" ), '5001', $xpay->Response_Code() );
				}
			}
		}
	}

} // class_exists function end
