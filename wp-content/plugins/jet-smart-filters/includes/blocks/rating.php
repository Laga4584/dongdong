<?php
/**
 * Rating Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Rating' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Rating class
	 */
	class Jet_Smart_Filters_Block_Rating extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'rating';
		}

	}

}