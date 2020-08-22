<?php
namespace Raven\Modules\Heading;

defined( 'ABSPATH' ) || die();

use Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'heading' ];
	}

}
