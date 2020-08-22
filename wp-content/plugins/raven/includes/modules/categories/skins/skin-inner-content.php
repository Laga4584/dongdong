<?php
namespace Raven\Modules\Categories\Skins;

use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Skin_Inner_Content extends Skin_Base {
	public function get_id() {
		return 'inner_content';
	}

	public function get_title() {
		return __( 'Inner Content', 'raven' );
	}

	protected function register_image_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Featured Image', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'image_background_position',
			[
				'label' => __( 'Background Position', 'raven' ),
				'type' => 'select',
				'default' => 'center center',
				'options' => [
					'center center' => __( 'Center Center', 'raven' ),
					'center left' => __( 'Center Left', 'raven' ),
					'center right' => __( 'Center Right', 'raven' ),
					'top center' => __( 'Top Center', 'raven' ),
					'top left' => __( 'Top Left', 'raven' ),
					'top right' => __( 'Top Right', 'raven' ),
					'bottom center' => __( 'Bottom Center', 'raven' ),
					'bottom left' => __( 'Bottom Left', 'raven' ),
					'bottom right' => __( 'Bottom Right', 'raven' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-img' => 'background-position: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_background_size',
			[
				'label' => __( 'Background Size', 'raven' ),
				'type' => 'select',
				'default' => 'cover',
				'options' => [
					'auto' => __( 'Auto', 'raven' ),
					'cover' => __( 'Cover', 'raven' ),
					'contain' => __( 'Contain', 'raven' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-img' => 'background-size: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'image_hover_effect',
			[
				'label' => __( 'Hover Effect', 'raven' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => __( 'None', 'raven' ),
					'slide-right' => __( 'Slide Right', 'raven' ),
					'slide-down' => __( 'Slide Down', 'raven' ),
					'scale-down' => __( 'Scale Down', 'raven' ),
					'scale-up' => __( 'Scale Up', 'raven' ),
					'blur' => __( 'Blur', 'raven' ),
					'grayscale-reverse' => __( 'Grayscale to Color', 'raven' ),
					'grayscale' => __( 'Color to Grayscale', 'raven' ),
				],
				'prefix_class' => 'raven-hover-',
			]
		);

		$this->start_controls_tabs( 'image_tabs' );

		$this->start_controls_tab(
			'image_tab_normal',
			[
				'label' => __( 'Normal', 'raven' ),
			]
		);

		$this->add_control(
			'image_opacity_normal',
			[
				'label' => __( 'Opacity', 'raven' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'overlay_tab_background_normal',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Overlay Color Type', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-img::before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_tab_hover',
			[
				'label' => __( 'Hover', 'raven' ),
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label' => __( 'Opacity', 'raven' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-categories-item:hover .raven-categories-img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'overlay_tab_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Overlay Color Type', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-categories-item:hover .raven-categories-img::before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	protected function render_skin_image( $settings ) {
		?>
		<div class="raven-categories-img" style="background-image: url('<?php echo Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image', $settings ); ?>')"></div>
		<?php
	}
}
