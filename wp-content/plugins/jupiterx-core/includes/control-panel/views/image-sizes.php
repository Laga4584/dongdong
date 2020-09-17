<?php
/**
 * Image sizes template.
 *
 * @package JupiterX_Core\Control_Panel\Image_Sizes
 *
 * @since 1.2.0
 */

if ( ! JUPITERX_CONTROL_PANEL_IMAGE_SIZES ) {
	return;
}
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-image-sizes">
	<h3>
		<?php esc_html_e( 'Image Sizes', 'jupiterx-core' ); ?>
		<?php jupiterx_the_help_link( 'https://themes.artbees.net/docs/custom-size-for-images/', esc_html__( 'Custom size for images', 'jupiterx-core' ) ); ?>
	</h3>
	<button type="button" class="btn btn-primary image-size-add-new-btn js__cp-clist-add-item">
		<?php esc_html_e( 'Add a New Size', 'jupiterx-core' ); ?>
	</button>
	<div class="jupiterx-wrap jupiterx-img-size-wrap">
		<div class="jupiterx-img-size">
			<div class="jupiterx-img-size-list js__jupiterx-img-size-list clearfix mb-3">
				<?php
				foreach ( JupiterX_Control_Panel_Image_Sizes::get_available_image_sizes() as $size ) :
					if ( ! empty( $size['size_n'] ) && ! empty( $size['size_w'] ) && ! empty( $size['size_h'] ) ) :
						?>
						<div class="jupiterx-img-size-item js__cp-image-size-item">
							<div class="jupiterx-img-size-item-inner jupiterx-card">
								<div class="jupiterx-card-body fetch-input-data">
									<div class="js__size-name mb-3">
										<strong><?php esc_html_e( 'Name', 'jupiterx-core' ); ?>:</strong> <?php echo wp_kses_post( $size['size_n'] ); ?>
									</div>
									<div class="js__size-dimension mb-3">
										<strong><?php esc_html_e( 'Size', 'jupiterx-core' ); ?>:</strong> <?php echo wp_kses_post( $size['size_w'] ); ?>px <?php echo wp_kses_post( $size['size_h'] ); ?>px
									</div>
									<div class="js__size-crop mb-3">
										<strong><?php esc_html_e( 'Hard Crop', 'jupiterx-core' ); ?>:</strong>
										<?php if ( 'on' === $size['size_c'] ) : ?>
											<span><?php esc_html_e( 'On', 'jupiterx-core' ); ?></span>
										<?php else : ?>
											<span><?php esc_html_e( 'Off', 'jupiterx-core' ); ?></span>
										<?php endif; ?>
									</div>
									<button type="button" class="btn btn-outline-success js__cp-clist-edit-item mr-1"><?php esc_html_e( 'Edit', 'jupiterx-core' ); ?></button>
									<button type="button" class="btn btn-outline-danger js__cp-clist-remove-item"><?php esc_html_e( 'Remove', 'jupiterx-core' ); ?></button>
									<input name="size_n" type="hidden" value="<?php echo esc_attr( $size['size_n'] ); ?>" />
									<input name="size_w" type="hidden" value="<?php echo esc_attr( $size['size_w'] ); ?>" />
									<input name="size_h" type="hidden" value="<?php echo esc_attr( $size['size_h'] ); ?>" />
									<input name="size_c" type="hidden" value="<?php echo esc_attr( $size['size_c'] ); ?>" />
									<?php if ( ! empty( $size['default'] ) ) : ?>
										<input name="default" type="hidden" value="true" />
										<input name="id" type="hidden" value="<?php echo esc_attr( $size['id'] ); ?>" />
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
					endif;
				endforeach;
				?>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php wp_nonce_field( 'ajax-image-sizes-options', 'security' ); ?>
	</div>
</div>
