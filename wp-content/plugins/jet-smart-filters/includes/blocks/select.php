<?php
/**
 * Select Filter
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Block_Select' ) ) {

	/**
	 * Define Jet_Smart_Filters_Block_Select class
	 */
	class Jet_Smart_Filters_Block_Select extends Jet_Smart_Filters_Block_Base {

		/**
		 * Returns block name
		 *
		 * @return string
		 */
		public function get_name() {
			return 'select';
		}

	}

}