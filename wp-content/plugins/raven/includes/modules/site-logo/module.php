<?php
namespace Raven\Modules\Site_Logo;

defined( 'ABSPATH' ) || die();

use Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'site-logo' ];
	}
}
