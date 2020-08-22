<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace Raven\Modules\Posts\Carousel\Skins;

defined( 'ABSPATH' ) || die();

use Raven\Utils;
use Raven\Modules\Posts\Classes\Skin_Base;
use Raven\Modules\Posts\Module;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
abstract class Base extends Skin_Base {

	protected function _register_controls_actions() {
		add_action( 'elementor/element/raven-posts-carousel/section_settings/after_section_end', [ $this, 'register_settings_controls' ], 10 );
		add_action( 'elementor/element/raven-posts-carousel/section_sort_filter/after_section_end', [ $this, 'register_controls' ], 20 );
	}

	public function register_settings_controls( \Elementor\Widget_Base $widget ) {
		$this->parent = $widget;

		$this->start_injection( [
			'at' => 'after',
			'of' => 'query_posts_per_page',
        ] );

        $this->add_responsive_control(
			'slides_view',
			[
				'label' => __( 'Posts per View', 'raven' ),
				'type' => 'select',
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => __( '1', 'raven' ),
					'2' => __( '2', 'raven' ),
					'3' => __( '3', 'raven' ),
					'4' => __( '4', 'raven' ),
					'5' => __( '5', 'raven' ),
					'6' => __( '6', 'raven' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_scroll',
			[
				'label' => __( 'Slides to Scroll', 'raven' ),
				'type' => 'select',
				'default' => '1',
				'options' => [
					'1' => __( '1', 'raven' ),
					'2' => __( '2', 'raven' ),
					'3' => __( '3', 'raven' ),
					'4' => __( '4', 'raven' ),
					'5' => __( '5', 'raven' ),
					'6' => __( '6', 'raven' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label' => __( 'Arrows', 'raven' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'raven' ),
				'label_off' => __( 'Hide', 'raven' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Pagination', 'raven' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => __( 'Show', 'raven' ),
				'label_off' => __( 'Hide', 'raven' ),
				'frontend_available' => true,
				'condition' => [
					'is_archive_template' => '',
				],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => __( 'View Pagination As', 'raven' ),
				'type' => 'select',
				'default' => 'dots',
				'options' => [
					'dots' => __( 'Dots', 'raven' ),
					'lines' => __( 'Lines', 'raven' ),
				],
				'condition' => [
					$this->get_control_id( 'show_pagination' ) => 'yes',
					'is_archive_template' => '',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => __( 'Transition Duration', 'raven' ),
				'type' => 'number',
				'default' => 500,
				'min' => 100,
				'max' => 10000,
				'step' => 50,
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => $this->get_control_id( 'show_arrows' ),
									'operator' => '===',
									'value' => 'yes',
								],
								[
									'name' => $this->get_control_id( 'show_pagination' ),
									'operator' => '===',
									'value' => 'yes',
								],
								[
									'name' => $this->get_control_id( 'enable_autoplay' ),
									'operator' => '===',
									'value' => 'yes',
								],
							],
						],
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->get_id(),
						],
					],
				],
			]
		);

		$this->add_control(
			'enable_autoplay',
			[
				'label' => __( 'Autoplay', 'raven' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => __( 'Yes', 'raven' ),
				'label_off' => __( 'No', 'raven' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'raven' ),
				'type' => 'number',
				'default' => 2000,
				'min' => 100,
				'max' => 10000,
				'step' => 50,
				'condition' => [
					$this->get_control_id( 'enable_autoplay' ) => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'enable_hover_pause',
			[
				'label' => __( 'Pause on Hover', 'raven' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => __( 'Yes', 'raven' ),
				'label_off' => __( 'No', 'raven' ),
				'condition' => [
					$this->get_control_id( 'enable_autoplay' ) => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'enable_infinite_loop',
			[
				'label' => __( 'Infinite Loop', 'raven' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Yes', 'raven' ),
				'label_off' => __( 'No', 'raven' ),
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => $this->get_control_id( 'show_arrows' ),
									'operator' => '===',
									'value' => 'yes',
								],
								[
									'name' => $this->get_control_id( 'show_pagination' ),
									'operator' => '===',
									'value' => 'yes',
								],
								[
									'name' => $this->get_control_id( 'enable_autoplay' ),
									'operator' => '===',
									'value' => 'yes',
								],
							],
						],
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->get_id(),
						],
					],
				],
			]
		);

		$this->end_injection();
	}

	public function register_controls( \Elementor\Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_arrows_controls();
		$this->register_pagination_controls();
    }

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_arrows_controls() {
		$this->start_controls_section(
			'section_arrows',
			[
				'label' => __( 'Arrows', 'raven' ),
				'tab' => 'style',
				'condition' => [
					$this->get_control_id( 'show_arrows' ) => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrows' );

		$this->start_controls_tab(
			'tabs_arrows_normal',
			[
				'label' => __( 'Normal', 'raven' ),
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:before, {{WRAPPER}} .raven-slick-slider .slick-next:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev, {{WRAPPER}} .raven-slick-slider .slick-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:before, {{WRAPPER}} .raven-slick-slider .slick-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev, {{WRAPPER}} .raven-slick-slider .slick-next' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_spacing',
			[
				'label' => __( 'Spacing', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-slick-slider .slick-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'arrows_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'arrows_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'arrows_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev, {{WRAPPER}} .raven-slick-slider .slick-next' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'arrows_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-slick-slider .slick-prev, {{WRAPPER}} .raven-slick-slider .slick-next',
			]
		);

		$this->add_control(
			'arrows_border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev, {{WRAPPER}} .raven-slick-slider .slick-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'arrows_box_shadow',
				'selector' => '{{WRAPPER}} .raven-slick-slider .slick-prev, {{WRAPPER}} .raven-slick-slider .slick-next',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_arrows_hover',
			[
				'label' => __( 'Hover', 'raven' ),
			]
		);

		$this->add_control(
			'hover_arrows_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:hover:before, {{WRAPPER}} .raven-slick-slider .slick-next:hover:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_arrows_background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:hover, {{WRAPPER}} .raven-slick-slider .slick-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:hover:before, {{WRAPPER}} .raven-slick-slider .slick-next:hover:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:hover, {{WRAPPER}} .raven-slick-slider .slick-next:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_spacing',
			[
				'label' => __( 'Spacing', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:hover' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-slick-slider .slick-next:hover' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_arrows_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_arrows_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'hover_arrows_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:hover, {{WRAPPER}} .raven-slick-slider .slick-next:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_arrows_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-slick-slider .slick-prev:hover, {{WRAPPER}} .raven-slick-slider .slick-next:hover',
			]
		);

		$this->add_control(
			'hover_arrows_border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-slick-slider .slick-prev:hover, {{WRAPPER}} .raven-slick-slider .slick-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_arrows_box_shadow',
				'selector' => '{{WRAPPER}} .raven-slick-slider .slick-prev:hover, {{WRAPPER}} .raven-slick-slider .slick-next:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_pagination_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => __( 'Pagination', 'raven' ),
				'tab' => 'style',
				'condition' => [
					$this->get_control_id( 'show_pagination' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label' => __( 'Position', 'raven' ),
				'type' => 'select',
				'default' => 'outside',
				'options' => [
					'outside' => __( 'Outside', 'raven' ),
					'inside' => __( 'Inside', 'raven' ),
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'lines_width',
			[
				'label' => __( 'Width', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-lines button:before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_space_between',
			[
				'label' => __( 'Space Between', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-pager li' => 'margin: 0 calc( {{SIZE}}{{UNIT}} / 2 );',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label' => __( 'Spacing', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-pager.slick-pager-outside' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-pager.slick-pager-inside' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->register_dots_controls();

		$this->register_lines_controls();

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_dots_controls() {

		$this->start_controls_tabs( 'tabs_dots' );

		$this->update_control(
			'tabs_dots',
			[
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
			]
		);

		$this->start_controls_tab(
			'tabs_dots_normal',
			[
				'label' => __( 'Normal', 'raven' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots button:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots button:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
			]
		);

		$this->add_control(
			'dots_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
					$this->get_control_id( 'dots_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selector' => '{{WRAPPER}} .slick-dots button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_dots_active',
			[
				'label' => __( 'Active', 'raven' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
			]
		);

		$this->add_control(
			'active_dots_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots .slick-active button:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'active_dots_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots .slick-active button:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'active_dots_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
			]
		);

		$this->add_control(
			'active_dots_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
					$this->get_control_id( 'active_dots_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots .slick-active button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'active_dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selector' => '{{WRAPPER}} .slick-dots .slick-active button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_dots_hover',
			[
				'label' => __( 'Hover', 'raven' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
			]
		);

		$this->add_control(
			'hover_dots_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li:not(.slick-active) button:hover:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_dots_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li:not(.slick-active) button:hover:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_dots_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
			]
		);

		$this->add_control(
			'hover_dots_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
					$this->get_control_id( 'hover_dots_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li:not(.slick-active) button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'dots',
				],
				'selector' => '{{WRAPPER}} .slick-dots li:not(.slick-active) button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_lines_controls() {
		$this->start_controls_tabs( 'tabs_lines' );

		$this->update_control(
			'tabs_lines',
			[
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
			]
		);

		$this->start_controls_tab(
			'tabs_lines_normal',
			[
				'label' => __( 'Normal', 'raven' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
			]
		);

		$this->add_control(
			'lines_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-lines button:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'lines_thickness',
			[
				'label' => __( 'Thickness', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-lines button:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_lines_active',
			[
				'label' => __( 'Active', 'raven' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
			]
		);

		$this->add_control(
			'active_lines_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-lines .slick-active button:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'active_lines_thickness',
			[
				'label' => __( 'Thickness', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-lines .slick-active button:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_lines_hover',
			[
				'label' => __( 'Hover', 'raven' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
			]
		);

		$this->add_control(
			'hover_lines_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-lines li:not(.slick-active) button:hover:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_lines_thickness',
			[
				'label' => __( 'Thickness', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-lines li:not(.slick-active) button:hover:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	public function excerpt_length() {
		$excerpt_length = $this->get_instance_value( 'excerpt_length' );

		return intval( $excerpt_length['size'] );
	}

	public function excerpt_more() {
		return '';
	}

	public function render() {
		$wp_query = $this->parent->get_query_posts();

		$this->parent->query = $wp_query;

		if ( $wp_query->have_posts() ) {
			add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 0 );

			add_filter( 'excerpt_more', [ $this, 'excerpt_more' ], 0 );

			$module = Module::get_instance();

			$action_name = 'carousel_' . $this->get_id() . '_post';

			$action = $module->get_actions( $action_name );

			$this->render_wrapper_before();

			while ( $wp_query->have_posts() ) {
				$wp_query->the_post();

				$action->render_post( $this );
			}

			$this->render_wrapper_after();

			remove_filter( 'excerpt_length', [ $this, 'excerpt_length' ], 0 );

			remove_filter( 'excerpt_more', [ $this, 'excerpt_more' ], 0 );
		}

		wp_reset_postdata();
	}

	public function render_wrapper_before() {
		$settings = [
			'rtl' => is_rtl() ? true : false,
		];

		$slides_view = $this->get_instance_value( 'slides_view' );

		?>
		<div class="raven-posts-carousel raven-slick-slider">
			<div class="slick-items-wrapper">
				<div class="slick-items slick-columns-<?php echo esc_attr( $slides_view ); ?>" data-slick='<?php echo esc_attr( wp_json_encode( $settings ) ); ?>'>
		<?php
	}

	public function render_wrapper_after() {
		?>
				</div>
			</div>
		</div>
		<?php
	}
}
