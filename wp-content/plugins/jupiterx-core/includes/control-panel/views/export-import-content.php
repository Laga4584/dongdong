<?php
if ( ! JUPITERX_CONTROL_PANEL_EXPORT_IMPORT ) {
	return;
}
?>
<div class="jupiterx-cp-pane-box bootstrap-wrapper">
	<div class="alert alert-danger"><?php printf( __( 'The Export/Import feature is deprecated. To migrate/backup your website use <a href="%s" target="_blank">3rd-party plugins</a>.', 'jupiterx-core' ), 'https://wordpress.org/plugins/tags/backup/' ) ?></div>
	<h3><?php esc_html_e( 'Export', 'jupiterx-core' ); ?></h3>
	<div class="jupiterx-cp-export-wrap jupiterx-card mb-5">
		<form class="jupiterx-cp-export-form">
			<div class="jupiterx-card-body">
			<div class="jupiterx-form-group">
				<label for="filename">File Name</label>
				<input type="text" class="jupiterx-form-control" id="filename" name="filename" placeholder="<?php echo get_bloginfo( 'name' )?>-jupiterx">
			</div>
			<br>
			<div class="custom-control custom-checkbox">
				<input checked="" class="custom-control-input" id="site-content" name="site-content" type="checkbox" value="Content">
					<label class="custom-control-label" for="site-content">
						<?php esc_html_e( 'Site Content', 'jupiterx-core' ); ?>
					</label>
				</input>
			</div>
			<div class="custom-control custom-checkbox">
				<input checked="" class="custom-control-input" id="site-widgets" name="site-widgets" type="checkbox" value="Widgets">
					<label class="custom-control-label" for="site-widgets">
						<?php esc_html_e( 'Widgets', 'jupiterx-core' ); ?>
					</label>
				</input>
			</div>
			<div class="custom-control custom-checkbox">
				<input checked="" class="custom-control-input" id="site-settings" name="site-settings" type="checkbox" value="Settings">
					<label class="custom-control-label" for="site-settings">
						<?php esc_html_e( 'Settings', 'jupiterx-core' ); ?>
					</label>
				</input>
			</div>
			<div class="custom-control custom-checkbox">
				<input checked="" class="custom-control-input" id="custom-tables" name="custom-tables" type="checkbox" value="Custom Tables">
					<label class="custom-control-label" for="custom-tables">
						<?php esc_html_e( 'Custom Tables', 'jupiterx-core' ); ?>
					</label>
				</input>
			</div>
		</div>
		<hr style="margin: 0;">
		<div class="jupiterx-card-body">
			<button class="btn btn-primary jupiterx-cp-export-btn" type="submit">
				<?php esc_html_e( 'Export', 'jupiterx-core' ); ?>
			</button>
		</div>
		</form>
	</div>
	<h3><?php esc_html_e( 'Import', 'jupiterx-core' ); ?></h3>
	<div class="jupiterx-cp-import-wrap jupiterx-card">
		<div class="jupiterx-card-body">
			<div class="jupiterx-upload-wrap w-75">
				<div class="input-group">
					<input class="jupiterx-form-control" placeholder="Select a Zip file" type="text">
						<div class="input-group-append">
							<a class="btn btn-secondary jupiterx-cp-import-upload-btn" href="#">
								<?php esc_html_e( 'Upload', 'jupiterx-core' ); ?>
							</a>
						</div>
					</input>
				</div>
			</div>
			</div>
			<hr style="margin: 0;">
			<div class="jupiterx-card-body">
			<button class="btn btn-primary jupiterx-cp-import-btn" type="submit">
				<?php esc_html_e( 'Import', 'jupiterx-core' ); ?>
			</button>
		</div>

	</div>
</div>
