<?php
/**
 * My Account page
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="jet-woo-builder-my-account-content">

	<?php
		$template = apply_filters( 'jet-woo-builder/current-template/template-id', jet_woo_builder_integration_woocommerce()->get_current_myaccount_template() );

		echo jet_woo_builder()->parser->get_template_content( $template );

		remove_action( 'woocommerce_account_content', 'woocommerce_account_content' );
		remove_action( 'woocommerce_account_content', 'woocommerce_output_all_notices', 5 );

		do_action( 'woocommerce_account_content' );
	?>

</div>
