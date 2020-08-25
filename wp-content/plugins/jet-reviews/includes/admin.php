<?php
namespace Jet_Reviews;
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Manager class
 */
class Admin {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Jet engine menu page slug
	 *
	 * @var string
	 */
	public $admin_page_slug = 'jet-reviews';

	/**
	 * [$registered_pages description]
	 * @var array
	 */
	public $components = array();

	/**
	 * [$inited_components description]
	 * @var array
	 */
	public $inited_components = array();

	/**
	 * [$cx_vue_ui description]
	 * @var null
	 */
	public $cx_vue_ui = null;

	/**
	 * Constructor for the class
	 */
	function __construct() {

		$this->init();

		add_action( 'admin_menu', array( $this, 'register_main_menu_page' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_main_page_assets' ) );

	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init() {

		$module_data = jet_reviews()->module_loader->get_included_module_data( 'cherry-x-vue-ui.php' );

		$this->cx_vue_ui = new \CX_Vue_UI( $module_data );

	}

	/**
	 * Register menu page
	 *
	 * @return void
	 */
	public function register_main_menu_page() {

		add_menu_page(
			__( 'JetReviews', 'jet-reviews' ),
			__( 'JetReviews', 'jet-reviews' ),
			'manage_options',
			$this->admin_page_slug,
			array( $this, 'render_page' ),
			'dashicons-star-half'
		);

	}

	/**
	 * Initialize interface builder
	 *
	 * @return [type] [description]
	 */
	public function enqueue_assets() {

		do_action( 'jet-reviews/dashboard/before-assets' );

		wp_enqueue_style(
			'jet-reviews-admin-css',
			jet_reviews()->plugin_url( 'assets/css/admin.css' ),
			array(),
			jet_reviews()->get_version()
		);

		wp_enqueue_script(
			'jet-reviews-admin-js',
			jet_reviews()->plugin_url( 'assets/js/admin/admin.js' ),
			array( 'cx-vue-ui', 'wp-api-fetch' ),
			jet_reviews()->get_version(),
			true
		);

		$localize_config = apply_filters( 'jet-reviews/admin/localize-config', array(
			'version' => jet_reviews()->get_version(),
		) );

		wp_localize_script( 'jet-reviews-admin-js', 'JetReviewsAdminConfig', $localize_config );

		do_action( 'jet-reviews/dashboard/after-assets' );

	}

	/**
	 * [enqueue_main_page_assets description]
	 * @return [type] [description]
	 */
	public function enqueue_main_page_assets() {

		$this->cx_vue_ui->enqueue_assets();

		if ( isset( $_REQUEST['page'] ) && $this->admin_page_slug === $_REQUEST['page'] ) {

			wp_enqueue_script(
				'chartjs.js',
				jet_reviews()->plugin_url( 'assets/js/lib/chartjs.min.js' ),
				array(),
				'2.7.1',
				true
			);

			wp_enqueue_script(
				'vuechartjs.js',
				jet_reviews()->plugin_url( 'assets/js/lib/vue-chartjs.min.js' ),
				array(),
				'3.5.0',
				true
			);

			wp_enqueue_script(
				'jet-reviews-main-page-js',
				jet_reviews()->plugin_url( 'assets/js/admin/main-page.js' ),
				array( 'cx-vue-ui', 'wp-api-fetch' ),
				jet_reviews()->get_version(),
				true
			);

			wp_localize_script( 'jet-reviews-main-page-js', 'JetReviewsMainPageConfig', $this->localize_main_page_config() );
		}
	}


	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function localize_main_page_config() {

		$post_types    = jet_reviews_tools()->get_post_types();
		$postTypesData = array();

		$month_list = array_map( function( $month_data ) {
			return $month_data['label'];
		}, jet_reviews_tools()->get_default_reviews_dataset() );

		$all_review_dataset = jet_reviews()->reviews_manager->data->get_review_dataset_by_post();
		$approved_review_dataset = jet_reviews()->reviews_manager->data->get_review_dataset_by_post( false, false, true );

		foreach ( $all_review_dataset as $key => $value ) {
			$not_approved_review_dataset[] = intval( $value ) - intval( $approved_review_dataset[ $key ] );
		}

		$generalDataSet = array(
			array(
				'label'            => __( 'All', 'jet-reviews' ),
				'data'             => $all_review_dataset,
				'backgroundColor'  => 'rgba(35, 156, 255, 0.05)',
				'borderColor'      => 'rgb(35, 156, 255)',
				'borderWidth'      => 2,
				'pointBorderWidth' => 2,
				'pointRadius'      => 3,
				'order'            => 0,
			),
			array(
				'label'            => __( 'Approved', 'jet-reviews' ),
				'data'             => $approved_review_dataset,
				'backgroundColor'  => 'rgba(35, 156, 255, 0.05)',
				'borderColor'      => '#46B450',
				'borderWidth'      => 2,
				'pointBorderWidth' => 2,
				'pointRadius'      => 3,
				'order'            => 1,
				'fill'             => false,
			),
			array(
				'label'            => __( 'Not Approved', 'jet-reviews' ),
				'data'             => $not_approved_review_dataset,
				'backgroundColor'  => 'rgba(35, 156, 255, 0.05)',
				'borderColor'      => '#C92C2C',
				'borderWidth'      => 2,
				'pointBorderWidth' => 2,
				'pointRadius'      => 3,
				'order'            => 1,
				'fill'             => false,
			),

		);

		foreach ( $post_types as $slug => $name ) {

			$post_type_settings = jet_reviews()->settings->get_post_type_data( $slug );

			$postTypesData[] = array(
				'label'               => $name,
				'slug'                => $slug,
				'reviewCount' => array(
					'all'    => jet_reviews()->reviews_manager->data->get_review_count( $slug ),
					'low'    => jet_reviews()->reviews_manager->data->get_review_count( $slug, 'low' ),
					'medium' => jet_reviews()->reviews_manager->data->get_review_count( $slug, 'medium' ),
					'high'   => jet_reviews()->reviews_manager->data->get_review_count( $slug, 'high' ),
				),
				'approvedReviews'     => jet_reviews()->reviews_manager->data->get_approved_review_count( $slug ),
				'allowed'             => $post_type_settings['allowed'],
				'needApprove'         => $post_type_settings['need_approve'],
				'commentsAllowed'     => $post_type_settings['comments_allowed'],
				'commentsNeedApprove' => $post_type_settings['comments_need_approve'],
				'approvalAllowed'     => $post_type_settings['approval_allowed'],
				'reviewType'          => $post_type_settings['review_type'],
				'dataSet' => array(
					array(
						'label'            => $name,
						'data'             => jet_reviews()->reviews_manager->data->get_review_dataset_by_post( $slug ),
						'backgroundColor'  => 'rgba(35, 156, 255, 0.05)',
						'borderColor'      => 'rgb(35, 156, 255)',
						'borderWidth'      => 2,
						'pointBorderWidth' => 2,
						'pointRadius'      => 3,
						'fill'             => true,
					)
				),
			);

			/*$generalDataSet[] = array(
				'label'       => $name,
				'data'        => jet_reviews()->reviews_manager->data->get_review_dataset_by_post( $slug ),
				'borderColor' => jet_reviews_tools()->rand_hex_color(),
				'borderWidth' => 1,
				'order'       => 1,
				'fill'        => false,
			);*/
		}

		$config = array(
			'reviewCount'           => array(
				'all'    => jet_reviews()->reviews_manager->data->get_review_count(),
				'low'    => jet_reviews()->reviews_manager->data->get_review_count( false, 'low' ),
				'medium' => jet_reviews()->reviews_manager->data->get_review_count( false, 'medium' ),
				'high'   => jet_reviews()->reviews_manager->data->get_review_count( false, 'high' ),
			),
			'approvedReviewCount'   => jet_reviews()->reviews_manager->data->get_approved_review_count(),
			'commentsCount'         => jet_reviews()->comments_manager->data->get_comment_count(),
			'approvedCommentsCount' => jet_reviews()->comments_manager->data->get_approved_comment_count(),
			'generalDataSets'       => $generalDataSet,
			'postTypes'             => $postTypesData,
			'monthList'             => $month_list,
		);

		return $config;
	}


	/**
	 * Returns dashboard page URL
	 * @return [type] [description]
	 */
	public function dashboard_url() {
		return add_query_arg(
			array( 'page' => $this->admin_page_slug ),
			esc_url( admin_url( 'admin.php' ) )
		);
	}

	/**
	 * Render main admin page
	 *
	 * @return void
	 */
	public function render_page() {
		include jet_reviews()->get_template( 'admin/pages/dashboard/main.php' );
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
