<?php
/**
 * Query manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Query_Manager' ) ) {

	/**
	 * Define Jet_Smart_Filters_Query_Manager class
	 */
	class Jet_Smart_Filters_Query_Manager {

		public  $_query            = array();
		private $_default_query    = array();
		private $_query_settings   = array();
		private $_props            = array();

		private $provider          = null;
		private $is_ajax_filter    = null;
		private $queried_hierarchy = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_filter( 'the_posts', array( $this, 'query_props_handler' ), 999, 2 );
			add_filter( 'posts_pre_query', array( $this, 'set_found_rows' ), 10, 2 );

		}

		/**
		 * Set no_found_rows to false
		 */
		public function set_found_rows( $posts, $query ) {

			if ( $query->get( 'jet_smart_filters' ) ) {
				$query->set( 'no_found_rows', false );
			}

			return $posts;

		}

		/**
		 * Store default query for passed provider
		 *
		 * @return [type] [description]
		 */
		public function store_provider_default_query( $provider_id, $query_args, $query_id = false ) {

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			if ( empty( $this->_default_query[ $provider_id ] ) ) {
				$this->_default_query[ $provider_id ] = array();
			}

			if ( isset( $this->_default_query[ $provider_id ][ $query_id ] ) ) {
				return;
			}

			if ( isset( $_REQUEST['jet-smart-filters-redirect'] ) ) {
				unset( $query_args['meta_query'] );
				unset( $query_args['tax_query'] );
			}

			$this->_default_query[ $provider_id ][ $query_id ] = $query_args;

		}

		/**
		 * Return default queries array
		 *
		 * @return [type] [description]
		 */
		public function get_default_queries() {
			return $this->_default_query;
		}

		/**
		 * Returns query settings
		 *
		 * @return array
		 */
		public function get_query_settings() {
			return $this->_query_settings;
		}

		/**
		 * Query vars
		 */
		public function query_vars() {
			return apply_filters( 'jet-smart-filters/query/vars', array(
				'tax_query',
				'meta_query',
				'date_query',
				'_s',
				'sort'
			) );
		}

		/**
		 * Return parsed query arguments
		 *
		 * @return void
		 */
		public function get_query_args() {
			if ( $this->is_ajax_filter() && ! empty( $this->_default_query ) ) {
				return array_merge( $this->_default_query, $this->_query );
			} else {
				return $this->_query;
			}
		}

		/**
		 * Check if is ajax filter processed
		 *
		 * @return boolean [description]
		 */
		public function is_ajax_filter() {

			if ( null !== $this->is_ajax_filter ) {
				return $this->is_ajax_filter;
			}

			if ( ! wp_doing_ajax() ) {
				$this->is_ajax_filter = false;
				return $this->is_ajax_filter;
			}

			$allowed_actions = apply_filters( 'jet-smart-filters/query/allowed-ajax-actions', array(
				'jet_smart_filters',
				'jet_smart_filters_refresh_controls',
				'jet_smart_filters_refresh_controls_reload',
			) );

			if ( ! isset( $_REQUEST['action'] ) || ! in_array( $_REQUEST['action'], $allowed_actions ) ) {
				$this->is_ajax_filter = false;
				return $this->is_ajax_filter;
			}

			$this->is_ajax_filter = true;
			return $this->is_ajax_filter;

		}

		/**
		 * Store query properties
		 *
		 * @param  [type] $posts [description]
		 * @param  [type] $query [description]
		 * @return [type]        [description]
		 */
		public function query_props_handler( $posts, $query ) {

			if ( $query->get( 'jet_smart_filters' ) ) {
				$this->store_query_props( $query );
			}

			return $posts;

		}

		/**
		 * Store query properites
		 *
		 * @param  WP_Query $query WP_Query object.
		 * @return void
		 */
		public function store_query_props( $query ) {

			$provider_data = $this->decode_provider_data( $query->get( 'jet_smart_filters' ) );
			$provider      = $provider_data['provider'];
			$query_id      = $provider_data['query_id'];

			if ( empty( $this->_props[ $provider ] ) ) {
				$this->_props[ $provider ] = array();
			}
			do_action( 'jet-smart-filters/query/store-query-props/' . $provider, $this, $query_id );
			$this->_props[ $provider ][ $query_id ] = array(
				'found_posts'   => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
				'page'          => $query->get( 'paged' )
			);

		}

		/**
		 * Encode provider data
		 *
		 * @param  [type] $provider [description]
		 * @param  string $query_id [description]
		 * @return [type]           [description]
		 */
		public function encode_provider_data( $provider, $query_id = 'default' ) {

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			return $provider . '/' . $query_id;
		}

		/**
		 * Decode provider data
		 *
		 * @param  [type] $provider [description]
		 * @param  string $query_id [description]
		 * @return [type]           [description]
		 */
		public function decode_provider_data( $provider ) {

			$data   = explode( '/', $provider );
			$result = array();

			if ( empty( $data ) ) {
				$result['provider'] = $provider;
				$result['query_id'] = 'default';
			} elseif ( ! empty( $data[0] ) && empty( $data[1] ) ) {
				$result['provider'] = $data[0];
				$result['query_id'] = 'default';
			} else {
				$result['provider'] = $data[0];
				$result['query_id'] = $data[1];
			}

			return $result;

		}

		/**
		 * Store properties array for provider
		 *
		 * @param [type] $props [description]
		 */
		public function set_props( $provider, $props, $query_id = 'default' ) {

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			if ( empty( $this->_props[ $provider ] ) ) {
				$this->_props[ $provider ] = array();
			}

			$this->_props[ $provider ][ $query_id ] = $props;
		}
		/**
		 * Store properties array for provider
		 *
		 * @param [type] $props [description]
		 */
		public function add_prop( $provider, $prop, $value, $query_id = 'default' ) {
			if ( ! $query_id ) {
				$query_id = 'default';
			}
			if ( empty( $this->_props[ $provider ] ) ) {
				$this->_props[ $provider ] = array();
			}
			$this->_props[ $provider ][ $query_id ][ $prop ] = $value;

		}
		/**
		 * Query properties provider
		 *
		 * @param  string $provider Provider ID.
		 * @return array
		 */
		public function get_query_props( $provider = null, $query_id = 'default' ) {

			if ( ! $query_id ) {
				$query_id = 'default';
			}

			if ( ! $provider ) {
				return $this->_props;
			}

			return isset( $this->_props[ $provider ][ $query_id ] ) ? $this->_props[ $provider ][ $query_id ] : array();

		}

		/**
		 * Force is_ajax_filter to true
		 */
		public function set_is_ajax_filter() {
			$this->is_ajax_filter = true;
		}

		/**
		 * Set current provider info
		 */
		public function set_provider( $provider = '' ) {
			$this->provider = $provider;
		}

		/**
		 * Parse current request
		 *
		 * @return [type] [description]
		 */
		public function parse_request() {

			$data = ! empty( $_REQUEST['jet-smart-filters'] ) ? $_REQUEST['jet-smart-filters'] : false;

			if ( ! $data ) {
				return array();
			}

			$data      = explode( ';', $data );
			$provider  = ! empty( $data[0] ) ? $data[0] : false;
			$hierarchy = ! empty( $data[1] ) ? $data[1] : false;
			$trail     = ! empty( $data[2] ) ? $data[2] : false;

			$this->provider = $provider;

			if ( $hierarchy && $trail ) {

				$parsed_trail = array();
				$trail        = explode( '|', $trail );

				foreach ( $trail as $index => $item ) {

					$item = explode( '=', $item );

					if ( isset( $item[0] ) && isset( $item[1] ) ) {
						$parsed_trail[] = array(
							'depth' => $index,
							'tax'   => $item[0],
							'value' => $item[1],
						);
					}

				}

				$this->queried_hierarchy = array(
					'filter_id' => $hierarchy,
					'trail'     => $parsed_trail
				);
			}

			return array(
				'provider'          => $this->provider,
				'queried_hierarchy' => $this->queried_hierarchy,
			);

		}

		/**
		 * Returns queried hierarchy
		 *
		 * @return [type] [description]
		 */
		public function get_queried_hierarchy() {
			return $this->queried_hierarchy;
		}

		/**
		 * Get current provider ID.
		 *
		 * @return string
		 */
		public function get_current_provider( $return = null ) {

			if ( ! empty( $this->provider ) ) {
				$provider = $this->provider;
			} elseif ( $this->is_ajax_filter() ) {
				$provider = $_REQUEST['provider'];
			} else {

				$request  = $this->parse_request();
				$provider = ! empty( $request['provider'] ) ? $request['provider'] : false;

				if ( ! $provider ) {
					$provider_data = get_query_var( 'jet_smart_filters' );
				}

			}

			if ( ! $provider ) {
				return false;
			}

			if ( 'raw' === $return ) {
				return $provider;
			}

			$data = $this->decode_provider_data( $provider );

			if ( ! $return ) {
				return $data;
			} else {
				return isset( $data[ $return ] ) ? $data[ $return ] : false;
			}

		}

		/**
		 * Return properties for current query
		 *
		 * @return array
		 */
		public function get_current_query_props() {
			$data = $this->get_current_provider();
			return $this->get_query_props( $data['provider'], $data['query_id'] );
		}

		/**
		 * Query
		 */
		public function get_query_from_request( $request = array() ) {

			if ( empty( $request ) ) {
				$request = $_REQUEST;
			}

			$this->_query = array(
				'jet_smart_filters' => $this->get_current_provider( 'raw' ),
				'suppress_filters'  => false,
			);

			if ( $this->is_ajax_filter() ) {
				$this->_default_query  = ! empty( $request['defaults'] ) ? $request['defaults'] : array();
				$this->_query_settings = ! empty( $request['settings'] ) ? $request['settings'] : array();
			}

			foreach ( $this->query_vars() as $var ) {

				if ( $this->is_ajax_filter() ) {
					$data = isset( $request['query'] ) ? $request['query'] : array();
				} else {
					$data = $request;
				}

				if ( ! $data ) {
					$data = array();
				}

				array_walk( $data, function( $value, $key ) use ( $var ) {

					if ( strpos( $key, $var ) ) {

						switch ( $var ) {

							case 'tax_query':

								$this->add_tax_query_var( $value, $this->clear_key( $key, $var ) );

								break;

							case 'date_query':

								$this->add_date_query_var( $value );

								break;

							case 'meta_query':

								$key         = $this->clear_key( $key, $var );
								$with_suffix = explode( '|', $key );
								$suffix      = false;

								if ( isset( $with_suffix[1] ) ) {
									$key    = $with_suffix[0];
									$suffix = $this->process_suffix( $with_suffix[1] );
									$value  = $this->apply_suffix( $suffix, $value );
								}

								$this->add_meta_query_var( $value, $key, $suffix );

								break;

							case '_s':

								if ( false !== strpos( $key, '__s_query' ) ) {
									$this->_query['s'] = $value;
								}

								break;

							case 'sort':

								$sort_props = json_decode( wp_unslash( $value ), true );

								if ( ! empty( $sort_props['orderby'] ) ) {
									switch ( $sort_props['orderby'] ) {
										case 'price':
											$this->_query['orderby']  = 'meta_value_num';
											$this->_query['meta_key'] = '_price';

											break;

										case 'sales_number':
											$this->_query['orderby']  = 'meta_value_num';
											$this->_query['meta_key'] = 'total_sales';

											break;

										case 'rating':
											$this->_query['orderby']  = 'meta_value_num';
											$this->_query['meta_key'] = '_wc_average_rating';

											break;

										case 'reviews_number':
											$this->_query['orderby']  = 'meta_value_num';
											$this->_query['meta_key'] = '_wc_review_count';

											break;

										default:
											$this->_query['orderby'] = $sort_props['orderby'];

											break;
									}
								}

								if ( ! empty( $sort_props['order'] ) ) {
									$this->_query['order'] = $sort_props['order'];
								}

								if ( ! empty( $sort_props['meta_key'] ) ) {
									$this->_query['meta_key'] = $sort_props['meta_key'];
								}

								break;

							default:

								$this->_query[ $var ] = apply_filters(
									'jet-smart-filters/query/add-var',
									$value,
									$key,
									$var,
									$this
								);

								break;
						}
					}

				} );

			}

			if ( isset( $request['paged'] ) && 'false' !== $request['paged'] ) {
				$paged = absint( $request['paged'] );
			} elseif (  isset( $request['jet_paged'] ) ) {

				$paged = absint( $request['jet_paged'] );
			} else {
				$paged = false;
			}

			if ( $paged ) {
				$this->_query['paged'] = $paged;
			}

			$this->_query = apply_filters( 'jet-smart-filters/query/final-query', $this->_query );

		}

		/**
		 * Clear key from varaible prefix
		 *
		 * @param  [type] $key       [description]
		 * @param  [type] $query_var [description]
		 * @return [type]            [description]
		 */
		public function clear_key( $key, $query_var ) {
			return str_replace( '_' . $query_var . '_', '', $key );
		}

		/**
		 * Return raw key
		 *
		 * @param  [type] $key       [description]
		 * @param  [type] $query_var [description]
		 * @return [type]            [description]
		 */
		public function raw_key( $key, $query_var ) {

			$key        = str_replace( '_' . $query_var . '_', '', $key );
			$has_filter = explode( '|', $key );

			if ( isset( $has_filter[1] ) ) {
				return $has_filter[0];
			} else {
				return $key;
			}

		}

		/**
		 * Get taxonomy operator from value
		 *
		 * @param  Array  &$data
		 * @return String $operator
		 */
		public function get_operator( &$data ) {

			$operator = false;

			if ( ! is_array( $data ) ) {
				return $operator;
			}

			foreach ( $data as $key => $value ) {
				if ( false !== strpos( $value, 'operator_' ) ) {
					$operator = str_replace( 'operator_', '', $value );
					unset( $data[ $key ] );
				}
			}

			return $operator;

		}

		/**
		 * Add tax query varibales
		 */
		public function add_tax_query_var( $value, $key ) {

			$operator = $this->get_operator( $value );
			$tax_query = isset( $this->_query['tax_query'] ) ? $this->_query['tax_query'] : array();

			if ( ! isset( $tax_query[ $key ] ) ) {
				$tax_query[ $key ] = array(
					'taxonomy' => $key,
					'field'    => 'term_id',
					'terms'    => $value,
				);
			} else {

				if ( ! is_array( $value ) ) {
					$value = array( $value );
				}

				if ( ! is_array( $tax_query[ $key ]['terms'] ) ) {
					$tax_query[ $key ]['terms'] = array( $tax_query[ $key ]['terms'] );
				}

				$tax_query[ $key ]['terms'] = array_merge( $tax_query[ $key ]['terms'], $value );

			}

			if ( $operator ) {
				$tax_query[ $key ]['operator'] = $operator;
			}

			if ( ! empty( $this->_default_query['tax_query'] ) ) {
				$this->_query['tax_query'] = array_merge( $this->_default_query['tax_query'], $tax_query );
			} else {
				$this->_query['tax_query'] = $tax_query;
			}

		}

		/**
		 * Add date query varibales
		 */
		public function add_date_query_var( $value ) {

			$date_query = isset( $this->_query['date_query'] ) ? $this->_query['date_query'] : array();
			$value      = explode( ':', $value );

			if ( 2 !== count( $value ) ) {
				return;
			}

			$after  = $value[0];
			$before = $value[1];
			$after  = explode( '/', $after );
			$before = explode( '/', $before );

			$after_query = array(
				'year'  => isset( $after[0] ) ? $after[0] : false,
				'month' => isset( $after[1] ) ? $after[1] : false,
				'day'   => isset( $after[2] ) ? $after[2] : false,
			);

			$before_query = array(
				'year'  => isset( $before[0] ) ? $before[0] : false,
				'month' => isset( $before[1] ) ? $before[1] : false,
				'day'   => isset( $before[2] ) ? $before[2] : false,
			);

			$after_query   = array_filter( $after_query );
			$before_query  = array_filter( $before_query );
			$current_query = array();

			if ( ! empty( $after_query ) ) {
				$current_query['after'] = $after_query;
			}

			if ( ! empty( $before_query ) ) {
				$current_query['before'] = $before_query;
			}

			if ( ! empty( $current_query ) ) {
				$date_query[] = $current_query;
			}

			if ( !empty( $this->_default_query['date_query'] ) ) {
				$this->_query['date_query'] = array_merge( $this->_default_query['date_query'], $date_query );
			} else {
				$this->_query['date_query'] = $date_query;
			}

		}

		/**
		 * Process suffix data
		 *
		 * @param string $suffix, data separated by comma
		 * @return array processed data
		 */

		public function process_suffix( $suffix ) {

			$suffix_data = array();

			foreach ( explode( ',', $suffix ) as $item ) {
				if ( in_array( $item, ['search', 'range', 'date-range', 'check-range', 'rating'] ) ) {
					$suffix_data['filter_type'] = $item;
				}

				if ( 'is_custom_checkbox' === $item ) {
					$suffix_data['is_custom_checkbox'] = true;
				}

				if ( strpos( $item, '::' ) ) {
					$exploded_item = explode( '::', $item );
					$suffix_data[$exploded_item[0]] = $exploded_item[1];
				}
			}

			return $suffix_data;

		}

		/**
		 * Apply value suffix
		 *
		 * @param  [type] $suffix [description]
		 * @param  [type] $value  [description]
		 * @return [type]         [description]
		 */
		public function apply_suffix( $suffix, $value ) {

			if ( ! isset( $suffix['filter_type'] ) ) {
				return $value;
			}

			switch ( $suffix['filter_type'] ) {
				case 'range':
					return explode( ':', $value );

				case 'date-range':
					return array_map( 'strtotime', explode( ':', $value ) );

				case 'check-range':
					$result = array();

					if ( is_array( $value ) ) {
						foreach ( $value as $row ) {
							$result[] = explode( ':', $row );
						}
					} else {
						$result[] = explode( ':', $value );
					}

					return $result;

				default:
					return apply_filters( 'jet-smart-filters/apply-suffix/' . $suffix['filter_type'], $value, $this );
			}

		}

		/**
		 * Add tax query varibales
		 */
		public function add_meta_query_var( $value, $key, $additional_options = array() ) {

			$meta_query         = isset( $this->_query['meta_query'] ) ? $this->_query['meta_query'] : array();
			$filter_type        = isset( $additional_options['filter_type'] ) ? $additional_options['filter_type'] : false;
			$is_custom_checkbox = isset( $additional_options['is_custom_checkbox'] ) ? true : false;

			if ( 'check-range' === $filter_type || ( $is_custom_checkbox && is_array( $value ) ) ) {
				$nested_query = array(
					'relation' => 'OR',
				);

				foreach ( $value as $value_row ) {
					$nested_query[] = $this->prepare_meta_query_row( $value_row, $key, $additional_options );
				}

				$meta_query[] = $nested_query;
			} else {
				$meta_query[] = $this->prepare_meta_query_row( $value, $key, $additional_options );
			}

			if ( !empty( $this->_default_query['meta_query'] ) ) {
				$this->_query['meta_query'] = array_merge( $this->_default_query['meta_query'], $meta_query );
			} else {
				$this->_query['meta_query'] = $meta_query;
			}

		}

		/**
		 * Preapre single meta query item
		 *
		 * @param  mixed  $value
		 * @param  string $key
		 * @param  array  $additional_options
		 * @return array
		 */
		public function prepare_meta_query_row( $value, $key, $additional_options = array() ) {

			$filter_type        = isset( $additional_options['filter_type'] ) ? $additional_options['filter_type'] : false;
			$is_custom_checkbox = isset( $additional_options['is_custom_checkbox'] ) ? true : false;
			$compare_operand    = isset( $additional_options['compare'] ) ? $additional_options['compare'] : 'equal';
			$custom_type        = false;

			if ( is_array( $value ) ) {
				$compare = 'IN';
			} else {
				switch ( $compare_operand ){
					case 'less' :
						$compare     = '<=';
						$custom_type = 'NUMERIC';
						break;

					case 'greater' :
						$compare     = '>=';
						$custom_type = 'NUMERIC';
						break;

					case 'like' :
						$compare = 'LIKE';
						break;

					case 'in' :
						$compare = 'IN';
						break;

					case 'between' :
						$compare = 'BETWEEN';
						break;

					case 'exists' :
						$compare = 'EXISTS';
						break;

					case 'regexp' :
						$compare = 'REGEXP';
						break;

					default:
						$compare = '=';
						break;
				}
			}

			$current_row = array(
				'key'     => $key,
				'value'   => $value,
				'compare' => $compare,
			);

			if ( $filter_type ) {
				switch ($filter_type) {
					case 'search':
						$current_row['compare'] = 'LIKE';
						$current_row['type']    = 'CHAR';

						break;

					case 'range':
					case 'check-range':
						$current_row['compare'] = 'BETWEEN';
						$current_row['type']    = 'DECIMAL(16,4)';

						break;

					case 'date-range':
						$current_row['compare'] = 'BETWEEN';
						$current_row['type']    = 'NUMERIC';

						break;

					case 'rating':
						$current_row['type'] = 'DECIMAL(16,4)';

						break;
				}
			}

			if ( $is_custom_checkbox ) {
				$current_row['value']   = $value . '["]?;s:4:"true"|' . $value . '[\'\"]?;[^s]';
				$current_row['compare'] = 'REGEXP';
			}

			if ( $custom_type ) {
				$current_row['type'] = $custom_type;
			}

			return apply_filters( 'jet-smart-filters/query/meta-query-row', $current_row, $this, $additional_options );

		}

	}

}
