<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$order = wc_get_order( $order_id );
$payment_gateway = pafw_get_payment_gateway_from_order( $order );

if ( empty( $payment_gateway ) || ( 'bacs' != $payment_gateway->id && ! $payment_gateway->supports( 'pafw-vbank' ) ) ) {
	return;
}

?>
<div class="field">
    <label><?php _e( '환불 계좌 정보를 입력하세요.', 'pgall-for-woocommerce' ); ?></label>
</div>
<div class="field pafw-ex-reason">
    <input type="text" name="refund_bank_account" class="input-text" placeholder="<?php _e( '환불받으실 은행명, 계좌번호, 예금주명을 입력해주세요.', 'pgall-for-woocommerce' ); ?>"/>
</div>