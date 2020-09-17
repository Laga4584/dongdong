<?php

/**
 * A class that extends WP_Customize_Setting so we can access
 * the protected updated method when importing options.
 *
 * @since 0.3
 */

require_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';

if ( ! class_exists( 'JupiterX_Customizer_Option' ) ) {

	/**
	 * Customizer option class.
	 *
	 * @since 1.9.0
	 */
	final class JupiterX_Customizer_Option extends WP_Customize_Setting {

		/**
		 * Import an option value for this setting.
		 *
		 * @since 0.3
		 * @param mixed $value The option value.
		 * @return void
		 */
		public function import( $value )
		{
			$this->update( $value );
		}
	}
}
