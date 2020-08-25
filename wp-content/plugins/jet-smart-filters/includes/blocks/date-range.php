<?php
/**
 * Date Range Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Date_Range' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Date_Range class
	 */
	class Jet_Smart_Filters_Block_Date_Range extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'date-range';
		}

		/**
		 * Return callback
		 *
		 * @return html
		 */
		public function render_callback( $settings = array() ) {

			jet_smart_filters()->set_filters_used();

			if ( empty( $settings['filter_id'] ) ) {
				return $this->is_editor() ? __( 'Please select a filter', 'jet-smart-filters' ) : false;
			}

			if ( empty( $settings['content_provider'] ) || $settings['content_provider'] === 'not-selected' ) {
				return $this->is_editor() ? __( 'Please select a provider', 'jet-smart-filters' ) : false;
			}

			if ( 'ajax' === $settings['apply_type'] ) {
				$apply_type = 'ajax-reload';
			} else {
				$apply_type = $settings['apply_type'];
			}

			$filter_id         = $settings['filter_id'];
			$base_class        = 'jet-smart-filters-' . $this->get_name();
			$provider          = $settings['content_provider'];
			$query_id          = 'default';
			$show_label        = $settings['show_label'];
			$hide_button       = $settings['hide_apply_button'];
			$apply_button_text = $settings['apply_button_text'];

			ob_start();

			printf( '<div class="%1$s jet-filter">', $base_class );

			jet_smart_filters()->filter_types->render_filter_template( $this->get_name(), array(
				'filter_id'            => $filter_id,
				'content_provider'     => $provider,
				'query_id'             => $query_id,
				'apply_type'           => $apply_type,
				'hide_button'          => $hide_button,
				'button_text'          => $apply_button_text,
				'show_label'           => $show_label,
			) );
	
			echo '</div>';

			$filter_layout = ob_get_clean();

			return $filter_layout;

		}

	}

}