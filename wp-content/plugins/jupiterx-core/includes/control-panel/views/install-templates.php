<?php

if ( ! JUPITERX_CONTROL_PANEL_TEMPLATES ) {
	return;
}

$menu_items_access = get_site_option( 'menu_items' );

if ( is_multisite() && ! is_super_admin() ) : ?>
	<div class="jupiterx-cp-pane-box" id="jupiterx-no-templates">
		<div class="alert alert-warning" role="alert">

			<?php esc_html_e( 'Template installation is only allowed for user with Super Admin role. Please contact your website\'s administrator.', 'jupiterx-core' ); ?>
			<a href="https://themes.artbees.net/docs/installing-a-template/" target="_blank" >
			<?php esc_html_e( 'Read More', 'jupiterx-core' ); ?>
		</a>
		</div>
	</div>
<?php
endif;

$installed_template_data_attr = '';
$installed_template_id = jupiterx_get_option( 'template_installed_id', '' );
$installed_template_data_attr .= ' data-installed-template-id="' . esc_attr( $installed_template_id ) . '"';
$installed_template = jupiterx_get_option( 'template_installed', '' );
$installed_template_data_attr .= ' data-installed-template="' . esc_attr( $installed_template ) . '"';
wp_print_request_filesystem_credentials_modal();
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-templates">

	<!-- Restore Button wrap -->
	<div id="js__restore-template-wrap" class="jupiterx-restore-template-wrap">
		<a class="btn btn-primary jupiterx-button--restore-backup" id="js__restore-template-btn" href="#" data-content="" data-toggle="popover" data-placement="bottom">
			<?php esc_html_e( 'Restore from Last Backup', 'jupiterx-core' ); ?>
		</a>
	</div>
	<!-- End of Restore Button wrap -->

	<!-- Installed Template wrap -->
	<div id="js__installed-template-wrap" class="jupiterx-cp-installed-template">
		<h3>
			<?php esc_html_e( 'Installed Template', 'jupiterx-core' ); ?>
			<?php jupiterx_the_help_link( 'https://themes.artbees.net/docs/installing-a-template/', esc_html__( 'Installing a Template', 'jupiterx-core' ) ); ?>
		</h3>
		<div id="js__installed-template" <?php echo $installed_template_data_attr; ?>></div>
		<div class="clearfix"></div>
	</div>
	<!-- End of installed template -->

	<div class="jupiterx-cp-install-template">
		<h3>
			<?php esc_html_e( 'Templates', 'jupiterx-core' ); ?>
			<?php jupiterx_the_help_link( 'https://themes.artbees.net/docs/installing-a-template/', esc_html__( 'Installing a Template', 'jupiterx-core' ) ); ?>
		</h3>
		<?php
		jupiterx_templates()->html( [
			'posts_per_page' => 12,
		] );
		?>
	</div>
</div>
