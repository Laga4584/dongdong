<?php
/**
 * Active_Tags Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Active_Tags' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Active_Tags class
	 */
	class Jet_Smart_Filters_Block_Active_Tags extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'active-tags';
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

			$base_class  = 'jet-smart-filters-' . $this->get_name();
			$provider    = $settings['content_provider'];
			$query_id    = 'default';
			$clear_item  = isset( $settings['clear_item'] ) ? filter_var( $settings['clear_item'], FILTER_VALIDATE_BOOLEAN ) : false;
			$clear_label = ! empty( $settings['clear_item_label'] ) && $clear_item ? $settings['clear_item_label'] : false;

			ob_start();

			printf(
				'<div class="%1$s jet-active-tags" data-label="%5$s" data-clear-item-label="%6$s" data-content-provider="%2$s" data-apply-type="%3$s" data-query-id="%4$s">',
				$base_class,
				$provider,
				$settings['apply_type'],
				$query_id,
				$settings['tags_label'],
				$clear_label ? $clear_label : false
			);
	
			if ( $this->is_editor() ) {
				$active_filters_type = jet_smart_filters()->filter_types->get_filter_types( 'active-filters' );
				$active_filters_type->render_tags_sample( $settings );
			}
	
			echo '</div>';

			$filter_layout = ob_get_clean();

			return $filter_layout;

		}

	}

}