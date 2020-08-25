<?php
add_filter( 'woocommerce_get_sections_checkout', array ( 'WC_Gateway_PAFW_EximBay', 'checkout_sections' ) );

add_filter( 'pafw_admin_gateway_settings', 'add_eximbay_gateway_settings', 10, 2 );

function add_eximbay_gateway_settings( $gateway_settings, $base_url ) {
	$gateway_settings[] = array (
		"id"        => "pafw-gw-eximbay",
		"title"     => "엑심베이(Eximbay)",
		"className" => "",
		"type"      => "Toggle",
		"default"   => "no",
		"desc"      => __( '<div class="desc2">엑심베이 일반결제를 이용합니다.</div>', 'pgall-for-woocommerce-eximbay' ),
		"action"    => array (
			"icon"   => "cogs",
			"show"   => "yes",
			"target" => "_blank",
			"url"    => $base_url . 'mshop_eximbay'
		)
	);

	return $gateway_settings;
}
function pafweb_register_cancel_unpaid_order_action( $order_id, $payment_due = 7 ) {
	pafweb_clear_unpaid_order_action( $order_id );

	$due_date = date( 'Y-m-d 00:00:00', strtotime( '+' . ( $payment_due + 1 ) . ' day' ) );
	as_schedule_single_action(
		strtotime( $due_date ),
		'pafweb_cancel_unpaid_order',
		array ( 'order_id' => $order_id )
	);
}
function pafweb_clear_unpaid_order_action( $order_id ) {
	as_unschedule_all_actions( 'pafweb_cancel_unpaid_order', array ( 'order_id' => $order_id ) );
}
function pafweb_maybe_cancel_unpaid_order_action( $order_id, $old_status, $new_status ) {
	if ( $old_status == 'on-hold' ) {
		pafweb_clear_unpaid_order_action( $order_id );
	}
}

add_action( 'woocommerce_order_status_changed', 'pafweb_maybe_cancel_unpaid_order_action', 10, 3 );
function pafweb_cancel_unpaid_order( $order_id ) {
	$order = wc_get_order( $order_id );

	try {
		if ( $order && 'on-hold' == $order->get_status() ) {
			$payment_gateway = pafw_get_payment_gateway_from_order( $order );

			if ( $payment_gateway instanceof WC_Gateway_Eximbay ) {
				$order->update_status( 'cancelled', __( '[미입금 자동취소] 지불되지 않은 주문이 취소 처리 되었습니다.', 'pgall-for-woocommerce-eximbay' ) );

				pafw_update_meta_data( $order, '_pafw_order_cancelled', 'yes' );
				pafw_update_meta_data( $order, '_pafw_cancel_date', current_time( 'mysql' ) );
			}
		}
	} catch ( Exception $e ) {

	}
}

add_action( 'pafweb_cancel_unpaid_order', 'pafweb_cancel_unpaid_order' );