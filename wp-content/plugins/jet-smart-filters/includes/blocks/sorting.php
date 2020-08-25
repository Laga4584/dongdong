<?php
/**
 * Sorting Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Sorting' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Sorting class
	 */
	class Jet_Smart_Filters_Block_Sorting extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'sorting';
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

			$sorting_filter          = jet_smart_filters()->filter_types->get_filter_types( 'sorting' );
			$sorting_options         = $sorting_filter->sorting_options( $settings['sorting_list'] );
			$container_data_atts     = $sorting_filter->container_data_atts( $settings );
			$placeholder             = ! empty( $settings['sorting_placeholder'] ) ? $settings['sorting_placeholder'] : __( 'Sort...', 'jet-smart-filters' );
			$label                   = $settings['sorting_label'];
			$settings['label_block'] = 'yes';

			ob_start();

			include jet_smart_filters()->get_template( 'filters/sorting.php' );

			if ( $settings['apply_button'] ) {
				include jet_smart_filters()->get_template( 'common/apply-filters.php' );
			}

			$filter_layout = ob_get_clean();

			return $filter_layout;

		}

	}

}