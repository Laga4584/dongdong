<?php
namespace Jet_Reviews\Endpoints;

use Jet_Reviews\Reviews\Data as Reviews_Data;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Posts class
 */
class Update_Review_Approval extends Base {

	/**
	 * [get_method description]
	 * @return [type] [description]
	 */
	public function get_method() {
		return 'POST';
	}

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'update-review-approval';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

		return array(
			'review_id' => array(
				'default'    => '',
				'required'   => false,
			),
			'type' => array(
				'default'    => 'like',
				'required'   => false,
			),
			'inc' => array(
				'default'    => true,
				'required'   => false,
			),
		);
	}

	/**
	 * [callback description]
	 * @param  [type]   $request [description]
	 * @return function          [description]
	 */
	public function callback( $request ) {

		$args = $request->get_params();

		$review_id = isset( $args['review_id'] ) ? $args['review_id'] : false;
		$type      = isset( $args['type'] ) ? $args['type'] : 'like';
		$inc      = isset( $args['inc'] ) ? $args['inc'] : true;

		$approval_data = Reviews_Data::get_instance()->update_review_approval( $review_id, $type, $inc );

		if ( ! $approval_data ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( 'Error Review Approval', 'jet-reviews' ),
			) );
		}

		return rest_ensure_response( array(
			'success' => true,
			'message' => __( 'Success Review Approval', 'jet-reviews' ),
			'data'    => $approval_data,
		) );
	}
}
