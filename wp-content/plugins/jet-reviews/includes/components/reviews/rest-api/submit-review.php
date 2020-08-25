<?php
namespace Jet_Reviews\Endpoints;

use Jet_Reviews\Reviews\Data as Reviews_Data;
use Jet_Reviews\User\Manager as User_Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Posts class
 */
class Submit_Review extends Base {

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
		return 'submit-review';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

		return array(
			'post' => array(
				'default'    => '',
				'required'   => false,
			),
			'title' => array(
				'default'    => '',
				'required'   => false,
			),
			'content' => array(
				'default'    => '',
				'required'   => false,
			),
			'rating_data' => array(
				'default'    => [],
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

		$post_id     = isset( $args['post'] ) ? $args['post'] : false;
		$title       = isset( $args['title'] ) ? wp_kses_post( $args['title'] ) : '';
		$content     = isset( $args['content'] ) ? wp_kses_post( $args['content'] ) : '';
		$rating_data = isset( $args['rating_data'] ) ? $args['rating_data'] : [];

		if ( jet_reviews_tools()->is_demo_mode() ) {
			return rest_ensure_response( array(
				'success'       => false,
				'message'       => __( 'You can\'t leave a review. Demo mode is active', 'jet-reviews' ),
				'data'          => [],
			) );
		}

		$post_type      = get_post_type( $post_id );
		$post_type_data = jet_reviews()->settings->get_post_type_data( $post_type );
		$rating         = $this->calculate_rating( $rating_data );
		$author_id      = get_current_user_id();

		$prepared_data = array(
			'post_id'      => $post_id,
			'post_type'    => get_post_type( $post_id ),
			'author'       => $author_id,
			'date'         => current_time( 'mysql' ),
			'title'        => $title,
			'content'      => $content,
			'type_slug'    => $post_type_data['review_type'],
			'rating_data'  => maybe_serialize( $rating_data ),
			'rating'       => $rating,
			'approved'     => filter_var( $post_type_data['need_approve'], FILTER_VALIDATE_BOOLEAN ) ? 0 : 1,
			'pinned'       => 0,
		);

		$insert_id = Reviews_Data::get_instance()->add_new_review( $prepared_data );

		jet_reviews()->user_manager->add_user_reviewed_post_id( $post_id );

		if ( ! $insert_id ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( 'DataBase Error', 'jet-reviews' ),
			) );
		}

		if ( filter_var( $post_type_data['need_approve'], FILTER_VALIDATE_BOOLEAN ) ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( '*Your review must be approved by the moderator', 'jet-reviews' ),
			) );
		}

		$author_data = jet_reviews()->user_manager->get_raw_user_data( $author_id );

		$return_data = array(
			'id'         => $insert_id,
			'post'    => array(
				'id'    => $post_id,
				'title' => wp_trim_words( get_the_title( $post_id ), 3, ' ...' ),
				'link'  => get_permalink( $post_id ),
			),
			'post_type'  => $prepared_data['post_type'],
			'author'     => array(
				'id'     => $author_data['id'],
				'name'   => $author_data['name'],
				'mail'   => $author_data['mail'],
				'avatar' => $author_data['avatar'],
			),
			'date'         => array(
				'raw'        => $prepared_data['date'],
				'human_diff' => jet_reviews_tools()->human_time_diff_by_date( $prepared_data['date'] ),
			),
			'title'         => $title,
			'content'       => $content,
			'type_slug'     => $prepared_data['type_slug'],
			'rating_data'   => $rating_data,
			'rating'        => $rating,
			'comments'      => array(),
			'approved'      => $post_type_data['need_approve'],
			'like'          => 0,
			'dislike'       => 0,
			'approval'      => jet_reviews()->user_manager->get_review_approval_data( $insert_id ),
			'pinned'        => false,
		);

		$condition = jet_reviews()->user_manager->get_condition( 'already-reviewed' );

		$message = __( 'New Review has been created', 'jet-reviews' );

		if ( 0 !== $author_data['id'] ) {
			$message = $condition->get_invalid_message();
		}

		return rest_ensure_response( array(
			'success'       => true,
			'message'       => $message,
			'data'          => $return_data,
		) );
	}

	/**
	 * [calculate_rating description]
	 * @param  [type] $rating_data [description]
	 * @return [type]              [description]
	 */
	public function calculate_rating( $rating_data ) {

		$fields_rating = array();

		foreach ( $rating_data as $key => $field_data ) {
			$value = (int)$field_data['field_value'];
			$max   = (int)$field_data['field_max'];

			$fields_rating[] = round( ( 100 * $value ) / $max, 2 );
		}

		return round( array_sum( $fields_rating ) / count( $fields_rating ) );
	}

}
