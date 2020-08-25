<?php
/**
 * Remove_Filters Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Remove_Filters' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Remove_Filters class
	 */
	class Jet_Smart_Filters_Block_Remove_Filters extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'remove-filters';
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

			$base_class = 'jet-smart-filters-' . $this->get_name();
			$provider   = $settings['content_provider'];
			$query_id   = 'default';
			$edit_mode  = $this->is_editor();
			$additional_providers = '';

			ob_start();

			echo '<div class="' . $base_class . ' jet-filter">';
			include jet_smart_filters()->get_template( 'common/remove-filters.php' );
			echo '</div>';

			$filter_layout = ob_get_clean();

			return $filter_layout;

		}

	}

}