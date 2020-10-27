<?php
/**
 * Add Base Widget.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Base;

defined( 'ABSPATH' ) || die();

use Elementor\Widget_Base;

/**
 * Base Widget.
 *
 * An abstract class to register new Raven widgets.
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Base_Widget extends Widget_Base {

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'jupiterx-core-raven-elements' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the widget keywords.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'raven', 'jupiter', 'jupiterx' ];
	}

	/**
	 * Retrieve widget active state.
	 *
	 * Use to disable or enable the widget on a certain condition.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return true;
	}
}
