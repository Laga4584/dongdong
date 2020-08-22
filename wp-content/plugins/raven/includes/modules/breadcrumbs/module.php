<?php
namespace Raven\Modules\Breadcrumbs;

defined( 'ABSPATH' ) || die();

use Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'breadcrumbs' ];
	}

}
