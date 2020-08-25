<?php
namespace Jet_Reviews\Settings;

use Jet_Reviews\Endpoints as Endpoints;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$key description]
	 * @var string
	 */
	private $slug = 'settings-manager';

	/**
	 * [$key description]
	 * @var string
	 */
	public $key = 'jet-reviews-settings';

	/**
	 * [$settings description]
	 * @var null
	 */
	public $settings_page_config = null;

	/**
	 * [$page description]
	 * @var null
	 */
	public $page = null;

	/**
	 * Constructor for the class
	 */
	function __construct() {

		$this->load_files();

		add_action( 'jet-reviews/init', array( $this, 'init' ) );

		add_action( 'jet-reviews/rest/init-endpoints', array( $this, 'init_endpoints' ), 10, 1 );

	}

	/**
	 * [get_slug description]
	 * @return [type] [description]
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * [load_files description]
	 * @return [type] [description]
	 */
	public function load_files() {

		require jet_reviews()->plugin_path( 'includes/components/settings/rest-api/save-settings.php' );

		if ( is_admin() ) {
			require jet_reviews()->plugin_path( 'includes/components/settings/admin-pages/settings-page.php' );
		}
	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init() {

		if ( is_admin() ) {
			$this->page = new Page();
		}

	}

	/**
	 * [init_endpoints description]
	 * @return [type] [description]
	 */
	public function init_endpoints( $rest_api_manager ) {
		$rest_api_manager->register_endpoint( new Endpoints\Save_Settings() );
	}

	/**
	 * [get description]
	 * @param  [type]  $setting [description]
	 * @param  boolean $default [description]
	 * @return [type]           [description]
	 */
	public function get( $setting, $default = false ) {

		if ( null === $this->settings_page_config ) {
			$this->settings_page_config = get_option( $this->key, array() );
		}

		return isset( $this->settings_page_config[ $setting ] ) ? $this->settings_page_config[ $setting ] : $default;
	}

	/**
	 * [get_post_type_data description]
	 * @param  [type] $post_type [description]
	 * @return [type]            [description]
	 */
	public function get_post_type_data( $post_type ) {

		$post_types = jet_reviews_tools()->get_post_types();

		$option_name = $post_type . '-type-settings';

		$allowed_post_types = $this->get( 'allowed-post-types', array( 'post' => 'true' ) );

		$default_allowed = isset( $allowed_post_types[ $post_type ] ) ? filter_var( $allowed_post_types[ $post_type ], FILTER_VALIDATE_BOOLEAN ) : false;

		$default_data = array(
			'allowed'               => $default_allowed,
			'name'                  => isset( $post_types[ $post_type ] ) ? $post_types[ $post_type ] : $post_type,
			'slug'                  => $post_type,
			'review_type'           => 'default',
			'allowed_roles'         => array(
				'administrator',
				'editor',
				'author',
				'contributor',
				'subscriber',
			),
			'verification'          => 'none',
			'need_approve'          => false,
			'comments_allowed'      => true,
			'comments_need_approve' => false,
			'approval_allowed'      => true,
		);

		$saved_data = $this->get( $option_name, array() );

		return wp_parse_args( $saved_data, $default_data );

	}

	/**
	 * [get_the_post_type_data description]
	 * @return [type] [description]
	 */
	public function get_the_post_type_data() {
		$post_id = get_the_ID();
		$post_type = get_post_type( $post_id );

		return $this->get_post_type_data( $post_type );
	}

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
}
