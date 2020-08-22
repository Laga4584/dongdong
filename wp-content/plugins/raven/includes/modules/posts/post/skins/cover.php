<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace Raven\Modules\Posts\Post\Skins;

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

    protected function _register_controls_actions() {
        parent::_register_controls_actions();

        $this->remove_control( 'mirror_rows' );
    }
}
