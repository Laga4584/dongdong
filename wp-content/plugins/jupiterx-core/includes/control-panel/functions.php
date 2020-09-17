<?php
/**
 * The Jupiter Control Panel component.
 *
 * @package JupiterX_Core\Control_Panel
 */

/**
 * Run on control panel init.
 *
 * @since 1.2.0
 */
add_action( 'jupiterx_control_panel_init', function() {
	jupiterx_core()->load_files( [
		'control-panel/includes/class-customizer-option',
		'control-panel/includes/class-filesystem',
		'control-panel/includes/class-validator',
		'control-panel/includes/class-helpers',
		'control-panel/includes/logic-messages',
		'control-panel/includes/class-image-sizes',
		'control-panel/includes/class-settings',
		'control-panel/includes/class-db-manager',
		'control-panel/includes/class-install-template',
		'control-panel/includes/class-install-plugins',
		'control-panel/includes/class-export-import-content',
		'control-panel/includes/class-system-status',
		'control-panel/includes/class-db-php-manager',
	] );
} );

/**
 * Get started quick guide.
 *
 * @since 1.2.0
 */
add_action( 'jupiterx_control_panel_get_started', function() {
	?>
	<h6><?php esc_html_e( 'Get started:', 'jupiterx-core' ); ?></h6>
	<iframe class="mb-4" width="400" height="225" src="https://www.youtube.com/embed/fnlzOHECEDo?modestbranding=1" frameborder="0" allowfullscreen></iframe>
	<?php
} );

add_filter( 'jupiterx_control_panel_sections', 'jupiterx_add_control_panel_sections' );
/**
 * Add sections to control panel.
 *
 * @since 1.9.0
 */
function jupiterx_add_control_panel_sections( $sections ) {

	$sections['image_size'] = [
		'title'     => __( 'Image Sizes' , 'jupiterx-core' ),
		'href'      => 'image-sizes',
		'condition' => defined( 'JUPITERX_CONTROL_PANEL_IMAGE_SIZES' ) && JUPITERX_CONTROL_PANEL_IMAGE_SIZES,
		'order'     => 40,
	];

	$sections['system_status'] = [
		'title'     => __( 'System Status' , 'jupiterx-core' ),
		'href'      => 'system-status',
		'condition' => defined( 'JUPITERX_CONTROL_PANEL_SYSTEM_STATUS' ) && JUPITERX_CONTROL_PANEL_SYSTEM_STATUS,
		'order'     => 50,
	];

	$sections['settings'] = [
		'title'     => __( 'Settings' , 'jupiterx-core' ),
		'href'      => 'settings',
		'condition' => defined( 'JUPITERX_CONTROL_PANEL_SETTINGS' ) && JUPITERX_CONTROL_PANEL_SETTINGS,
		'order'     => 70,
	];

	$sections['export_import'] = [
		'title'     => __( 'Export/Import' , 'jupiterx-core' ),
		'href'      => 'export-import',
		'condition' => defined( 'JUPITERX_CONTROL_PANEL_EXPORT_IMPORT' ) && JUPITERX_CONTROL_PANEL_EXPORT_IMPORT,
		'order'     => 75,
	];

	return $sections;
}

/**
 * Core additional settings.
 *
 * @since 1.2.0
 */
