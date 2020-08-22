<?php
/**
 * Main class that handles Google fonts.
 *
 * @package JupiterX\Framework\API\Fonts
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Google fonts loader class.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Fonts
 */
final class _JupiterX_Load_Google_Fonts {

	/**
	 * Google fonts.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $google_fonts = [];

	/**
	 * Construct the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		$this->google_fonts = JupiterX_Fonts::get_registered_fonts( 'google' );

		// Don't enqueue if there's no Google font registered from the Customizer.
		if ( empty( $this->google_fonts ) ) {
			return;
		}

		wp_enqueue_script( 'jupiterx-webfont' );

		// Print script.
		wp_add_inline_script( 'jupiterx-webfont', $this->get_script() );
	}

	/**
	 * Get script to print.
	 *
	 * @since 1.0.0
	 */
	public function get_script() {
		$fonts = array_keys( $this->google_fonts );

		// String weights.
		$weights = implode( ',', JupiterX_Fonts::FONT_WEIGHTS );

		// Add weights to each fonts.
		$fonts = array_map( function ( $value ) use ( $weights ) {
			$subsets = '';

			if ( ! empty( $this->google_fonts[ $value ]['subsets'] ) ) {
				$subsets = implode( ',', $this->google_fonts[ $value ]['subsets'] );
			}

			$subsets = ! empty( $subsets ) && 'latin' !== $subsets ? ':' . $subsets : '';

			return "{$value}:{$weights}{$subsets}";
		}, $fonts );

		$fonts = implode( "','", $fonts );

		return "WebFont.load({
			google: {
				families: ['{$fonts}']
			}
		});";
	}
}

new _JupiterX_Load_Google_Fonts();
