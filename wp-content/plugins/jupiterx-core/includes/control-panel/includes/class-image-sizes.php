<?php
/**
 * This class provides the methods to Store and retrieve Image sizes from database.
 *
 * @package JupiterX_Core\Control_Panel\Image_Sizes
 *
 * @since 1.2.0
 */


if ( ! class_exists( 'JupiterX_Control_Panel_Image_Sizes' ) ) {
	/**
	 * Store and retrieve Image sizes.
	 *
	 * @since 1.2.0
	 */
	class JupiterX_Control_Panel_Image_Sizes {

		/**
		 * Array of custom image sizes.
		 *
		 * @since 1.11.0
		 * @access public
		 *
		 * @var array
		 */
		protected static $default_options = [
			[
				'size_w'  => 500,
				'size_h'  => 500,
				'size_n'  => 'Image Size 500x500',
				'size_c'  => 'on',
				'default' => true,
				'id'      => 1,
			],
		];

		/**
		 * Class constructor.
		 *
		 * @since 1.2.0
		 */
		public function __construct() {
			add_filter( 'jupiterx_control_panel_pane_image_sizes', [ $this, 'view' ] );
			add_action( 'wp_ajax_jupiterx_save_image_sizes', [ $this, 'save_image_size' ] );
		}

		/**
		 * Return list of the stored image sizes.
		 *
		 * If empty, it will return default sample size.
		 *
		 * @since 1.2.0
		 *
		 * @return array
		 */
		public static function get_available_image_sizes() {
			$options = get_option( JUPITERX_IMAGE_SIZE_OPTION );

			if ( empty( $options ) ) {
				$options = [];
			}

			$existing_default_options = [];

			foreach ( $options as $option ) {
				if ( ! empty( $option['default'] ) ) {
					$existing_default_options[] = intval( $option['id'] );
				}
			}

			$deleted_default_options = get_option( 'jupiterx_image_sizes_deleted' );

			if ( false === $deleted_default_options ) {
				$deleted_default_options = [];
			}

			foreach ( self::$default_options as $default_option ) {
				if (
					in_array( $default_option['id'], $deleted_default_options, true ) ||
					in_array( $default_option['id'], $existing_default_options, true )
				) {
					continue;
				}

				array_unshift( $options, $default_option );
			}

			return $options;
		}

		/**
		 * Image sizes HTML directory.
		 *
		 * @since 1.2.0
		 *
		 * @return string
		 */
		public function view() {
			return jupiterx_core()->plugin_dir() . 'includes/control-panel/views/image-sizes.php';
		}

		/**
		 * Process image sizes data passed via admin-ajax.php and store it in wp_options table.
		 *
		 * @since 1.2.0
		 */
		public function save_image_size() {
			check_ajax_referer( 'ajax-image-sizes-options', 'security' );

			$options = [];

			if ( ! empty( $_POST['options'] ) ) {
				$options = $_POST['options'];
			}

			// phpcs:disable
			$options = array_map( 'sanitize_text_field', $options );
			// phpcs:enable

			$options_array   = [];
			$default_options = [];

			foreach ( $options as $sizes ) {
				parse_str( $sizes, $output );

				if ( ! empty( $output['default'] ) ) {
					$default_options[] = intval( $output['id'] );
				}

				$options_array[] = $output;
			}

			$deleted_default_options = [];

			foreach (self::$default_options as $default_option) {
				if ( in_array( $default_option['id'], $default_options, true ) ) {
					continue;
				}

				$deleted_default_options[] = $default_option['id'];
			}

			update_option( 'jupiterx_image_sizes_deleted', $deleted_default_options );
			update_option( JUPITERX_IMAGE_SIZE_OPTION, $options_array );

			wp_die( 1 );
		}
	}
}

new JupiterX_Control_Panel_Image_Sizes();
