<?php
/**
 * My Account Registration Form Template
 */

if ( ! is_account_page() ) {
	do_action( 'woocommerce_before_customer_login_form' );
}

?>
<h2><?php esc_html_e( 'Register', 'jet-woo-builder' ); ?></h2>

<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

	<?php do_action( 'woocommerce_register_form_start' ); ?>

	<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_username"><?php esc_html_e( 'Username', 'jet-woo-builder' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
		</p>

	<?php endif; ?>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="reg_email"><?php esc_html_e( 'Email address', 'jet-woo-builder' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
	</p>

	<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_password"><?php esc_html_e( 'Password', 'jet-woo-builder' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
		</p>

	<?php endif; ?>

	<?php do_action( 'woocommerce_register_form' ); ?>

	<p class="woocommerce-form-row form-row">
		<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
		<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'jet-woo-builder' ); ?>"><?php esc_html_e( 'Register', 'jet-woo-builder' ); ?></button>
	</p>

	<?php do_action( 'woocommerce_register_form_end' ); ?>

</form>