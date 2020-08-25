<?php



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Order_Status_Controller' ) ) :

	class PAFW_Order_Status_Controller {

		protected static $rules = null;

		protected static function get_rules() {
			if ( is_null( self::$rules ) ) {
				self::$rules = array (
					'product'         => get_option( 'pafw-order-status-by-product', array () ),
					'category'        => get_option( 'pafw-order-status-by-category', array () ),
					'attributes'      => get_option( 'pafw-order-status-by-attributes', array () ),
					'auto_transition' => get_option( 'pafw-auto-transition-by-term', array () ),
				);
			}

			return self::$rules;
		}
		protected static function get_matched_rules_by_product_ids( $product_info ) {
			$rules = self::get_rules();

			if ( ! empty( $rules['product'] ) ) {
				foreach ( $rules['product'] as $rule ) {
					if ( 'yes' == $rule['enabled'] ) {
						$product_ids = array_keys( $rule['products'] );

						foreach ( $product_info['parent_id'] as $cart_product_id ) {
							if ( in_array( $cart_product_id, $product_ids ) ) {
								return $rule;
							}
						}
					}
				}
			}

			if ( ! empty( $rules['attributes'] ) ) {
				$terms = array ();
				foreach ( $product_info['variations'] as $variation ) {
					$terms = array_merge( $terms, get_terms( $variation['attribute'], array ( 'slug' => $variation['slug'] ) ) );
				}
				$term_ids = array_flip( wp_list_pluck( $terms, 'term_id' ) );

				foreach ( $rules['attributes'] as $rule ) {
					if ( 'yes' == $rule['enabled'] ) {
						if ( ! empty( array_intersect_key( $term_ids, pafw_get( $rule, 'attributes', array () ) ) ) ) {
							return $rule;
						}
					}
				}
			}

			if ( ! empty( $rules['category'] ) ) {
				foreach ( $rules['category'] as $rule ) {
					if ( 'yes' == $rule['enabled'] ) {
						foreach ( $product_info['parent_id'] as $cart_product_id ) {

							$terms = get_the_terms( $cart_product_id, 'product_cat' );

							if ( ! empty( $terms ) ) {
								$term_ids = array_flip( array_map( function ( $term ) {
									$term_id = apply_filters( 'wpml_object_id', $term->term_id, 'product_cat', true, pafw_get_default_language() );

									return $term_id;
								}, $terms ) );

								if ( ! empty( array_intersect_key( $term_ids, $rule['categories'] ) ) ) {
									return $rule;
								}
							}
						}
					}
				}
			}

			return null;
		}
		static function get_product_info( $order ) {
			$product_ids = array (
				'variations' => array (),
				'parent_id'  => array ()
			);
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product();

				if ( $product ) {
					if ( is_callable( array ( $product, 'get_attributes' ) ) ) {
						foreach ( $product->get_attributes() as $attribute => $slug ) {
							$product_ids['variations'][] = array (
								'attribute' => $attribute,
								'slug'      => $slug
							);
						}
					}

					$product_ids['parent_id'][] = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
				}
			}

			return $product_ids;
		}
		static function check_order_status_for_virtual( $order_status, $order ) {
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product();
				if ( ! $product || ! $product->is_virtual() ) {
					return $order_status;
				}
			}

			return get_option( 'pafw-gw-order_status_after_payment_for_virtual', 'completed' );
		}
		static function get_order_status( $order_status, $order_id, $order ) {
			if ( is_null( $order ) ) {
				$order = wc_get_order( $order_id );
			}

			if ( $order ) {
				$order_status = self::check_order_status_for_virtual( $order_status, $order );
				$product_info = self::get_product_info( $order );
				$rule         = self::get_matched_rules_by_product_ids( $product_info );

				if ( $rule && ! empty( $rule['order_status'] ) ) {
					$order_status = $rule['order_status'];
				}
			}

			return $order_status;
		}
		public static function maybe_register_scheduled_action( $order_id, $old_status, $new_status ) {
			$rules = self::get_rules();

			$action_group = 'pafw_order_status_transition_' . $order_id;

			as_unschedule_all_actions( '', array (), $action_group );

			if ( ! empty( $rules['auto_transition'] ) ) {
				foreach ( $rules['auto_transition'] as $rule ) {
					if ( $new_status == $rule['from_status'] ) {
						as_schedule_single_action(
							gmdate( 'U' ) + DAY_IN_SECONDS * $rule['term'],
							'pafw_order_status_transition',
							array (
								'order_id'    => $order_id,
								'from_status' => $rule['from_status'],
								'to_status'   => $rule['to_status'],
							),
							$action_group
						);
					}
				}
			}
		}
		public static function maybe_change_order_status( $order_id, $from_status, $to_status ) {
			$order = wc_get_order( $order_id );

			if ( $order && $from_status == $order->get_status() ) {
				$order->update_status( $to_status, __( '[PGALL] 주문상태 자동변경 정책 적용', 'pgall-for-woocommerce' ) );
			}
		}
	}

endif;
