<?php
/**
 * Search Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Search' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Search class
	 */
	class Jet_Smart_Filters_Block_Search extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'search';
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

			$base_class = 'jet-smart-filters-' . $this->get_name();
			$provider   = $settings['content_provider'];
			$query_id   = 'default';
			$show_label = $settings['show_label'];

			if ( in_array( $settings['apply_type'], ['ajax', 'mixed'] ) ) {
				$apply_type = $settings['apply_type'] . '-reload';
			} else {
				$apply_type = $settings['apply_type'];
			}

			ob_start();

			printf( '<div class="%1$s jet-filter">', $base_class );

			jet_smart_filters()->filter_types->render_filter_template( $this->get_name(), array(
				'filter_id'            => $settings['filter_id'],
				'content_provider'     => $provider,
				'query_id'             => $query_id,
				'apply_type'           => $apply_type,
				'button_text'          => $settings['apply_button_text'],
				'show_label'           => $show_label,
				'min_letters_count'    => $settings['typing_min_letters_count'],
				//'button_icon'          => $icon,
				//'button_icon_position' => $settings['filter_apply_button_icon_position'],
			) );

			echo '</div>';

			$filter_layout = ob_get_clean();

			return $filter_layout;

		}

	}

}