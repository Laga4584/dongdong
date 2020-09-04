<?php
namespace Jet_Reviews\User;

/**
 * Database manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Base DB class
 */
class Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$_conditions description]
	 * @var array
	 */
	public $registered_conditions = array();

	/**
	 * [$register_verifications description]
	 * @var array
	 */
	public $registered_verifications = array();

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Constructor for the class
	 */
	public function __construct() {
		$this->register_conditions();
		//$this->register_verifications();
	}

	/**
	 * [register_conditions description]
	 * @return [type] [description]
	 */
	public function register_conditions() {

		$base_path = jet_reviews()->plugin_path( 'includes/user/conditions/' );

		require $base_path . 'base.php';

		$default = array(
			'\Jet_Reviews\User\Conditions\User_Guest'       => $base_path . 'user-guest.php',
			'\Jet_Reviews\User\Conditions\User_Role'        => $base_path . 'user-role.php',
			'\Jet_Reviews\User\Conditions\Already_Reviewed' => $base_path . 'already-reviewed.php',
		);

		foreach ( $default as $class => $file ) {
			require $file;

			$this->register_condition( $class );
		}

		/**
		 * You could register custom conditions on this hook.
		 * Note - each condition should be presented like instance of class 'Jet_Reviews\User\Conditions\Base_Condition'
		 */
		do_action( 'jet-reviews/user/conditions/register', $this );

	}

	/**
	 * [register_condition description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function register_condition( $class ) {

		$instance = new $class;
		$this->registered_conditions[ $instance->get_slug() ] = $instance;
	}

	/**
	 * [get_condition description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function get_condition( $slug ) {

		if ( array_key_exists( $slug, $this->registered_conditions ) ) {
			return $this->registered_conditions[ $slug ];
		}

		return false;
	}

	/**
	 * [register_conditions description]
	 * @return [type] [description]
	 */
	public function register_verifications() {

		$base_path = jet_reviews()->plugin_path( 'includes/user/verifications/' );

		require $base_path . 'base.php';

		$default = array(
			'\Jet_Reviews\User\Verifications\User_Logged_In' => $base_path . 'user-logged-in.php',
		);

		foreach ( $default as $class => $file ) {
			require $file;

			$this->register_verification( $class );
		}

		/**
		 * You could register custom conditions on this hook.
		 * Note - each condition should be presented like instance of class 'Jet_Reviews\User\Verifications\Base_Verification'
		 */
		do_action( 'jet-reviews/user/verifications/register', $this );

	}

	/**
	 * [register_condition description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function register_verification( $class ) {
		$instance = new $class;
		$this->registered_verifications[ $instance->get_slug() ] = $instance;
	}

	/**
	 * [get_condition description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function get_verification( $slug ) {

		if ( array_key_exists( $slug, $this->registered_verifications ) ) {
			return $this->registered_verifications[ $slug ];
		}

		return false;
	}

	/**
	 * [get_verification_data description]
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
	public function get_verification_data( $slug = false, $args = array() ) {

		$verification_instance = $this->get_verification( $slug );

		if ( ! $verification_instance ) {
			return false;
		}

		$check = $verification_instance->check( $args );

		if ( ! $check ) {
			return false;
		}

		return array(
			'slug'    => $verification_instance->get_slug(),
			'icon'    => $verification_instance->get_icon(),
			'message' => $verification_instance->get_message(),
		);
	}

	/**
	 * [get_post_types_options description]
	 * @return [type] [description]
	 */
	public function get_verification_options() {

		$registered_verifications = $this->registered_verifications;

		if ( empty( $registered_verifications ) ) {
			return array();
		}

		$verification_options_options = array(
			array(
				'label' => esc_html__( 'None', 'jet-reviews' ),
				'value' => 'none',
			)
		);

		foreach ( $registered_verifications as $slug => $verification ) {
			$verification_options_options[] = array(
				'label' => $verification->get_name(),
				'value' => $slug,
			);
		}

		return $verification_options_options;
	}

	/**
	 * [is_user_can_publish_review description]
	 * @return boolean [description]
	 */
	public function get_user_condition_data() {

		foreach ( $this->registered_conditions as $slug => $instance ) {
			$instance_check = $instance->check();

			if ( ! $instance_check ) {
				return array(
					'allowed' => false,
					'message' => $instance->get_invalid_message(),
				);
			}
		}

		return array(
			'allowed' => true,
			'message' => __( '*This user can publish reviews.', 'jet-reviews' ),
		);
	}

	/**
	 * [get_raw_user_data description]
	 * @return [type] [description]
	 */
	public function get_raw_user_data( $user_id = false ) {

		$user_data = get_user_by( 'id', $user_id );

		if ( ! $user_data ) {
			return array(
				'id'     => 0,
				'name'   => esc_html__( 'Guest', 'jet-reviews' ),
				'mail'   => 'email@example.com',
				'avatar' => get_avatar( 'email@example.com', 64 ),
			);
		}

		return array(
			'id'     => $user_data->data->ID,
			'name'   => $user_data->data->display_name,
			'mail'   => $user_data->data->user_email,
			'avatar' => get_avatar( $user_data->data->user_email, 64 ),
		);
	}

	/**
	 * [generate_user_data description]
	 * @return [type] [description]
	 */
	public function get_current_user_data() {

		$prepared_user_data = $this->get_raw_user_data( get_current_user_id() );

		$user_condition_data = $this->get_user_condition_data();

		return array(
			'id'               => $prepared_user_data['id'],
			'name'             => $prepared_user_data['name'],
			'mail'             => $prepared_user_data['mail'],
			'avatar'           => $prepared_user_data['avatar'],
			'canReview'        => $user_condition_data['allowed'],
			'canReviewMessage' => $user_condition_data['message'],
			'canComment'       => true,
			'canApproval'      => 0 !== $prepared_user_data['id'] ? true : false,
		);
	}

	/**
	 * [add_user_reviewed_post_id description]
	 */
	public function add_user_reviewed_post_id( $post_id = false ) {

		if ( ! $post_id ) {
			return false;
		}

		$user_id = get_current_user_id();

		/**
		 * is guest check
		 */
		if ( 0 === $user_id ) {
			return false;
		}

		$reviewed_posts = get_user_meta( $user_id, 'jet-reviewed-posts', true );

		$reviewed_posts = ! empty( $reviewed_posts ) ? $reviewed_posts : array();

		if ( in_array( $post_id, $reviewed_posts ) ) {
			return false;
		}

		$reviewed_posts[] = $post_id;

		update_user_meta( $user_id, 'jet-reviewed-posts', $reviewed_posts );
	}

	/**
	 * [add_user_reviewed_post_id description]
	 * @param boolean $post_id [description]
	 */
	public function remove_user_reviewed_post_id( $post_id = false ) {

		if ( ! $post_id ) {
			return false;
		}

		$user_id = get_current_user_id();

		/**
		 * is guest check
		 */
		if ( 0 === $user_id ) {
			return false;
		}

		$reviewed_posts = get_user_meta( $user_id, 'jet-reviewed-posts', true );

		if ( empty( $reviewed_posts ) ) {
			return false;
		}

		if ( ! in_array( $post_id, $reviewed_posts ) ) {
			return false;
		}

		if ( ( $key = array_search( $post_id, $reviewed_posts ) ) !== false ) {
			unset( $reviewed_posts[ $key ] );
		}

		update_user_meta( $user_id, 'jet-reviewed-posts', $reviewed_posts );
	}

	/**
	 * [is_post_already_reviewed description]
	 * @return boolean [description]
	 */
	public function is_post_reviewed() {
		$post_id = get_the_ID();

		$user_id = get_current_user_id();

		/**
		 * is guest check
		 */
		if ( 0 === $user_id ) {
			return false;
		}

		$reviewed_posts = get_user_meta( $user_id, 'jet-reviewed-posts', true );

		if ( empty( $reviewed_posts ) ) {
			return false;
		}

		if ( in_array( $post_id, $reviewed_posts ) ) {
			return true;
		}

		return false;
	}

	/**
	 * [add_user_reviewed_post_id description]
	 */
	public function update_user_approval_review( $review_id = false, $data = array() ) {

		if ( ! $review_id || empty( $data ) ) {
			return false;
		}

		$user_id = get_current_user_id();

		/**
		 * is guest check
		 */
		if ( 0 === $user_id ) {
			return false;
		}

		$approval_reviews = get_user_meta( $user_id, 'jet-approval-reviews', true );

		$approval_reviews = ! empty( $approval_reviews ) ? $approval_reviews : array();

		$approval_reviews[ $review_id ] = $data;

		update_user_meta( $user_id, 'jet-approval-reviews', $approval_reviews );

	}

	/**
	 * [add_user_reviewed_post_id description]
	 */
	public function delete_user_approval_review( $review_id = false ) {

		if ( ! $review_id ) {
			return false;
		}

		$user_id = get_current_user_id();

		/**
		 * is guest check
		 */
		if ( 0 === $user_id ) {
			return false;
		}

		$approval_reviews = get_user_meta( $user_id, 'jet-approval-reviews', true );

		if ( empty( $approval_reviews ) ) {
			return false;
		}

		if ( ! array_key_exists( $review_id, $approval_reviews ) ) {
			return false;
		}

		unset( $approval_reviews[ $review_id ] );

		update_user_meta( $user_id, 'jet-approval-reviews', $approval_reviews );

	}

	/**
	 * [is_post_already_reviewed description]
	 * @return boolean [description]
	 */
	public function get_review_approval_data( $review_id ) {

		$user_id = get_current_user_id();

		/**
		 * is guest check
		 */
		if ( 0 === $user_id ) {
			return array(
				'like'    => false,
				'dislike' => false,
			);
		}

		$approval_reviews = get_user_meta( $user_id, 'jet-approval-reviews', true );

		if ( empty( $approval_reviews ) ) {
			return array(
				'like'    => false,
				'dislike' => false,
			);
		}

		if ( isset( $approval_reviews[ $review_id ] ) ) {
			return $approval_reviews[ $review_id ];
		}

		return array(
			'like'    => false,
			'dislike' => false,
		);
	}

}
