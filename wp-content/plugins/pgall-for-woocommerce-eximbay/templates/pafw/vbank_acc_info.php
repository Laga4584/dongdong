<?php

if ( $order->get_status() == 'failed' ) {
	return;
}
$payment_url = pafw_get_meta( $order, '_pafw_payment_url' );

if ( empty( $payment_url ) ) {
	return;
}

?>

<h2><?php _e( '입금 안내', 'pgall-for-woocommerce-eximbay' ); ?></h2>
<p><?php echo sprintf( __( '"<a target="_blank" href="%s">입금 안내 페이지</a>"를 참고하셔서 입금을 진행해주시기 바랍니다.', 'pgall-for-woocommerce-eximbay' ), $payment_url ); ?></p>