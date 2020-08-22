<?php
namespace Raven\Modules\Column;

defined( 'ABSPATH' ) || die();

use Raven\Base\Module_base;

class Module extends Module_Base {

	public function __construct() {
		add_action( 'elementor/element/column/layout/before_section_end', [ $this, 'extend_settings' ], 10 );
		add_action( 'elementor/frontend/column/before_render', [ $this, 'before_render' ] );
	}

	public function extend_settings( $element ) {
		$element->add_control(
			'raven_link',
			[
				'label' => __( 'Link', 'raven' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
					'categories' => [
						'url',
					],
				],
				'placeholder' => 'https://your-link.com',
				'render_type' => 'ui',
			]
		);

		$element->update_control(
			'content_position',
			[
				'selectors' => [
					'{{WRAPPER}}.elementor-column .elementor-column-wrap' => 'align-items: {{VALUE}}',
					'{{WRAPPER}}.elementor-column .elementor-column-wrap .elementor-widget-wrap' => 'align-items: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'raven_display',
			[
				'label' => __( 'Display', 'raven' ),
				'description' => __( 'This Raven option is deprecated and will be removed in v2.0. Set this to <strong>Block</strong> and use Elementor <strong>Custom Positioning</strong> feature.', 'raven' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Block', 'raven' ),
					'flex' => __( 'Flex', 'raven' ),
				],
			]
		);

		$element->add_control(
			'raven_flex_orientation',
			[
				'label' => __( 'Content Orientation', 'raven' ),
				'type' => 'choose',
				'default' => 'horizontal',
				'options' => [
					'horizontal' => [
						'title' => __( 'Horizontal', 'raven' ),
						'icon' => 'eicon-ellipsis-h',
					],
					'vertical' => [
						'title' => __( 'Vertical', 'raven' ),
						'icon' => 'eicon-editor-list-ul',
					],
				],
				'label_block' => false,
				'prefix_class' => 'raven-column-flex-',
				'condition' => [ 'raven_display' => 'flex' ],
			]
		);

		$element->add_control(
			'raven_flex_align',
			[
				'label' => __( 'Content Align', 'raven' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'raven' ),
					'start' => __( 'Left', 'raven' ),
					'center' => __( 'Middle', 'raven' ),
					'end' => __( 'Right', 'raven' ),
					'space-between' => __( 'Space Between', 'raven' ),
					'space-evenly' => __( 'Space Evenly', 'raven' ),
					'space-around' => __( 'Space Around', 'raven' ),
				],
				'prefix_class' => 'raven-column-flex-',
				'condition' => [
					'raven_display' => 'flex',
					'raven_flex_orientation' => 'horizontal',
				],
			]
		);

		$element->add_control(
			'raven_flex_vertical_align',
			[
				'label' => __( 'Content Align', 'raven' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'raven' ),
					'start' => __( 'Top', 'raven' ),
					'center' => __( 'Middle', 'raven' ),
					'end' => __( 'Bottom', 'raven' ),
					'space-between' => __( 'Space Between', 'raven' ),
					'space-evenly' => __( 'Space Evenly', 'raven' ),
					'space-around' => __( 'Space Around', 'raven' ),
				],
				'prefix_class' => 'raven-column-flex-',
				'condition' => [
					'raven_display' => 'flex',
					'raven_flex_orientation' => 'vertical',
				],
			]
		);
	}

	public function before_render( \Elementor\Element_Base $element ) {
		$link = $element->get_settings_for_display( 'raven_link' );

		if ( empty( $link['url'] ) ) {
			return;
		}

		$element->add_render_attribute( '_wrapper', [
			'class' => 'raven-column-link',
			'data-raven-link' => $link['url'],
			'data-raven-link-target' => empty( $link['is_external'] ) ? '_self' : '_blank',
		] );
	}

}
