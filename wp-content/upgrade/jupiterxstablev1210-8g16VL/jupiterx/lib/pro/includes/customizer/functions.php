<?php
/**
 * The Jupiter Customizer component.
 *
 * @package JupiterX\Pro\Customizer
 */

add_action( 'init', 'jupiterx_pro_customizer' );
/**
 * Load customizer settings.
 *
 * @since 1.6.0
 */
function jupiterx_pro_customizer() {

	// Load all the settings.
	foreach ( glob( dirname( __FILE__ ) . '/**/*.php' ) as $setting ) {
		require_once $setting;
	}
}
