<?php
/**
 * Apply_Button Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Apply_Button' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Apply_Button class
	 */
	class Jet_Smart_Filters_Block_Apply_Button extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'apply-button';
		}

		/**
		 * Return callback
		 *
		 * @return html
		 */
		public function render_callback( $settings = array() ) {

			jet_smart_filters()->set_filters_used();

			if ( empty( $settings['content_provider'] ) || $settings['content_provider'] === 'not-selected' ) {
				return $this->is_editor() ? __( 'Please select a provider', 'jet-smart-filters' ) : false;
			}

			$base_class   = 'jet-smart-filters-' . $this->get_name();
			$data_atts    = '';
			$redirect     = ! empty( $settings['apply_redirect'] ) ? $settings['apply_redirect'] : false;
			$redirectPath = ! empty( $settings['redirect_path'] ) ? $settings['redirect_path'] : false;
			$atts         = array(
				'data-content-provider' => $settings['content_provider'],
				'data-apply-type'       => $settings['apply_type'],
				'data-query-id'         => 'default',
				'data-redirect'         => $redirect
			);

			if ( $redirect && $redirectPath ) {
				$atts['data-redirect-path'] = $redirectPath;
			}

			foreach ( $atts as $key => $value ) {
				$data_atts .= sprintf( ' %1$s="%2$s"', $key, $value );
			}

			ob_start();

			echo '<div class="' . $base_class . ' jet-filter">';
			include jet_smart_filters()->get_template( 'common/apply-filters.php' );
			echo '</div>';

			$filter_layout = ob_get_clean();

			return $filter_layout;

		}

	}

}