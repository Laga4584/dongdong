<?php
/**
 * Settings template.
 *
 * @package JupiterX_Core\Control_Panel\Settings
 *
 * @since 1.4.0
 */

if ( ! JUPITERX_CONTROL_PANEL_SETTINGS ) {
	return;
}
?>
<div class="jupiterx-cp-pane-box bootstrap-wrapper">
	<h3><?php esc_html_e( 'Settings', 'jupiterx-core' ); ?></h3>
	<div class="jupiterx-cp-export-wrap">
		<form class="jupiterx-cp-settings-form" action="#">
			<div class="form-row">
				<div class="form-group col-md-12">
					<button type="button" class="btn btn-secondary jupiterx-cp-settings-flush"><?php esc_html_e( 'Flush Assets Cache', 'jupiterx-core' ); ?></button>
					<span class="jupiterx-cp-settings-flush-feedback text-muted ml-2 d-none"><?php esc_html_e( 'Flushing...', 'jupiterx-core' ); ?></span>
					<small class="form-text text-muted"><?php esc_html_e( 'Clear CSS, Javascript and images cached files. New cached versions will be compiled/created on page load.', 'jupiterx-core' ); ?></small>
				</div>
				<div class="col-md-12"><hr></div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-dev-mode"><?php esc_html_e( 'Development Mode', 'jupiterx-core' ); ?></label>
					<input type="hidden" name="jupiterx_dev_mode" value="0">
					<div class="jupiterx-switch">
						<input type="checkbox" id="jupiterx-cp-settings-dev-mode" name="jupiterx_dev_mode" value="1" <?php checked( jupiterx_get_option( 'dev_mode' ), true ); ?>>
						<label for="jupiterx-cp-settings-dev-mode"></label>
					</div>
					<small class="form-text text-muted"><?php esc_html_e( 'This option should be enabled while your website is in development.', 'jupiterx-core' ); ?></small>
				</div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-cache-busting"><?php esc_html_e( 'Cache Busting', 'jupiterx-core' ); ?></label>
					<input type="hidden" name="jupiterx_cache_busting" value="0">
					<div class="jupiterx-switch">
						<input type="checkbox" id="jupiterx-cp-settings-cache-busting" name="jupiterx_cache_busting" value="1" <?php checked( jupiterx_get_option( 'cache_busting', true ) ); ?>>
						<label for="jupiterx-cp-settings-cache-busting"></label>
					</div>
					<small class="form-text text-muted"><?php esc_html_e( 'Enable cache busting technique.', 'jupiterx-core' ); ?></small>
				</div>
				<?php do_action( 'jupiterx_control_panel_after_theme_settings' ); ?>
				<div class="col-md-12"><hr></div>
				<div class="form-group col-md-12">
					<label class="m-0"><?php esc_html_e( 'Custom Post Types', 'jupiterx-core' ); ?></label>
					<small class="form-text text-muted mb-2"><?php esc_html_e( 'Enable Jupiter features (customizer, meta options, etc.) for these post types.', 'jupiterx-core' ); ?></small>
					<input type="hidden" name="jupiterx_post_types" value="">
					<?php $post_types = jupiterx_get_custom_post_types( 'objects' ); ?>
					<?php $supported_post_types = jupiterx_get_option( 'post_types' ); ?>
					<?php if ( ! empty( $post_types ) ) : ?>
						<?php foreach ( $post_types as $id => $post_type ) : ?>
							<?php echo '<div class="custom-control custom-checkbox">'; ?>
							<?php echo '<input type="checkbox" class="custom-control-input" name="jupiterx_post_types[]" ' . ( ( is_array( $supported_post_types ) && in_array( $post_type->name, jupiterx_get_option( 'post_types' ), true ) ) ? 'checked="checked"' : '' ) . ' value="' . esc_attr( $post_type->name ) . '" id="jupiterx_post_type_' . esc_attr( $post_type->name ) . '">'; ?>
							<?php echo '<label class="custom-control-label" for="jupiterx_post_type_' . esc_attr( $post_type->name ) . '">' . $post_type->label . '</label>'; ?>
							<?php echo '</div>'; ?>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="jupiterx-settings-no-post-type">
							<i class="jupiterx-icon-info-circle"></i><?php esc_html_e( 'No custom post type was found.', 'jupiterx-core' ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php do_action( 'jupiterx_control_panel_settings_white_label' ); ?>
				<?php if ( jupiterx_is_premium() && class_exists( 'Jupiter_Donut' ) ) : ?>
				<div class="col-md-12"><hr></div>
				<h5 class="col-md-12 mb-3">Donut Plugin</h5>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-donut-skin-color"><?php esc_html_e( 'Theme Accent Color', 'jupiterx-core' ); ?></label>
					<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-donut-skin-color" placeholder="Enter color hex codes (#f97352)" value="<?php echo esc_attr( jupiterx_get_option( 'donut_skin_color' ) ); ?>" name="jupiterx_donut_skin_color">
				</div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-donut-twitter-consumer-key"><?php esc_html_e( 'Twitter Consumer Key', 'jupiterx-core' ); ?></label>
					<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-donut-twitter-consumer-key" value="<?php echo esc_attr( jupiterx_get_option( 'donut_twitter_consumer_key' ) ); ?>" name="jupiterx_donut_twitter_consumer_key">
				</div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-donut-twitter-consumer-secret"><?php esc_html_e( 'Twitter Consumer Secret', 'jupiterx-core' ); ?></label>
					<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-donut-twitter-consumer-secret" value="<?php echo esc_attr( jupiterx_get_option( 'donut_twitter_consumer_secret' ) ); ?>" name="jupiterx_donut_twitter_consumer_secret">
				</div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-donut-twitter-access-token"><?php esc_html_e( 'Twitter Access Token', 'jupiterx-core' ); ?></label>
					<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-donut-twitter-access-token" value="<?php echo esc_attr( jupiterx_get_option( 'donut_twitter_access_token' ) ); ?>" name="jupiterx_donut_twitter_access_token">
				</div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-donut-twitter-access-token-secret"><?php esc_html_e( 'Twitter Access Token Secret', 'jupiterx-core' ); ?></label>
					<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-donut-twitter-access-token-secret" value="<?php echo esc_attr( jupiterx_get_option( 'donut_twitter_access_token_secret' ) ); ?>" name="jupiterx_donut_twitter_access_token_secret">
				</div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-donut-mailchimp-api-key"><?php esc_html_e( 'MailChimp API Key', 'jupiterx-core' ); ?></label>
					<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-donut-mailchimp-api-key" value="<?php echo esc_attr( jupiterx_get_option( 'donut_mailchimp_api_key' ) ); ?>" name="jupiterx_donut_mailchimp_api_key">
				</div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-donut-mailchimp-list-id"><?php esc_html_e( 'Mailchimp List ID', 'jupiterx-core' ); ?></label>
					<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-donut-mailchimp-list-id" value="<?php echo esc_attr( jupiterx_get_option( 'donut_mailchimp_list_id' ) ); ?>" name="jupiterx_donut_mailchimp_list_id">
				</div>
				<div class="form-group col-md-6">
					<label for="jupiterx-cp-settings-donut-google-maps-api-key"><?php esc_html_e( 'Google Maps API Key', 'jupiterx-core' ); ?></label>
					<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-donut-google-maps-api-key" value="<?php echo esc_attr( jupiterx_get_option( 'donut_google_maps_api_key' ) ); ?>" name="jupiterx_donut_google_maps_api_key">
				</div>
				<div class="form-group col-md-12">
					<label class="m-0 mb-2"><?php esc_html_e( 'Custom Post Types', 'jupiterx-core' ); ?></label>
					<input type="hidden" name="jupiterx_donut_post_types" value="">
					<?php $post_types = [
						'clients' => esc_html__( 'Clients', 'jupiterx-core' ),
						'animated-columns' => esc_html__( 'Animated Columns', 'jupiterx-core' ),
						'banner_builder' => esc_html__( 'Banner Builder', 'jupiterx-core' ),
						'edge' => esc_html__( 'Edge Slider', 'jupiterx-core' ),
						'employees' => esc_html__( 'Employees', 'jupiterx-core' ),
						'faq' => esc_html__( 'FAQ', 'jupiterx-core' ),
						'news' => esc_html__( 'News', 'jupiterx-core' ),
						'pricing' => esc_html__( 'Pricing Tables', 'jupiterx-core' ),
						'slideshow' => esc_html__( 'FlexSlider', 'jupiterx-core' ),
						'tab_slider' => esc_html__( 'Tab Slider', 'jupiterx-core' ),
						'testimonial' => esc_html__( 'Testimonial', 'jupiterx-core' ),
						'photo_album' => esc_html__( 'Photo Album', 'jupiterx-core' ),
					]; ?>
					<?php if ( ! empty( $post_types ) ) : ?>
						<?php foreach ( $post_types as $slug => $post_type ) : ?>
							<?php echo '<div class="custom-control custom-checkbox">
								<input
									type="checkbox"
									class="custom-control-input"
									name="jupiterx_donut_post_types[]"
									' . ( ! in_array( $slug, (array) jupiterx_get_option( 'donut_post_types', array_keys( $post_types ) ), true ) ? '' : 'checked="checked"' ) . '
									value="' . esc_attr( $slug ) . '"
									id="jupiterx_donut_post_type_' . esc_attr( $slug ) . '">
								<label class="custom-control-label" for="jupiterx_donut_post_type_' . esc_attr( $slug ) . '">' . $post_type . '</label>
							</div>'; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
			<div class="mt-2">
				<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Save Settings', 'jupiterx-core' ); ?></button>
				<span class="jupiterx-cp-settings-save-feedback text-muted ml-2 d-none"><?php esc_html_e( 'Saving...', 'jupiterx-core' ); ?></span>
			</div>
		</form>
	</div>
</div>
