<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Reviews_Assets' ) ) {

	/**
	 * Define Jet_Reviews_Assets class
	 */
	class Jet_Reviews_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'wp_footer', array( $this, 'render_vue_template' ) );
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {

			wp_enqueue_style(
				'jet-reviews',
				jet_reviews()->plugin_url( 'assets/css/jet-reviews.css' ),
				array( 'elementor-icons-fa-solid', 'elementor-icons-fa-regular' ),
				jet_reviews()->get_version()
			);

		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {
			global $wp;

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_script(
				'jet-vue',
				jet_reviews()->plugin_url( 'assets/js/lib/vue' . $suffix . '.js' ),
				array(),
				'2.6.11',
				true
			);

			wp_enqueue_script(
				'jet-reviews-frontend',
				jet_reviews()->plugin_url( 'assets/js/jet-reviews-frontend.js' ),
				array( 'jquery', 'wp-api-fetch', 'elementor-frontend', 'jet-vue' ),
				jet_reviews()->get_version(),
				true
			);

			$localize_data = array(
				'version'                  => jet_reviews()->get_version(),
				'ajax_url'                 => esc_url( admin_url( 'admin-ajax.php' ) ),
				'current_url'              => esc_url( home_url( add_query_arg( [], $wp->request ) ) ),
				'getPublicReviewsRoute'    => '/jet-reviews-api/v1/get-public-reviews-list',
				'submitReviewCommentRoute' => '/jet-reviews-api/v1/submit-review-comment',
				'submitReviewRoute'        => '/jet-reviews-api/v1/submit-review',
				'likeReviewRoute'          => '/jet-reviews-api/v1/update-review-approval',
				'currentUserData'          => jet_reviews()->user_manager->get_current_user_data(),
				'currentPostData'          => jet_reviews_tools()->get_current_post_data(),
				'reviewTypeData'           => jet_reviews_tools()->get_post_review_type_data(),
			);

			wp_localize_script(
				'jet-reviews-frontend',
				'jetReviewPublicConfig',
				apply_filters( 'jet-reviews/public/localized-data', $localize_data )
			);

		}

		/**
		 * [render_vue_template description]
		 * @return [type] [description]
		 */
		public function render_vue_template() {

			$vue_templates = array(
				'jet-advanced-reviews-item',
				'jet-advanced-reviews-comment',
				'jet-advanced-reviews-point-field',
				'jet-advanced-reviews-star-field',
				'jet-advanced-reviews-form',
			);

			foreach ( glob( jet_reviews()->plugin_path() . 'templates/public/vue-templates/*.php' ) as $file ) {
				$path_info = pathinfo( $file );
				$template_name = $path_info['filename'];

				if ( in_array( $template_name, $vue_templates ) ) {?>
					<script type="text/x-template" id="<?php echo $template_name; ?>-template"><?php
						require $file; ?>
					</script><?php
				}
			}
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

}

/**
 * Returns instance of Jet_Reviews_Assets
 *
 * @return object
 */
function jet_reviews_assets() {
	return Jet_Reviews_Assets::get_instance();
}
