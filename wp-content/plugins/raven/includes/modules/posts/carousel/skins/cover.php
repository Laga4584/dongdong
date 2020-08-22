<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace Raven\Modules\Posts\Carousel\Skins;

defined( 'ABSPATH' ) || die();

use Raven\Utils;
use Raven\Modules\Posts\Module;

class Cover extends Base {

	public function get_id() {
		return 'cover';
	}

	public function get_title() {
		return __( 'Inner Content', 'raven' );
	}
}
