<?php
/**
 * Thank You Customer Address Details Template
 */

global $wp;

if ( isset( $wp->query_vars['order-received'] ) ) {
	$order_received_id = $wp->query_vars['order-received'];
} else {
	$order_received_id = jet_woo_builder_template_functions()->get_last_received_order();
}

if ( ! $order_received_id ) {
	return;
}

$order                 = wc_get_order( $order_received_id );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();

if ( ! $order ) {
	return;
}

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}