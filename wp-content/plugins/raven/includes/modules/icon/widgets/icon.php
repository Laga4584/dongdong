<?php
namespace Raven\Modules\Icon\Widgets;

use Raven\Base\Base_Widget;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

class Icon extends Base_Widget {

	public function get_name() {
		return 'raven-icon';
	}

	public function get_title() {
		return __( 'Icon', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-icon';
	}

	protected function _register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_icon();
		$this->register_section_container();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'raven' ),
			]
		);

		$this->add_control(
			'icon_new',
			[
				'label' => __( 'Icon', 'raven' ),
				'type' => 'icons',
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'raven' ),
				'type' => 'url',
				'placeholder' => __( 'https://your-link.com', 'raven' ),
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
				'label' => __( 'Settings', 'raven' ),
			]
		);

		$this->add_control(
			'container',
			[
				'label' => __( 'Container', 'raven' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'raven' ),
				'label_off' => __( 'Hide', 'raven' ),
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => __( 'Shape', 'raven' ),
				'type' => 'select',
				'options' => [
					'circle' => __( 'Circle', 'raven' ),
					'square' => __( 'Square', 'raven' ),
				],
				'default' => 'circle',
				'condition' => [
					'container' => 'yes',
				],
				'prefix_class' => 'raven-shape-',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'raven' ),
				'type' => 'raven_hover_effect',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_icon() {
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'icon_tabs' );

		$this->start_controls_tab(
			'icon_tab_normal',
			[
				'label' => __( 'Normal', 'raven' ),
			]
		);

		$this->add_group_control(
			'raven-text-background',
			[
				'name' => 'icon_tab_background_normal',
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-icon i::before, {{WRAPPER}} .raven-icon svg',
			]
		);

		$this->add_responsive_control(
			'icon_tab_size_normal',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_tab_rotate_normal',
			[
				'label' => __( 'Rotate', 'raven' ),
				'type' => 'slider',
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon > i, {{WRAPPER}} .raven-icon > svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_tab_hover',
			[
				'label' => __( 'Hover', 'raven' ),
			]
		);

		$this->add_group_control(
			'raven-text-background',
			[
				'name' => 'icon_tab_background_hover',
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-icon:hover i::before, {{WRAPPER}} .raven-icon:hover svg',
			]
		);

		$this->add_responsive_control(
			'icon_tab_size_hover',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon:hover' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-icon:hover svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_tab_rotate_hover',
			[
				'label' => __( 'Rotate', 'raven' ),
				'type' => 'slider',
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon:hover > i' => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .raven-icon:hover > svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
				'selectors' => [
					'{{WRAPPER}} .raven-icon-wrapper' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'container' => 'yes',
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
				'condition' => [
					'container' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'container_tabs' );

		$this->start_controls_tab(
			'container_tab_normal',
			[
				'label' => __( 'Normal', 'raven' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'container_tab_background_normal',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-icon',
			]
		);

		$this->add_control(
			'container_tab_border_heading_normal',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'container_tab_border_normal',
				'selector' => '{{WRAPPER}} .raven-icon',
			]
		);

		$this->add_responsive_control(
			'container_tab_border_radius_normal',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'shape' => 'square',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'container_tab_box_shadow_normal',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'container_tab_hover',
			[
				'label' => __( 'Hover', 'raven' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'container_tab_background_hover',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-icon:hover',
			]
		);

		$this->add_control(
			'container_tab_border_heading_hover',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'container_tab_border_hover',
				'selector' => '{{WRAPPER}} .raven-icon:hover',
			]
		);

		$this->add_responsive_control(
			'container_tab_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-icon:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'shape' => 'circle',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'container_tab_box_shadow_hover',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-icon:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings          = $this->get_settings_for_display();
		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $settings['__fa4_migrated']['icon_new'] );
		$is_new            = empty( $settings['icon'] ) && $migration_allowed;

		$this->add_render_attribute( 'wrapper', 'class', 'raven-icon-wrapper' );

		$this->add_render_attribute( 'icon-wrapper', 'class', 'raven-icon' );

		if ( ! empty( $settings['hover_effect'] ) ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-animation-' . $settings['hover_effect'] );
		}

		$icon_tag = 'div';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'icon-wrapper', 'href', $settings['link']['url'] );
			$icon_tag = 'a';

			if ( ! empty( $settings['link']['is_external'] ) ) {
				$this->add_render_attribute( 'icon-wrapper', 'target', '_blank' );
			}

			if ( $settings['link']['nofollow'] ) {
				$this->add_render_attribute( 'icon-wrapper', 'rel', 'nofollow' );
			}
		}

		if ( ! empty( $settings['icon'] ) ) {
			$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<<?php echo $icon_tag . ' ' . $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
				<?php
				if ( $is_new || $migrated ) :
					Elementor::$instance->icons_manager->render_icon( $settings['icon_new'], [ 'aria-hidden' => 'true' ] );
				else :
					?>
					<i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
				<?php endif; ?>
			</<?php echo $icon_tag; ?>>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
		var link = settings.link.url ? 'href="' + settings.link.url + '"' : '',
			iconTag = link ? 'a' : 'div',
			iconHTML = elementor.helpers.renderIcon( view, settings.icon_new, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = elementor.helpers.isIconMigrated( settings, 'icon_new' );
		#>
		<div class="raven-icon-wrapper">
			<{{{ iconTag }}} class="raven-icon elementor-animation-{{ settings.hover_effect }}" {{{ link }}}>
				<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
					{{{ iconHTML.value }}}
				<# } else { #>
					<i class="{{ settings.icon }}" aria-hidden="true"></i>
				<# } #>
			</{{{ iconTag }}}>
		</div>
		<?php
	}
}
