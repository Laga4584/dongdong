<?php
/**
 * Class: Jet_Smart_Filters_Provider_WooCommerce_Archive
 * Name: WooCommerce Archive (Jet Woo Builder)
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Provider_WooCommerce_Archive' ) ) {

	/**
	 * Define Jet_Smart_Filters_Provider_WooCommerce_Archive class
	 */
	class Jet_Smart_Filters_Provider_WooCommerce_Archive extends Jet_Smart_Filters_Provider_Base {

		/**
		 * Watch for default query
		 */
		public function __construct() {

			if ( ! jet_smart_filters()->query->is_ajax_filter() ) {
				add_filter( 'posts_pre_query', array( $this, 'store_archive_query' ), 0, 2 );
				add_filter( 'woocommerce_shop_loop', array( $this, 'set_loop_props' ) );

				add_action( 'elementor/widget/before_render_content', array( $this, 'store_default_settings' ), 0 );
			}

		}

		/**
		 * Save default widget settings
		 *
		 * @param  $widget
		 * @return void
		 */
		public function store_default_settings( $widget ) {

			if ( $this->widget_name() !== $widget->get_name() ) {
				return;
			}

			$settings         = $widget->get_settings();
			$store_settings   = $this->settings_to_store();
			$default_settings = array();

			if ( ! empty( $settings['_element_id'] ) ) {
				$query_id = $settings['_element_id'];
			} else {
				$query_id = 'default';
			}

			foreach ( $store_settings as $key ) {
				if ( $key === 'switcher_enable' ) {
					$default_settings[ $key ] = isset( $settings[ $key ] ) ? filter_var( $settings[$key], FILTER_VALIDATE_BOOLEAN ) : '';
				} else {
					$default_settings[ $key ] = isset( $settings[ $key ] ) ? $settings[ $key ] : '';
				}
			}

			$default_settings['_el_widget_id'] = $widget->get_id();

			jet_smart_filters()->providers->store_provider_settings( $this->get_id(), $default_settings, $query_id );

		}

		/**
		 * Returns Products loop appropriate widget name
		 * @return string
		 */
		public function widget_name() {
			return 'jet-woo-builder-products-loop';
		}

		/**
		 * Returns settings to store list
		 * @return array
		 */
		public function settings_to_store() {

			return array(
				'switcher_enable',
				'main_layout',
				'main_layout_switcher_label',
				'main_layout_switcher_icon',
				'secondary_layout',
				'secondary_layout_switcher_label',
				'secondary_layout_switcher_icon'
			);

		}

		/**
		 * WooCommerce loop properties to store
		 *
		 * @return array
		 */
		public function wc_loop_props() {
			return apply_filters( 'jet-smart-filters/providers/' . $this->get_id() . '/wc-loop-props', array(
				'columns',
				'name',
				'is_shortcode',
				'is_paginated',
				'is_search',
				'is_filtered',
			) );
		}

		/**
		 * Set woocommerce loop properties
		 */
		public function set_loop_props() {

			$props = $this->wc_loop_props();

			foreach ( $props as $prop ) {
				jet_smart_filters()->query->add_prop( $this->get_id(), $prop, wc_get_loop_prop( $prop ) );
			}

		}

		/**
		 * Store default query args
		 *
		 * @param  array  $args       Query arguments.
		 * @param  array  $attributes Shortcode attributes.
		 * @param  string $type       Shortcode type.
		 * @return array
		 */
		public function store_archive_query( $posts, $query ) {

			if ( ! $query->get( 'wc_query' ) ) {
				return $posts;
			}

			$default_query = array(
				'post_type'         => $query->get( 'post_type' ),
				'post_status'       => 'publish',
				'wc_query'          => $query->get( 'wc_query' ),
				'tax_query'         => $query->get( 'tax_query' ),
				'orderby'           => $query->get( 'orderby' ),
				'order'             => $query->get( 'order' ),
				'paged'             => $query->get( 'paged' ),
				'posts_per_page'    => $query->get( 'posts_per_page' ),
				'jet_smart_filters' => $this->get_id(),
			);

			if ( $query->get( 'taxonomy' ) ) {
				$default_query['taxonomy'] = $query->get( 'taxonomy' );
				$default_query['term'] = $query->get( 'term' );
			}

			if ( is_search() ){
				$default_query['s'] = $query->get( 's' );
			}

			jet_smart_filters()->query->store_provider_default_query( $this->get_id(), $default_query );

			$query->set( 'jet_smart_filters', $this->get_id() );

			return $posts;

		}

		/**
		 * Get provider name
		 *
		 * @return string
		 */
		public function get_name() {
			return __( 'WooCommerce Archive (by JetWooBuilder)', 'jet-smart-filters' );
		}

		/**
		 * Get provider ID
		 *
		 * @return string
		 */
		public function get_id() {
			return 'woocommerce-archive';
		}

		/**
		 * Get filtered provider content
		 *
		 * @return string
		 */
		public function ajax_get_content() {

			if ( ! function_exists( 'wc' ) || ! function_exists( 'jet_woo_builder' ) ) {
				return;
			}


			global $wp_query;
			$wp_query = new WP_Query( jet_smart_filters()->query->get_query_args() );

			// ensure boolean values
			$booleans = array(
				'is_shortcode',
				'is_paginated',
				'is_search',
				'is_filtered',
			);

			$query_props = jet_smart_filters()->query->get_current_query_props();

			foreach ( $booleans as $bool_prop ) {
				if ( isset( $query_props[ $bool_prop ] ) ) {
					jet_smart_filters()->query->add_prop(
						$this->get_id(),
						$bool_prop,
						filter_var( $query_props[ $bool_prop ], FILTER_VALIDATE_BOOLEAN )
					);
				}
			}

			if ( ! class_exists( 'Elementor\Jet_Woo_Builder_Base' ) ) {
				require_once jet_woo_builder()->plugin_path(
					'includes/base/class-jet-woo-builder-base.php'
				);
			}

			if ( ! class_exists( 'Elementor\Jet_Woo_Builder_Products_Loop' ) ) {
				require_once jet_woo_builder()->plugin_path(
					'includes/widgets/shop/jet-woo-builder-products-loop.php'
				);
			}

			do_action( 'jet-smart-filters/providers/woocommerce-archive/before-ajax-content' );

			add_action( 'woocommerce_before_shop_loop', array( $this, 'add_loop_data' ), 0 );

			$layout          = ! empty( $_COOKIE['jet_woo_builder_layout'] ) ? absint( $_COOKIE['jet_woo_builder_layout'] ) : false;
			$settings        = jet_smart_filters()->query->get_query_settings();
			$switcher_enable = filter_var( $settings['switcher_enable'], FILTER_VALIDATE_BOOLEAN );
			$default_layout  = ! empty( $settings['main_layout'] ) ? absint( $settings['main_layout'] ) : false;

			if ( function_exists( 'jet_woo_builder_integration_woocommerce' ) && $switcher_enable && $layout ) {
				jet_woo_builder_integration_woocommerce()->current_template_archive = $layout;
			} elseif ( function_exists( 'jet_woo_builder_integration_woocommerce' ) && $switcher_enable && $default_layout ) {
				jet_woo_builder_integration_woocommerce()->current_template_archive = $default_layout;
			}

			Elementor\Jet_Woo_Builder_Products_Loop::products_loop();

			remove_action( 'woocommerce_before_shop_loop', array( $this, 'add_loop_data' ), 0 );

			do_action( 'jet-smart-filters/providers/woocommerce-archive/after-ajax-content' );

		}

		/**
		 * Add loop data from request to rendered WooCommerce loop
		 */
		public function add_loop_data() {

			$props       = $this->wc_loop_props();
			$query_props = jet_smart_filters()->query->get_current_query_props();

			foreach ( $props as $prop ) {
				if ( isset( $query_props[ $prop ] ) ) {
					wc_set_loop_prop( $prop, $query_props[ $prop ] );
				}
			}

		}

		/**
		 * Store query ptoperties
		 *
		 * @return [type] [description]
		 */
		public function store_props() {
			global $woocommerce_loop;

			jet_smart_filters()->query->set_props(
				$this->get_id(),
				array(
					'found_posts'   => $woocommerce_loop['total'],
					'max_num_pages' => $woocommerce_loop['total_pages'],
					'page'          => $woocommerce_loop['current_page'],
				)
			);
		}

		/**
		 * Get provider wrapper selector
		 *
		 * @return string
		 */
		public function get_wrapper_selector() {
			return '.jet-woo-products-wrapper';
		}

		/**
		 * Add custom settings for AJAX request
		 */
		public function add_settings( $settings ) {
			return jet_smart_filters()->query->get_query_settings();
		}

		/**
		 * Pass args from reuest to provider
		 */
		public function apply_filters_in_request() {

			$args = jet_smart_filters()->query->get_query_args();

			if ( ! $args ) {
				return;
			}

			add_filter( 'pre_get_posts', array( $this, 'add_query_args' ), 10 );

		}

		/**
		 * Add custom query arguments
		 *
		 * @param array $args [description]
		 */
		public function add_query_args( $query ) {

			if ( ! $query->get( 'wc_query' ) ) {
				return;
			}

			foreach ( jet_smart_filters()->query->get_query_args() as $query_var => $value ) {
				$query->set( $query_var, $value );
			}

		}
	}

}
