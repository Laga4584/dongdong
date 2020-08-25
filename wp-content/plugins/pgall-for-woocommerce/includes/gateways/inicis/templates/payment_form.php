<?php

$use_ssl = pafw_check_ssl();

if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
	$lang_code = ICL_LANGUAGE_CODE;

	$request_url       = untrailingslashit( WC()->api_request_url( get_class( $this ) . '?type=std&lang=' . $lang_code, $use_ssl ) );
	$request_close_url = untrailingslashit( WC()->api_request_url( get_class( $this ) . '?type=std_cancel&lang=' . $lang_code, $use_ssl ) );
	$request_popup_url = untrailingslashit( WC()->api_request_url( get_class( $this ) . '?type=std_popup&lang=' . $lang_code, $use_ssl ) );
} else {
	$request_url       = untrailingslashit( WC()->api_request_url( get_class( $this ) . '?type=std', $use_ssl ) );
	$request_close_url = untrailingslashit( WC()->api_request_url( get_class( $this ) . '?type=std_cancel', $use_ssl ) );
	$request_popup_url = untrailingslashit( WC()->api_request_url( get_class( $this ) . '?type=std_popup', $use_ssl ) );
}

?>
<form id="SendPayForm_id" name="" method="POST">
    <!-- 필수사항 -->
    <input name="version" value="1.0">
	<?php
	if ( $this->id == 'inicis_stdescrow_bank' ) { ?>
        <input name="mid" value="<?php echo $this->settings['escrow_merchant_id']; ?>">
	<?php } else { ?>
        <input name="mid" value="<?php echo $this->settings['merchant_id']; ?>">
	<?php } ?>
    <input name="goodsname" value="<?php echo esc_attr( $productinfo ); ?>">
    <input name="oid" value="<?php echo $txnid; ?>">
    <input name="price" value="<?php echo intval( $order->get_total() ); ?>">
    <input name="currency" value="WON">
    <input name="buyername" value="<?php echo pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) ); ?>">
    <input name="buyertel" value="<?php echo pafw_get_customer_phone_number( $order ); ?>">
    <input name="buyeremail" value="<?php echo pafw_get_object_property( $order, 'billing_email' ); ?>">
    <input type="text" name="timestamp" value="<?php echo $timestamp; ?>">
    <input type="hidden" name="signature" value="<?php echo $sign ?>">
    <input type="hidden" name="mKey" value="<?php echo $mKey; ?>">
    <input name="gopaymethod" value="<?php echo $this->settings['gopaymethod']; ?>">
    <input name="acceptmethod" value="<?php echo $acceptmethod; ?>">
    <input name="returnUrl" value="<?php echo $request_url; ?>">
    <input name="nointerest" value="<?php echo $cardNoInterestQuota; ?>">
    <input name="quotabase" value="<?php echo $cardQuotaBase; ?>">
    <input name="merchantData" value="<?php echo $notification; ?>">
    <!-- 선택사항 -->
    <input name="offerPeriod" value="">
    <input name="languageView" value="<?php echo pafw_get( $this->settings, 'language_code', 'ko' ); ?>">
    <input name="charset" value="UTF-8">
    <input name="payViewType" value="<?php echo $payView_type; ?>">
    <input name="closeUrl" value="<?php echo $request_close_url; ?>">
    <input name="popupUrl" value="<?php echo $request_popup_url; ?>">
    <input name="vbankRegNo" value="">
    <input name="logo_url" value="<?php echo utf8_uri_encode( pafw_get( $this->settings, 'site_logo', PAFW()->plugin_url() . '/assets/images/default-logo.jpg' ) ); ?>">

	<?php if ( wc_tax_enabled() ) : ?>
        <input type="hidden" name="tax" value="<?php echo intval( $tax_amount ); ?>">
        <input type="hidden" name="taxfree" value="<?php echo intval( $tax_free_amount ); ?>">
	<?php endif; ?>
	<?php echo apply_filters( 'inicis_payment_form_std_template', '', $order ); ?>
</form>