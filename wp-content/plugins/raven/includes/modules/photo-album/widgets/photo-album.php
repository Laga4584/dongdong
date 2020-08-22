<?php
namespace Raven\Modules\Photo_Album\Widgets;

use Raven\Base\Base_Widget;
use Raven\Modules\Photo_Album\Skins;

defined( 'ABSPATH' ) || die();

class Photo_Album extends Base_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'raven-photo-album';
	}

	public function get_title() {
		return __( 'Photo Album', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-photo-album';
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'raven-savvior', 'raven-anime', 'raven-stack-motion-effects' ];
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Cover( $this ) );
		$this->add_skin( new Skins\Skin_Stack( $this ) );
	}

	protected function _register_controls() {
		$this->register_content_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'raven' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'images',
			[
				'type' => 'gallery',
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'raven' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'placeholder' => 'Title',
			]
		);

		$repeater->add_control(
			'description',
			[
				'type' => 'textarea',
				'dynamic' => [
					'active' => true,
				],
				'label' => __( 'Description', 'raven' ),
				'label_block' => true,
				'placeholder' => 'Description',
			]
		);

		$repeater->add_control(
			'stack_color',
			[
				'type' => 'color',
				'label' => __( 'Stack Color', 'raven' ),
			]
		);

		$this->add_control(
			'list',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
			]
		);

		$this->end_controls_section();

		$this->update_control(
			'_skin',
			[
				'frontend_available' => 'true',
			]
		);
	}
}
