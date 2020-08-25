<?php
namespace Jet_Reviews\Endpoints;

use Jet_Reviews\Comments\Data as Comments_Data;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Posts class
 */
class Submit_Review_Comment extends Base {

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
		return 'submit-review-comment';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

		return array(
			'post_id' => array(
				'default'    => '',
				'required'   => false,
			),
			'parent_id' => array(
				'default'    => '',
				'required'   => false,
			),
			'review_id' => array(
				'default'    => '',
				'required'   => false,
			),
			'content' => array(
				'default'    => '',
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

		$post_id = isset( $args['post_id'] ) ? $args['post_id'] : 0;

		if ( ! $post_id ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( 'Error', 'jet-reviews' ),
			) );
		}

		$post_type = get_post_type( $post_id );
		$post_type_data = jet_reviews()->settings->get_post_type_data( $post_type );
		$author_id = get_current_user_id();

		$data = array(
			'post_id'   => isset( $args['post_id'] ) ? $args['post_id'] : 0,
			'parent_id' => isset( $args['parent_id'] ) ? $args['parent_id'] : 0,
			'review_id' => isset( $args['review_id'] ) ? $args['review_id'] : 0,
			'author'    => $author_id,
			'content'   => wp_kses_post( $args['content'] ),
			'date'      => current_time( 'mysql' ),
			'approved'  => filter_var( $post_type_data['comments_need_approve'], FILTER_VALIDATE_BOOLEAN ) ? 0 : 1,
		);

		$insert_id = Comments_Data::get_instance()->submit_review_comment( $data );

		if ( ! $insert_id ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( 'Error', 'jet-reviews' ),
			) );
		}

		if ( filter_var( $post_type_data['comments_need_approve'], FILTER_VALIDATE_BOOLEAN ) ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( '*Your comment must be approved by the moderator', 'jet-reviews' ),
			) );
		}

		$user_data = jet_reviews()->user_manager->get_raw_user_data( $author_id );

		$return_data = array(
			'id'        => $insert_id,
			'post_id'   => $data['post_id'],
			'parent_id' => $data['parent_id'],
			'review_id' => $data['review_id'],
			'author'    => array(
				'id'     => $user_data['id'],
				'name'   => $user_data['name'],
				'mail'   => $user_data['mail'],
				'avatar' => $user_data['avatar'],
			),
			'date'      => array(
				'raw'        => $data['date'],
				'human_diff' => jet_reviews_tools()->human_time_diff_by_date( $data['date'] ),
			),
			'content'   => $data['content'],
			'approved'  => filter_var( $data['approved'], FILTER_VALIDATE_BOOLEAN ),
			'children'  => array(),
		);

		return rest_ensure_response( array(
			'success' => true,
			'message' => __( 'New Comment has been saved', 'jet-reviews' ),
			'data'    => $return_data,
		) );
	}

}
