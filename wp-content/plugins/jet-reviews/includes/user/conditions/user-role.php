<?php
namespace Jet_Reviews\User\Conditions;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class User_Role extends Base_Condition {

	/**
	 * [$slug description]
	 * @var string
	 */
	private $slug = 'user-role';

	/**
	 * [$invalid_message description]
	 * @var boolean
	 */
	private $invalid_message = false;

	/**
	 * [__construct description]
	 */
	public function __construct() {
		$this->invalid_message = __( '*User with this role cannot publish a new review', 'jet-reviews' );
	}

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * [get_valid_message description]
	 * @return [type] [description]
	 */
	public function get_invalid_message() {
		return $this->invalid_message;
	}

	/**
	 * [check description]
	 * @return [type] [description]
	 */
	public function check() {

		$user = wp_get_current_user();

		if ( 0 !== $user->ID ) {
			$roles = $user->roles;
		} else {
			$roles = array( 'guest' );
		}

		$post_type_data = jet_reviews()->settings->get_the_post_type_data();

		$post_allowed_roles = $post_type_data['allowed_roles'];

		foreach ( $roles as $key => $role ) {

			if ( in_array( $role, $post_allowed_roles ) ) {
				return true;
			}
		}

		return false;
	}

}
