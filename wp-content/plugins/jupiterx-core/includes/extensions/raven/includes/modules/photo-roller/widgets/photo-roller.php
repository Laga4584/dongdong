<?php
namespace JupiterX_Core\Raven\Modules\Photo_Roller\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Photo_Roller extends Base_Widget {

	public function get_name() {
		return 'raven-photo-roller';
	}

	public function get_title() {
		return __( 'Photo Roller', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-photo-roller';
	}

	protected function _register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'image',
			[
				'label' => __( 'Image', 'jupiterx-core' ),
				'type' => 'media',
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-photo-roller-frame::after' => 'background-image: url({{URL}});',
				],
				'render_type' => 'template',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'rolling_speed',
			[
				'label' => __( 'Rolling Speed (px/s)', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-photo-roller-frame' => 'animation-duration: calc( 301s - {{SIZE}}s );',
				],
			]
		);

		$this->add_control(
			'reverse_direction',
			[
				'label' => __( 'Reverse direction', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-roller-frame' => 'animation-direction: reverse;',
				],
			]
		);

		$this->add_control(
			'pause_hover',
			[
				'label' => __( 'Pause on hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-photo-roller-frame:hover' => 'animation-play-state: paused;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings     = $this->get_settings_for_display();
		$css_selector = '.raven-photo-roller-' . $this->get_id() . ' .raven-photo-roller-frame';

		$this->add_render_attribute(
			'wrapper',
			'class',
			'raven-photo-roller raven-photo-roller-' . $this->get_id()
		);

		// TODO: Refactor to use a better way.
		if ( $settings['image']['id'] ) {
			$image = wp_get_attachment_image_src( $settings['image']['id'], 'full' );

			$settings['image']['width']  = $image[1];
			$settings['image']['height'] = $image[2];
		}

		if ( $settings['image_tablet']['id'] ) {
			$image = wp_get_attachment_image_src( $settings['image_tablet']['id'], 'full' );

			$settings['image_tablet']['width']  = $image[1];
			$settings['image_tablet']['height'] = $image[2];
		}

		if ( $settings['image_mobile']['id'] ) {
			$image = wp_get_attachment_image_src( $settings['image_mobile']['id'], 'full' );

			$settings['image_mobile']['width']  = $image[1];
			$settings['image_mobile']['height'] = $image[2];
		}

		// Render custom styles for this element.
		$this->render_styles( $css_selector, $settings );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="raven-photo-roller-frame">
				<picture>
					<?php if ( ! empty( $settings['image_mobile']['url'] ) ) : ?>
					<source media="(max-width: 767px)" srcset="<?php echo $settings['image_mobile']['url']; ?>">
					<?php endif; ?>

					<?php if ( ! empty( $settings['image_tablet']['url'] ) ) : ?>
					<source media="(max-width: 1024px)" srcset="<?php echo $settings['image_tablet']['url']; ?>">
					<?php endif; ?>

					<img class="raven-photo-roller-frame-img" src="<?php echo $settings['image']['url']; ?>" alt="" itemprop="image">
				</picture>
			</div>
		</div>
		<?php
	}

	/**
	 * @todo Refactor to use a better way.
	 */
	protected function render_styles( $selector, $settings ) {
		echo '<style type="text/css">';

		if ( isset( $settings['image']['width'] ) ) {
			printf( '%1$s, %1$s img { width: %2$spx; height: %3$spx; }', $selector, $settings['image']['width'], $settings['image']['height'] );
		}

		if ( isset( $settings['image_tablet']['width'] ) ) {
			printf( '@media (max-width: 1024px) { %1$s, %1$s img { width: %2$spx; height: %3$spx; } }', $selector, $settings['image_tablet']['width'], $settings['image_tablet']['height'] );
		}

		if ( isset( $settings['image_mobile']['width'] ) ) {
			printf( '@media (max-width: 767px) { %1$s, %1$s img { width: %2$spx; height: %3$spx; } }', $selector, $settings['image_mobile']['width'], $settings['image_mobile']['height'] );
		}

		echo '</style>';
	}
}
