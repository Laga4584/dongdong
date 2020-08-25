<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form class="form-horizontal" name="regForm" method="post" action="<?php echo $this->get_api_url( 'request' ); ?>">
    <input type="hidden" name="ver" value="230"/>
    <input type="hidden" name="txntype" value="PAYMENT"/>
    <input type="hidden" name="charset" value="UTF-8"/>

    <input type="hidden" name="statusurl" value="<?php echo $this->get_api_url( 'status' ); ?>"/>
    <input type="hidden" name="returnurl" value="<?php echo $this->get_api_url( 'return' ); ?>"/>

    <input type="hidden" name="rescode"/>
    <input type="hidden" name="resmsg"/>

    <input type="hidden" name="mid" value="<?php echo pafw_get( $this->settings, 'merchant_id' ); ?>">
    <input type="hidden" name="ref" value="<?php echo $this->get_txnid( $order ); ?>">
    <input type="hidden" name="ostype" value="<?php echo wp_is_mobile() ? 'M' : 'P'; ?>">
    <input type="hidden" name="displaytype" value="P">
	<?php if ( ! empty( $this->issuercountry ) ) : ?>
        <input type="hidden" name="issuercountry" value="<?php echo $this->issuercountry; ?>">
	<?php endif; ?>

    <input type="text" name="cur" value="<?php echo $order->get_currency(); ?>">
    <input type="text" name="amt" value="<?php echo $order->get_total(); ?>">
    <input type="text" name="shop" value="<?php echo get_bloginfo( 'name' ); ?>">
    <input type="text" name="buyer" value="<?php echo pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ); ?>">
    <input type="text" name="email" value="<?php echo pafw_get_object_property( $order, 'billing_email' ); ?>">
    <input type="text" name="tel" value="<?php echo preg_replace( "/[^0-9]*/s", "", pafw_get_object_property( $order, 'billing_phone' ) ); ?>">

    <input type="hidden" name="lang" value="<?php echo apply_filters( 'pafw_payment_gateway_language', pafw_get( $this->settings, 'language_code', 'KO' ), $this->id ); ?>">
    <input type="hidden" name="paymethod" value="<?php echo $this->paymethod; ?>">

    <input type="hidden" name="item_0_product" value="<?php echo $this->make_product_info( $order ); ?>">

    <input type="hidden" name="param1" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
    <input type="hidden" name="param2" value="">
    <input type="hidden" name="param3" value="">
    <input type="hidden" name="autoclose" value="Y">

    <input type="hidden" name="tokenBilling" value="<?php echo $this->supports( 'subscriptions' ) ? 'Y' : 'N'; ?>">
</form>
