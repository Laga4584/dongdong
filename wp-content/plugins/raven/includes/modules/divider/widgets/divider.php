<?php
namespace Raven\Modules\Divider\Widgets;

use Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

class Divider extends Base_Widget {

	public function get_name() {
		return 'raven-divider';
	}

	public function get_title() {
		return __( 'Divider', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-divider';
	}

	protected function _register_controls() {
		$this->register_section_divider();
	}

	private function register_section_divider() {
		$this->start_controls_section(
			'section_divider',
			[
				'label' => __( 'Divider', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'line_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Line Color Type', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-divider-solid, {{WRAPPER}} .raven-divider-double:before, {{WRAPPER}} .raven-divider-double:after',
			]
		);

		$this->add_control(
			'line_style',
			[
				'label' => __( 'Line Type', 'raven' ),
				'type' => 'select',
				'default' => 'solid',
				'options' => [
					'solid' => __( 'Solid', 'raven' ),
					'double' => __( 'Double', 'raven' ),
				],
			]
		);

		$this->add_responsive_control(
			'line_weight',
			[
				'label' => __( 'Weight', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-divider-solid, {{WRAPPER}} .raven-divider-double:before, {{WRAPPER}} .raven-divider-double:after' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-divider-double:before' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'line_width',
			[
				'label' => __( 'Width', 'raven' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-divider-line' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'spacing',
			[
				'label' => __( 'Spacing', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-divider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'line_box_shadow',
				'selector' => '{{WRAPPER}} .raven-divider-solid, {{WRAPPER}} .raven-divider-double:before, {{WRAPPER}} .raven-divider-double:after',
			]
		);

		$this->add_responsive_control(
			'line_align',
			[
				'label' => __( 'Alignment', 'raven' ),
				'type' => 'choose',
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'raven' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'raven' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'raven' ),
						'icon' => 'fa fa-align-right',
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'line', 'class', 'raven-divider-line raven-divider-' . $settings['line_style'] );
		?>
		<div class="raven-widget-wrapper">
			<div class="raven-divider">
				<span <?php echo $this->get_render_attribute_string( 'line' ); ?>></span>
			</div>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
		view.addRenderAttribute( 'line', 'class', 'raven-divider-line raven-divider-' + settings.line_style );
		#>
		<div class="raven-widget-wrapper">
			<div class="raven-divider">
				<span {{{ view.getRenderAttributeString( 'line' ) }}}></span>
			</div>
		</div>
		<?php
	}
}
