<?php
/**
 * This class handles admin notices.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 1.18.0
 */

/**
 * Handle admin notices.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 1.18.0
 */
class JupiterX_Core_Admin_Notices {

	/**
	 * Constructor.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		add_action( 'admin_notices', [ $this, 'check_required_plugins' ] );
	}

	/**
	 * Check required plugins.
	 *
	 * @since 1.18.0
	 */
	public function check_required_plugins() {
		$required_plugins = [
			'Elementor\Plugin',
			'ACF',
		];

		foreach ( $required_plugins as $key => $plugin ) {
			if ( ! class_exists( $plugin ) ) {
				continue;
			}

			unset( $required_plugins[ $key ] );
		}

		if ( empty( $required_plugins ) ) {
			return;
		}

		?>
		<div class="notice notice-warning is-dismissible">
			<p>
			<?php
				printf(
					/* translators: The required plugins. */
					esc_html__( '%1$s requires %2$s and %3$s plugins to be installed and activated.', 'jupiterx-core' ),
					'<strong>Jupiter X</strong>',
					'<strong>Elementor</strong>',
					'<strong>Advanced Custom Fields</strong>'
				);
			?>
			</p>
			<p><a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=jupiterx#/plugins' ); ?>"><?php esc_html_e( 'Activate them in Control Panel > Plugins', 'jupiterx-core' ); ?></a></p>
		</div>
		<?php
	}

}

new JupiterX_Core_Admin_Notices();
