<?php
namespace JupiterX_Core\Raven\Modules\Products\Skins;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Products\Module;

class Classic extends \Elementor\Skin_Base {

	public function get_id() {
		return 'classic';
	}

	public function get_title() {
		return __( 'Classic', 'jupiterx-core' );
	}

	protected function _register_controls_actions() {
		add_action( 'elementor/element/raven-wc-products/section_settings/after_section_start', [ $this, 'settings_start_controls' ] );
		add_action( 'elementor/element/raven-wc-products/section_settings/before_section_end', [ $this, 'settings_end_controls' ] );
		add_action( 'elementor/element/raven-wc-products/section_sort_filter/after_section_end', [ $this, 'sort_filter_completed_controls' ] );
	}

	public function settings_start_controls( \Elementor\Widget_Base $widget ) {
		$this->parent = $widget;

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'grid',
				'options' => [
					'grid' => __( 'Grid', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_all_products',
			[
				'label' => __( 'Show All Products', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'options' => [
					'1' => __( '1', 'jupiterx-core' ),
					'2' => __( '2', 'jupiterx-core' ),
					'3' => __( '3', 'jupiterx-core' ),
					'4' => __( '4', 'jupiterx-core' ),
					'5' => __( '5', 'jupiterx-core' ),
					'6' => __( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'options' => [
					'1' => __( '1', 'jupiterx-core' ),
					'2' => __( '2', 'jupiterx-core' ),
					'3' => __( '3', 'jupiterx-core' ),
					'4' => __( '4', 'jupiterx-core' ),
					'5' => __( '5', 'jupiterx-core' ),
					'6' => __( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					$this->get_control_id( 'show_all_products' ) => '',
				],
			]
		);
	}

	public function settings_end_controls( \Elementor\Widget_Base $widget ) {
		$this->parent = $widget;

		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Pagination', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'frontend_available' => true,
				'condition' => [
					$this->get_control_id( 'show_all_products' ) => '',
				],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => __( 'View Pagination As', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'page_based',
				'options' => [
					'page_based' => __( 'Page Based', 'jupiterx-core' ),
					'load_more' => __( 'Load More', 'jupiterx-core' ),
					'infinite_load' => __( 'Infinite Load', 'jupiterx-core' ),
				],
				'condition' => [
					$this->get_control_id( 'show_pagination' ) => 'yes',
					$this->get_control_id( 'show_all_products' ) => '',
				],
				'frontend_available' => true,
			]
		);
	}

	public function sort_filter_completed_controls( \Elementor\Widget_Base $widget ) {
		$this->parent = $widget;

		$this->start_controls_section(
			'section_pagination',
			[
				'label' => __( 'Pagination', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->get_control_id( 'show_pagination' ) => 'yes',
					$this->get_control_id( 'pagination_type!' ) => [ '', 'infinite_load', 'page_based' ],
				],
			]
		);

		$this->load_more_controls();

		$this->end_controls_section();
	}

	/**
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function load_more_controls() {
		$load_more_condition = [
			$this->get_control_id( 'pagination_type' ) => 'load_more',
		];

		$this->add_control(
			'load_more_text',
			[
				'label' => __( 'Button Label', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'Load More', 'jupiterx-core' ),
				'condition' => $load_more_condition,
			]
		);

		$this->add_responsive_control(
			'load_more_width',
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
						'min' => 1,
						'max' => 1000,
					],
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_height',
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
						'min' => 1,
						'max' => 1000,
					],
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
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
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_load_more' );

		$this->start_controls_tab(
			'tabs_load_more_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
				'condition' => $load_more_condition,
			]
		);

		$this->add_control(
			'load_more_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'load_more_typography',
				'scheme' => '3',
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button',
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'load_more_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => __( 'Background Color', 'jupiterx-core' ),
					],
				],
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button',
			]
		);

		$this->add_control(
			'load_more_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => $load_more_condition,
			]
		);

		$this->add_control(
			'load_more_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'load_more_border_border!' ) => '',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'load_more_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button',
			]
		);

		$this->add_control(
			'load_more_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'load_more_box_shadow',
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_load_more_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
				'condition' => $load_more_condition,
			]
		);

		$this->add_control(
			'hover_load_more_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'hover_load_more_typography',
				'scheme' => '3',
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button:hover',
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'hover_load_more_background',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Background Color Type', 'jupiterx-core' ),
					],
					'color' => [
						'label' => __( 'Background Color', 'jupiterx-core' ),
					],
				],
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button:hover',
			]
		);

		$this->add_control(
			'hover_load_more_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => $load_more_condition,
			]
		);

		$this->add_control(
			'hover_load_more_border_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'hover_load_more_border_border!' ) => '',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_load_more_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button:hover',
			]
		);

		$this->add_control(
			'hover_load_more_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'condition' => $load_more_condition,
				'selectors' => [
					'{{WRAPPER}} .raven-load-more-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_load_more_box_shadow',
				'condition' => $load_more_condition,
				'selector' => '{{WRAPPER}} .raven-load-more-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	public function render() {
		$this->parent->query_posts();

		$query = $this->parent->query;

		if ( ! $query->have_posts() ) {
			return;
		}

		$paginated = ! $query->get( 'no_found_rows' );

		$wc_loop = [
			'is_paginated' => $paginated,
			'total' => $paginated ? (int) $query->found_posts : count( $query->posts ),
			'total_pages' => $paginated ? (int) $query->max_num_pages : 1,
			'per_page' => (int) $query->get( 'posts_per_page' ),
			'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
		];

		wc_setup_loop( $wc_loop );

		$settings = array_merge( $wc_loop, [
			'pages_visible' => 7,
		] );

		?>
		<div class="raven-wc-products-wrapper" data-settings="<?php echo esc_attr( wp_json_encode( $settings ) ); ?>">
			<div class="woocommerce columns-<?php echo esc_attr( $this->get_instance_value( 'columns' ) ); ?>">
				<?php
				$this->render_products();
				$this->render_pagination();
				?>
			</div>
		</div>
		<?php
	}

	public function render_products( $echo = true ) {
		$data = [];

		$query = $this->parent->query;

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = (int) $this->get_instance_value( 'columns' );

		add_filter( 'post_class', [ $this, 'add_product_post_class' ] );

		add_filter( 'woocommerce_product_is_visible', [ $this, 'set_product_as_visible' ] );

		if ( $echo ) :

			woocommerce_product_loop_start();

		endif;

		while ( $query->have_posts() ) :

			$query->the_post();

			if ( $echo ) :

				wc_get_template_part( 'content', 'product' );

			elseif ( ! $echo ) :

				ob_start();

				wc_get_template_part( 'content', 'product' );

				$data['products'][] = ob_get_clean();

			endif;

		endwhile;

		if ( $echo ) :

			woocommerce_product_loop_end();

		endif;

		woocommerce_reset_loop();

		remove_filter( 'post_class', [ $this, 'add_product_post_class' ] );

		remove_filter( 'woocommerce_product_is_visible', [ $this, 'set_product_as_visible' ] );

		wp_reset_postdata();

		return $data;
	}

	protected function render_pagination() {
		if ( 'yes' === $this->get_instance_value( 'show_all_products' ) ) {
			return;
		}

		if ( 'yes' !== $this->get_instance_value( 'show_pagination' ) ) {
			return;
		}

		$pagination_type = $this->get_instance_value( 'pagination_type' );

		switch ( $pagination_type ) {
			case 'load_more':
				$this->render_load_more();
				break;

			case 'page_based':
				echo '<nav class="woocommerce-pagination"></nav>';
				break;
		}
	}

	protected function render_load_more() {
		?>
		<div class="raven-load-more">
			<a class="raven-load-more-button" href="#">
				<span class="raven-post-button-text"><?php echo $this->get_instance_value( 'load_more_text' ); ?></span>
			</a>
		</div>
		<?php
	}

	public function add_product_post_class( $classes ) {
		$classes[] = 'product';

		return $classes;
	}

	public function set_product_as_visible() {
		return true;
	}
}
