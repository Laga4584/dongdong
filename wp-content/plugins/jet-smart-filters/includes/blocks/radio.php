<?php
/**
 * Radio Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Radio' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Radio class
	 */
	class Jet_Smart_Filters_Block_Radio extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'radio';
		}

	}

}