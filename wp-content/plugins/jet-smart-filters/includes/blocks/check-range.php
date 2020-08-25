<?php
/**
 * Check Range Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Check_Range' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Check_Range class
	 */
	class Jet_Smart_Filters_Block_Check_Range extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'check-range';
		}

	}

}