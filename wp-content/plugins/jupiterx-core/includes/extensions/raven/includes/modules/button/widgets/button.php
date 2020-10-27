<?php
namespace JupiterX_Core\Raven\Modules\Button\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

class Button extends Base_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'raven-button';
	}

	public function get_title() {
		return __( 'Button', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-button';
	}

	protected function _register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_container();
		$this->register_section_text();
		$this->register_section_icon();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'text',
			[
				'label' => __( 'Text', 'jupiterx-core' ),
				'type' => 'text',
				'placeholder' => __( 'Enter your text', 'jupiterx-core' ),
				'default' => __( 'Click me', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'icon_new',
			[
				'label' => __( 'Choose Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'icon',
			]
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$this->add_control(
				'show_as_add_to_cart',
				[
					'label' => __( 'Show as Add to cart button', 'jupiterx-core' ),
					'type' => 'switcher',
					'label_on' => __( 'Yes', 'jupiterx-core' ),
					'label_off' => __( 'No', 'jupiterx-core' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);

			$this->add_group_control(
				'raven-posts',
				[
					'name' => 'product',
					'post_type' => 'product',
					'exclude' => [ 'authors', 'taxonomies' ],
					'fields_options' => [
						'product_includes' => [
							'label' => __( 'Product', 'jupiterx-core' ),
							'multiple' => false,
							'render_type' => 'template',
						],
					],
					'condition' => [
						'show_as_add_to_cart' => 'yes',
					],
				]
			);

			$this->add_control(
				'link',
				[
					'label' => __( 'Link', 'jupiterx-core' ),
					'type' => 'url',
					'placeholder' => __( 'Enter your web address', 'jupiterx-core' ),
					'default' => [
						'url' => '#',
					],
					'condition' => [
						'show_as_add_to_cart!' => 'yes',
					],
					'dynamic' => [
						'active' => true,
					],
				]
			);
		} else {
			$this->add_control(
				'link',
				[
					'label' => __( 'Link', 'jupiterx-core' ),
					'type' => 'url',
					'placeholder' => __( 'Enter your web address', 'jupiterx-core' ),
					'default' => [
						'url' => '#',
					],
					'dynamic' => [
						'active' => true,
					],
				]
			);
		}

		$this->end_controls_section();
	}

	private function register_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effects', 'jupiterx-core' ),
				'type' => 'raven_hover_effect',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Temporary suppressed.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	private function register_section_container() {
		$this->start_controls_section(
			'section_container',
			[
				'label' => __( 'Container', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
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
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em', '%' ],
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
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'jupiterx-core' ),
						'icon' => 'fa fa-align-justify',
					],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_content' );

		$this->start_controls_tab(
			'tab_content_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'background',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button',
			]
		);

		$this->add_control(
			'border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'separator' => 'after',
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'hover_background',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} a.raven-button:hover, {{WRAPPER}} .raven-button:hover',
			]
		);

		$this->add_control(
			'hover_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					'hover_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.raven-button:hover, {{WRAPPER}} .raven-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} a.raven-button:hover, {{WRAPPER}} .raven-button:hover',
			]
		);

		$this->add_control(
			'hover_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'separator' => 'after',
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} a.raven-button:hover, {{WRAPPER}} .raven-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_box_shadow',
				'selector' => '{{WRAPPER}} a.raven-button:hover, {{WRAPPER}} .raven-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_section_text() {
		$this->start_controls_section(
			'section_text',
			[
				'label' => __( 'Text', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'tabs_text' );

		$this->start_controls_tab(
			'tab_text_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'text_typography',
				'scheme' => '4',
				'selector' => '{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} a.raven-button, {{WRAPPER}} .raven-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_text_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'text_hover_typography',
				'scheme' => '4',
				'selector' => '{{WRAPPER}} a.raven-button:hover, {{WRAPPER}} .raven-button:hover',
			]
		);

		$this->add_control(
			'text_hover_color',
			[
				'label' => __( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} a.raven-button:hover, {{WRAPPER}} .raven-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_section_icon() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'icon_new[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'condition' => [
					'icon_new[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-button .raven-button-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-button .raven-button-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		/**
		 * @todo Deprecate this option in v2.0.
		 * In this case, deprecation means making this option hidden for users.
		 */
		$this->add_responsive_control(
			'icon_space_between',
			[
				'label' => __( 'Space Between', 'jupiterx-core' ),
				'description' => __( 'Depreciated and will be removed in v2.0. Use below spacing option for more flexibility.', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'condition' => [
					'icon_new[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-button .raven-button-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-button .raven-button-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-button span.raven-button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'condition' => [
					'icon_new[value]!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $settings['__fa4_migrated']['icon_new'] );
		$is_new            = empty( $settings['icon'] ) && $migration_allowed;

		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			$settings['icon_align']         = $this->get_settings( 'icon_align' );
			$settings['icon_space_between'] = $this->get_settings( 'icon_space_between' );
			$settings['icon_size']          = $this->get_settings( 'icon_size' );
		}

		if ( empty( $settings['icon_align'] ) ) {
			$settings['icon_align'] = 'left';
		}

		$this->add_render_attribute( 'button', 'class', 'raven-button' );
		$this->add_render_attribute( 'icon-align', 'class', 'raven-button-align-icon-' . $settings['icon_align'] );
		$this->add_render_attribute( 'icon-align', 'class', 'raven-button-tablet-align-icon-' . $settings['icon_align_tablet'] );
		$this->add_render_attribute( 'icon-align', 'class', 'raven-button-mobile-align-icon-' . $settings['icon_align_mobile'] );
		$this->add_render_attribute( 'icon-align', 'class', 'raven-button-icon' );
		$this->add_render_attribute( 'text', 'class', 'raven-button-text' );

		$this->add_inline_editing_attributes( 'text', 'none' );

		$product_id = isset( $settings['product_product_includes'] ) ? $settings['product_product_includes'] : 0;

		if ( class_exists( 'WooCommerce' ) && 'yes' === $settings['show_as_add_to_cart'] && ! empty( $product_id ) ) {
			$product      = wc_get_product( $product_id );
			$product_type = $product->get_type();
			$product_ajax = $product->supports( 'ajax_add_to_cart' );

			$class = implode( ' ', array_filter( [
				'product_type_' . $product_type,
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product_ajax ? 'ajax_add_to_cart' : '',
			] ) );

			$this->add_render_attribute( 'button',
				[
					'rel' => 'nofollow',
					'href' => $product->add_to_cart_url(),
					'data-quantity' => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product->get_id(),
					'class' => $class,
				]
			);

		} elseif ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'button', 'href', $settings['link']['url'] );
			$this->add_render_attribute( 'button', 'class', 'raven-button-link' );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}

			if ( $settings['link']['nofollow'] ) {
				$this->add_render_attribute( 'button', 'rel', 'nofollow' );
			}
		}

		if ( $settings['hover_effect'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_effect'] );
		}
		?>
		<div class="raven-widget-wrapper">
			<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
				<span class="raven-button-content">
					<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['icon_new']['value'] ) ) : ?>
						<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
						<?php
						if ( $is_new || $migrated ) :
							Elementor::$instance->icons_manager->render_icon( $settings['icon_new'], [ 'aria-hidden' => 'true' ] );
						else :
							?>
							<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
						<?php endif; ?>
						</span>
					<?php endif; ?>
					<span <?php echo $this->get_render_attribute_string( 'text' ); ?>>
						<?php echo $settings['text']; ?>
						<?php if ( isset( $product_ajax ) && $product_ajax ) : ?>
							<i class="raven-spinner"></i>
						<?php endif; ?>
					</span>
				</span>
			</a>
		</div>
		<?php
	}
}
