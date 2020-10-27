<?php
/**
 * Checkout Billing Form Template
 */

$checkout = wc()->checkout();

if ( sizeof( $checkout->checkout_fields ) > 0 ) {

	do_action( 'woocommerce_checkout_billing' );

}
