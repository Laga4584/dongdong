<?php
namespace Raven\Modules\Post_Meta;

defined( 'ABSPATH' ) || die();

use Raven\Base\Module_base;

class Module extends Module_Base {

	/**
	 * Register module widgets.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_widgets() {
		return [ 'post-meta' ];
	}
}
