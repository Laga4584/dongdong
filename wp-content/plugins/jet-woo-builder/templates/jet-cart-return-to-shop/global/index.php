<?php
/**
 * Cart Return to Shop Template
 */

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>

	<p class="return-to-shop">
		<a class="button wc-backward" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
			<?php echo esc_html__( 'Return to shop', 'jet-woo-builder' ); ?>
		</a>
	</p>

<?php endif; ?>