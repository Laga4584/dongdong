<?php
/**
 * Widgets manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Widgets_Manager' ) ) {

	/**
	 * Define Jet_Smart_Filters_Widgets_Manager class
	 */
	class Jet_Smart_Filters_Widgets_Manager {

		private $_category = 'jet-smart-filters';

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_action( 'elementor/init', array( $this, 'register_category' ) );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ), 10 );
		}

		public function prepare_help_url( $url, $name ){
			if ( ! empty( $url ) ) {
				return add_query_arg(
					array(
						'utm_source'   => 'need-help',
						'utm_medium'   => $name,
						'utm_campaign' => 'jetsmartfilters',
					),
					esc_url( $url )
				);
			}
			return false;
		}

		/**
		 * Returns filters widgets category
		 */
		public function get_category() {
			return $this->_category;
		}

		/**
		 * Register cherry category for elementor if not exists
		 *
		 * @return void
		 */
		public function register_category() {

			$elements_manager = Elementor\Plugin::instance()->elements_manager;

			$elements_manager->add_category(
				$this->get_category(),
				array(
					'title' => esc_html__( 'Filters', 'jet-smart-filters' ),
					'icon'  => 'font',
				),
				0
			);
		}

		/**
		 * Register listing widgets
		 *
		 * @return void
		 */
		public function register_widgets( $widgets_manager ) {

			$filter_types = jet_smart_filters()->filter_types->get_filter_types();

			require jet_smart_filters()->plugin_path( 'includes/widgets/base.php' );

			foreach ( $filter_types as $filter ) {
				if ( method_exists( $filter, 'widget' ) && ! empty( $filter->widget() ) && file_exists( $filter->widget() ) ) {
					$this->register_widget( $filter->widget(), $widgets_manager );
				}
			}

			$additional_widgets = array(
				jet_smart_filters()->plugin_path( 'includes/widgets/sorting.php' ),
				jet_smart_filters()->plugin_path( 'includes/widgets/active-filters.php' ),
				jet_smart_filters()->plugin_path( 'includes/widgets/active-tags.php' ),
				jet_smart_filters()->plugin_path( 'includes/widgets/apply-button.php' ),
				jet_smart_filters()->plugin_path( 'includes/widgets/remove-filters.php' ),
				jet_smart_filters()->plugin_path( 'includes/widgets/pagination.php' ),
			);

			foreach ( $additional_widgets as $widget ) {
				$this->register_widget( $widget, $widgets_manager );
			}

		}

		/**
		 * Register new widget
		 *
		 * @return void
		 */
		public function register_widget( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\Jet_Smart_Filters_%s_Widget', $class );

			require $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register_widget_type( new $class );
			}

		}

	}

}
