<?php
/**
 * Pagination Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Pagination' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Pagination class
	 */
	class Jet_Smart_Filters_Block_Pagination extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block namepagination
		 *
		 * @return string
		 */
		public function get_name() {
			return 'pagination';
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
			$apply_type = $settings['apply_type'];

			$controls_enabled = isset( $settings['enable_prev_next'] ) ? $settings['enable_prev_next'] : '';

			if ( $controls_enabled ) {
				$controls = array(
					'nav'  => true,
					'prev' => $settings['prev_text'],
					'next' => $settings['next_text'],
				);
			} else {
				$controls['nav'] = false;
			}

			$controls['pages_mid_size']  = ! empty( $settings['pages_center_offset'] ) ? absint( $settings['pages_center_offset'] ) : 0;
			$controls['pages_end_size']  =  ! empty( $settings['pages_end_offset'] ) ? absint( $settings['pages_end_offset'] ) : 0;

			ob_start();

			printf(
				'<div
					class="%1$s"
					data-apply-provider="%2$s"
					data-content-provider="%2$s"
					data-query-id="%3$s"
					data-controls="%4$s"
					data-apply-type="%5$s"
				>',
				$base_class,
				$provider,
				$query_id,
				htmlspecialchars( json_encode( $controls ) ),
				$apply_type
			);

			if ( $this->is_editor() ) {
				$pagination_filter_type = jet_smart_filters()->filter_types->get_filter_types( 'pagination' );
				$pagination_filter_type->render_pagination_sample( $controls );
			}

			echo '</div>';

			$filter_layout = ob_get_clean();

			return $filter_layout;

		}

	}

}