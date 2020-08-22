<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace Raven\Modules\Posts\Carousel\Skins;

defined( 'ABSPATH' ) || die();

use Raven\Utils;
use Raven\Modules\Posts\Module;

class Classic extends Base {

	public function get_id() {
		return 'classic';
	}

	public function get_title() {
		return __( 'Outer Content', 'raven' );
	}
}
