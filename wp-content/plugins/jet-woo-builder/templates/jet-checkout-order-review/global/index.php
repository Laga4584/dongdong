<?php
/**
 * Checkout Order Review Template
 */

do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'jet-woo-builder' ); ?></h3>

<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

<div id="order_review" class="woocommerce-checkout-review-order">
	<?php woocommerce_order_review();; ?>
</div>

<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