add_action( 'jupiterx_control_panel_after_theme_settings', function() {
	?>
	<div class="form-group col-md-6">
		<label for="jupiterx-cp-settings-svg-support">
			<?php esc_html_e( 'SVG Support', 'jupiterx-core' ); ?>
		</label>
		<input type="hidden" name="jupiterx_svg_support" value="0">
		<div class="jupiterx-switch">
			<input type="checkbox" id="jupiterx-cp-settings-svg-support" name="jupiterx_svg_support" value="1" <?php echo esc_attr( ( empty( jupiterx_get_option( 'svg_support' ) ) ) ? '' : 'checked' ); ?>>
			<label for="jupiterx-cp-settings-svg-support"></label>
		</div>
		<small class="form-text text-muted">
			<?php esc_html_e( 'Enable this option to upload SVG to WordPress Media Library.', 'jupiterx-core' ); ?>
		</small>
	</div>
	<div class="col-md-12">
		<hr />
	</div>
	<div class="form-group col-md-6">
		<label for="jupiterx-cp-settings-google-analytics-id"><?php esc_html_e( 'Google Analytics ID', 'jupiterx-core' ); ?></label>
		<?php jupiterx_the_help_link( 'https://themes.artbees.net/docs/adding-google-analytics-code-into-jupiter-x/', esc_html__( 'Adding Google Analytics code into Jupiter X', 'jupiterx-core' ) ); ?>
		<input type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-google-analytics-id" value="<?php echo esc_attr( jupiterx_get_option( 'google_analytics_id' ) ); ?>" name="jupiterx_google_analytics_id" placeholder="UA-45******-*">
	</div>
	<div class="form-group col-md-6">
		<label for="jupiterx-cp-settings-google-analytics-anonymization"><?php esc_html_e( 'IP Anonymization', 'jupiterx-core' ); ?></label>
		<input type="hidden" name="jupiterx_google_analytics_anonymization" value="0">
		<div class="jupiterx-switch">
			<input type="checkbox" id="jupiterx-cp-settings-google-analytics-anonymization" name="jupiterx_google_analytics_anonymization" value="1" <?php echo esc_attr( jupiterx_get_option( 'google_analytics_anonymization', true ) ? 'checked' : '' ); ?>>
			<label for="jupiterx-cp-settings-google-analytics-anonymization"></label>
		</div>
		<small class="form-text text-muted"><?php esc_html_e( 'Enable IP Anonymization for Google Analytics.', 'jupiterx-core' ); ?></small>
	</div>
	<div class="form-group col-md-12">
		<label for="jupiterx-cp-settings-adobe-project-id"><?php esc_html_e( 'Adobe Fonts Project ID', 'jupiterx-core' ); ?></label>
		<?php jupiterx_the_help_link( 'https://themes.artbees.net/docs/using-adobe-fonts-formerly-typekit-in-jupiter-x/', esc_html__( 'Using Adobe fonts (formerly Typekit) in Jupiter X', 'jupiterx-core' ) ); ?>
		<?php jupiterx_pro_badge(); ?>
		<input <?php echo esc_attr( ( ! jupiterx_is_pro() ) ? 'disabled' : '' ); ?> type="text" class="jupiterx-form-control" id="jupiterx-cp-settings-adobe-project-id" value="<?php echo esc_attr( jupiterx_get_option( 'adobe_fonts_project_id' ) ); ?>" name="jupiterx_adobe_fonts_project_id" placeholder="ezv****">
	</div>
	<div class="col-md-12"><hr></div>
	<div class="form-group col-md-6">
		<label for="jupiterx-cp-settings-tracking-codes-after-head">
			<?php /* translators: %s: html */ ?>
			<?php printf( esc_html__( 'Tracking Codes After %s Tag', 'jupiterx-core' ), '<code>&#x3C;head&#x3E;</code>' ); ?>
			<?php jupiterx_pro_badge(); ?>
		</label>
		<textarea <?php echo esc_attr( ( ! jupiterx_is_pro() ) ? 'disabled' : '' ); ?> class="jupiterx-form-control" rows="7" id="jupiterx-cp-settings-tracking-codes-after-head" name="jupiterx_tracking_codes_after_head" rows="3"><?php echo esc_html( stripslashes( jupiterx_get_option( 'tracking_codes_after_head' ) ) ); ?></textarea>
	</div>
	<div class="form-group col-md-6">
		<label for="jupiterx-cp-settings-tracking-codes-before-head">
			<?php /* translators: %s: html */ ?>
			<?php printf( esc_html__( 'Tracking Codes Before %s Tag', 'jupiterx-core' ), '<code>&#x3C;/head&#x3E;</code>' ); ?>
			<?php jupiterx_pro_badge(); ?>
		</label>
		<textarea <?php echo esc_attr( ( ! jupiterx_is_pro() ) ? 'disabled' : '' ); ?> class="jupiterx-form-control" rows="7" id="jupiterx-cp-settings-tracking-codes-before-head" name="jupiterx_tracking_codes_before_head" rows="3"><?php echo esc_html( stripslashes( jupiterx_get_option( 'tracking_codes_before_head' ) ) ); ?></textarea>
	</div>
	<div class="form-group col-md-6">
		<label for="jupiterx-cp-settings-tracking-codes-after-body">
			<?php /* translators: %s: html */ ?>
			<?php printf( esc_html__( 'Tracking Codes After %s Tag', 'jupiterx-core' ), '<code>&#x3C;body&#x3E;</code>' ); ?>
			<?php jupiterx_pro_badge(); ?>
		</label>
		<textarea <?php echo esc_attr( ( ! jupiterx_is_pro() ) ? 'disabled' : '' ); ?> class="jupiterx-form-control" rows="7" id="jupiterx-cp-settings-tracking-codes-after-body" name="jupiterx_tracking_codes_after_body" rows="3"><?php echo esc_html( stripslashes( jupiterx_get_option( 'tracking_codes_after_body' ) ) ); ?></textarea>
	</div>
	<div class="form-group col-md-6">
		<label for="jupiterx-cp-settings-tracking-codes-before-body">
			<?php /* translators: %s: html */ ?>
			<?php printf( esc_html__( 'Tracking Codes Before %s Tag', 'jupiterx-core' ), '<code>&#x3C;/body&#x3E;</code>' ); ?>
			<?php jupiterx_pro_badge(); ?>
		</label>
		<textarea <?php echo esc_attr( ( ! jupiterx_is_pro() ) ? 'disabled' : '' ); ?> class="jupiterx-form-control" rows="7" id="jupiterx-cp-settings-tracking-codes-before-body" name="jupiterx_tracking_codes_before_body" rows="3"><?php echo esc_html( stripslashes( jupiterx_get_option( 'tracking_codes_before_body' ) ) ); ?></textarea>
	</div>
	<?php
} );
