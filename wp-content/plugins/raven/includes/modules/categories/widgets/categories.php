<?php
namespace Raven\Modules\Categories\Widgets;

use Raven\Base\Base_Widget;
use Raven\Modules\Categories\Skins;

defined( 'ABSPATH' ) || die();

class Categories extends Base_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'raven-categories';
	}

	public function get_title() {
		return __( 'Categories', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-categories';
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'raven-savvior' ];
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Outer_Content( $this ) );
		$this->add_skin( new Skins\Skin_Inner_Content( $this ) );
	}

	protected function _register_controls() {
		$this->register_content_controls();
		$this->register_filter_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'raven' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __( 'Source', 'raven' ),
				'type' => 'select',
				'default' => 'blog',
				'options' => [
					'blog' => __( 'Blog', 'raven' ),
					'portfolio' => __( 'Portfolio', 'raven' ),
					'product' => __( 'Shop', 'raven' ),
				],
				'frontend_available' => 'true',
			]
		);

		$this->add_control(
			'specific_categories',
			[
				'label' => __( 'Specific Categories', 'raven' ),
				'type' => 'select2',
				'multiple' => true,
				'options' => [],
				'label_block' => true,
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

	private function register_filter_controls() {
		$this->start_controls_section(
			'section_filter',
			[
				'label' => __( 'Filter', 'raven' ),
			]
		);

		$this->add_control(
			'exclude',
			[
				'label' => __( 'Exclude', 'raven' ),
				'type' => 'select2',
				'multiple' => true,
				'options' => [],
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}
}
