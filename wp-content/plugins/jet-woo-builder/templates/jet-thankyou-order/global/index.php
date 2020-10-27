<?php
/**
 * Thank You Order Template
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

$order = wc_get_order( $order_received_id );

if ( ! $order ) {
	return;
}

if ( $order ) :

	do_action( 'woocommerce_before_thankyou', $order->get_id() );
	?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'jet-woo-builder' ); ?></p>

	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
		<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'jet-woo-builder' ); ?></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'jet-woo-builder' ); ?></a>
		<?php endif; ?>
	</p>

<?php else : ?>

	<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'jet-woo-builder' ), $order ); ?></p>

	<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

		<li class="woocommerce-order-overview__order order">
			<?php esc_html_e( 'Order number:', 'jet-woo-builder' ); ?>
			<strong><?php echo $order->get_order_number(); ?></strong>
		</li>

		<li class="woocommerce-order-overview__date date">
			<?php esc_html_e( 'Date:', 'jet-woo-builder' ); ?>
			<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
		</li>

		<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
			<li class="woocommerce-order-overview__email email">
				<?php esc_html_e( 'Email:', 'jet-woo-builder' ); ?>
				<strong><?php echo $order->get_billing_email(); ?></strong>
			</li>
		<?php endif; ?>

		<li class="woocommerce-order-overview__total total">
			<?php esc_html_e( 'Total:', 'jet-woo-builder' ); ?>
			<strong><?php echo $order->get_formatted_order_total(); ?></strong>
		</li>

		<?php if ( $order->get_payment_method_title() ) : ?>
			<li class="woocommerce-order-overview__payment-method method">
				<?php esc_html_e( 'Payment method:', 'jet-woo-builder' ); ?>
				<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
			</li>
		<?php endif; ?>

	</ul>

<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>

<?php else : ?>

	<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'jet-woo-builder' ), null ); ?></p>

<?php endif; ?>