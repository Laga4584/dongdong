<?php
namespace Raven\Modules\Countdown\Widgets;

use Raven\Base\Base_Widget;
use Raven\Utils;

defined( 'ABSPATH' ) || die();

class Countdown extends Base_Widget {

	public function get_name() {
		return 'raven-countdown';
	}

	public function get_title() {
		return __( 'Countdown', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-countdown';
	}

	public function get_script_depends() {
		return [ 'raven-countdown' ];
	}

	protected function _register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_container();
		$this->register_section_number();
		$this->register_section_title();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Content', 'raven' ),
			]
		);

		$this->add_control(
			'due_date',
			[
				'label' => __( 'Due Date', 'raven' ),
				'type'  => 'date_time',
				'default'  => date( 'Y-m-d H:i', strtotime( '+3 months' ) ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'raven' ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'raven' ),
				'type' => 'select',
				'default' => 'block',
				'frontend_available' => true,
				'options' => [
					'block' => __( 'Block', 'raven' ),
					'inline' => __( 'Inline', 'raven' ),
				],
			]
		);

		$this->add_control(
			'days',
			[
				'label' => __( 'Days', 'raven' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'raven' ),
				'label_off' => __( 'Hide', 'raven' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'hours',
			[
				'label' => __( 'Hours', 'raven' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'raven' ),
				'label_off' => __( 'Hide', 'raven' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'minutes',
			[
				'label' => __( 'Minutes', 'raven' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'raven' ),
				'label_off' => __( 'Hide', 'raven' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'seconds',
			[
				'label' => __( 'Seconds', 'raven' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'raven' ),
				'label_off' => __( 'Hide', 'raven' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'custom_label',
			[
				'label' => __( 'Custom Labels?', 'raven' ),
				'type' => 'switcher',
				'label_on' => __( 'Yes', 'raven' ),
				'label_off' => __( 'No', 'raven' ),
				'default' => 'no',
			]
		);

		$this->add_control(
			'days_label',
			[
				'label' => __( 'Days', 'raven' ),
				'type' => 'text',
				'default' => 'Days',
				'condition' => [
					'custom_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'hours_label',
			[
				'label' => __( 'Hours', 'raven' ),
				'type' => 'text',
				'default' => 'Hours',
				'condition' => [
					'custom_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'minutes_label',
			[
				'label' => __( 'Minutes', 'raven' ),
				'type' => 'text',
				'default' => 'Minutes',
				'condition' => [
					'custom_label' => 'yes',
				],
			]
		);

		$this->add_control(
			'seconds_label',
			[
				'label' => __( 'Seconds', 'raven' ),
				'type' => 'text',
				'default' => 'Seconds',
				'condition' => [
					'custom_label' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_container() {
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => __( 'Container', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'raven' ),
				'type' => 'slider',
				'default' => [
					'unit' => '%',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-countdown' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'color' => [
						'default' => '#0275d8',
					],
				],
				'selector' => '{{WRAPPER}} .raven-countdown-box',
			]
		);

		$this->add_responsive_control(
			'spacing_between',
			[
				'label' => __( 'Space Between', 'raven' ),
				'type' => 'slider',
				'default' => [
					'size' => 10,
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-countdown-box:not(:last-of-type)' => 'margin-' . Utils::get_direction( 'right' ) . ': {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'default' => [
					'top' => 20,
					'bottom' => 20,
				],
				'allowed_dimensions' => [ 'top', 'bottom' ],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-countdown-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'raven' ),
				'type' => 'choose',
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
				'default' => 'center',
				'selectors_dictionary' => [
					'left' => 'margin: 0 auto 0 0;',
					'center' => 'margin: 0 auto 0 auto;',
					'right' => 'margin: 0 0 0 auto;',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-countdown' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .raven-countdown-box',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-countdown-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-countdown-box',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_number() {
		$this->start_controls_section(
			'section_style_number',
			[
				'label' => __( 'Number', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .raven-countdown-number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'number_typography',
				'selector' => '{{WRAPPER}} .raven-countdown-number',
				'scheme' => '2',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_title() {
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .raven-countdown-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .raven-countdown-title',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'raven' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-countdown-block .raven-countdown-title' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-countdown-inline .raven-countdown-title' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'raven-countdown raven-flex' );
		$this->add_render_attribute( 'wrapper', 'class', 'raven-countdown-' . $settings['view'] );
		$this->add_render_attribute( 'wrapper', 'data-raven-countdown', $settings['due_date'] );

		if ( 'yes' === $settings['custom_label'] ) {
			$this->add_render_attribute( 'wrapper', 'data-raven-days', $settings['days_label'] );
			$this->add_render_attribute( 'wrapper', 'data-raven-hours', $settings['hours_label'] );
			$this->add_render_attribute( 'wrapper', 'data-raven-minutes', $settings['minutes_label'] );
			$this->add_render_attribute( 'wrapper', 'data-raven-seconds', $settings['seconds_label'] );
		}

		foreach ( [ 'days', 'hours', 'minutes', 'seconds' ] as $box ) {
			if ( 'yes' !== $settings[ $box ] ) {
				$this->add_render_attribute( 'wrapper', 'class', 'raven-countdown-hide-' . $box );
			}
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>></div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
		view.addRenderAttribute( 'wrapper', 'class', 'raven-countdown raven-flex' );
		view.addRenderAttribute( 'wrapper', 'class', 'raven-countdown-' + settings.view );
		view.addRenderAttribute( 'wrapper', 'data-raven-countdown', settings.due_date );

		if ( 'yes' === settings.custom_label ) {
			view.addRenderAttribute( 'wrapper', 'data-raven-days', settings.days_label );
			view.addRenderAttribute( 'wrapper', 'data-raven-hours', settings.hours_label );
			view.addRenderAttribute( 'wrapper', 'data-raven-minutes', settings.minutes_label );
			view.addRenderAttribute( 'wrapper', 'data-raven-seconds', settings.seconds_label );
		}

		[ 'days', 'hours', 'minutes', 'seconds' ].forEach(function(box) {
			if ( 'yes' !== settings[box] ) {
				view.addRenderAttribute( 'wrapper', 'class', 'raven-countdown-hide-' + box );
			}
		})
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}></div>
		<?php
	}

}
