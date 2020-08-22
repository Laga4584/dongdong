<?php
namespace Raven\Modules\Shopping_Cart\Widgets;

use Raven\Base\Base_Widget;
use Raven\Utils;

defined( 'ABSPATH' ) || die();

class Shopping_Cart extends Base_Widget {

	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public function get_name() {
		return 'raven-shopping-cart';
	}

	public function get_title() {
		return __( 'Shopping Cart', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-shopping-cart';
	}

	protected function _register_controls() {
		$this->register_content_controls();
		$this->register_settings_controls();
		$this->register_icon_controls();
		$this->register_number_controls();
		$this->register_cart_quick_view_controls();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'raven' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Choose Icon', 'raven' ),
				'type' => 'icon',
				'default' => 'jupiterx-icon-shopping-cart-6', // Use Jupiter X icons as default.
			]
		);

		$this->end_controls_section();
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'raven' ),
			]
		);

		$this->add_control(
			'show_cart_quick_view',
			[
				'label' => __( 'Cart Quick View', 'raven' ),
				'type' => 'switcher',
				'label_off' => __( 'No', 'raven' ),
				'label_on' => __( 'Yes', 'raven' ),
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function register_icon_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Icon', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'default' => Utils::get_direction( 'left' ),
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-wrap' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->start_controls_tabs( 'icon_tabs' );

		$this->start_controls_tab(
			'icon_tab_normal',
			[
				'label' => __( 'Normal', 'raven' ),
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon' => 'background-color: {{VALUE}};',
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

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon' => 'border-color: {{VALUE}};',
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
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-shopping-cart-icon',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-shopping-cart-icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_tab_hover',
			[
				'label' => __( 'Hover', 'raven' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hover_border_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon:hover' => 'border-color: {{VALUE}};',
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
						'label' => __( 'Border Width', 'raven' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-shopping-cart-icon:hover',
			]
		);

		$this->add_control(
			'hover_border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-icon:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-shopping-cart-icon:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_number_controls() {
		$this->start_controls_section(
			'section_number',
			[
				'label' => __( 'Number', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'number_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .raven-shopping-cart-count',
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'number_background_color',
			[
				'label' => __( 'Background Color', 'raven' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-count' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'number_width',
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
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-count' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'number_height',
			[
				'label' => __( 'Height', 'raven' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-count' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'number_spacing',
			[
				'label' => __( 'Spacing', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'number_padding',
			[
				'label' => __( 'Padding', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'number_border_heading',
			[
				'label' => __( 'Border', 'raven' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'number_border_radius',
			[
				'label' => __( 'Border Radius', 'raven' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-shopping-cart-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_cart_quick_view_controls() {
		$this->start_controls_section(
			'section_cart_quick_view',
			[
				'label' => __( 'Cart Quick View', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'cart_quick_view_animation',
			[
				'label' => __( 'Animation', 'raven' ),
				'type' => 'select',
				'default' => 'push',
				'options' => [
					'push' => __( 'Push', 'raven' ),
				],
			]
		);

		$this->add_control(
			'cart_quick_view_position',
			[
				'label' => __( 'Alignment', 'raven' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'raven' ),
						'icon' => 'fa fa-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'raven' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'right',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$editor     = \Elementor\Plugin::$instance->editor;
		$settings   = $this->get_settings_for_display();
		$cart_url   = ( 'yes' === $settings['show_cart_quick_view'] ) ? '#' : esc_url( wc_get_cart_url() );
		$cart_count = ! $editor->is_edit_mode() ? WC()->cart->cart_contents_count : 10;

		?>
		<div class="raven-shopping-cart-wrap">
			<a class="raven-shopping-cart" href="<?php echo $cart_url; ?>">
				<span class="raven-shopping-cart-icon <?php echo $settings['icon']; ?>"></span>
				<span class="raven-shopping-cart-count"><?php echo $cart_count; ?></span>
			</a>
			<?php $this->render_quick_cart_view(); ?>
		</div>
		<?php
	}

	protected function render_quick_cart_view() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' !== $settings['show_cart_quick_view'] || is_cart() || is_checkout() ) {
			return;
		}

		jupiterx_open_markup_e(
			'jupiterx_cart_quick_view',
			'div',
			[
				'class' => 'jupiterx-cart-quick-view',
				'data-position' => $settings['cart_quick_view_position'],
			]
		);

			jupiterx_open_markup_e( 'jupiterx_mini_cart_header', 'div', 'class=jupiterx-mini-cart-header' );

				jupiterx_open_markup_e( 'jupiterx_mini_cart_title', 'p', 'class=jupiterx-mini-cart-title' );

					jupiterx_output_e( 'jupiterx_mini_cart_title_text', __( 'Shopping cart', 'raven' ) );

				jupiterx_close_markup_e( 'jupiterx_mini_cart_title', 'p' );

				jupiterx_open_markup_e(
					'jupiterx_mini_cart_close',
					'button',
					[
						'class' => 'btn jupiterx-mini-cart-close jupiterx-icon-long-arrow',
						'role' => 'button',
					]
				);

				jupiterx_close_markup_e( 'jupiterx_mini_cart_close', 'button' );

			jupiterx_close_markup_e( 'jupiterx_mini_cart_header', 'div' );

			the_widget( 'WC_Widget_Cart', [ 'title' => '' ] );

		jupiterx_close_markup_e( 'jupiterx_cart_quick_view', 'div' );
	}
}
