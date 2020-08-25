<?php
namespace Jet_Reviews\Settings;

use Jet_Reviews\Admin as Admin;
use Jet_Reviews\Settings\Manager as Settings_Manager;
use Jet_Reviews\Base_Page as Base_Page;
use Jet_Reviews\Reviews\Data as Reviews_Data;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Page extends Base_Page {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_slug() {
		return $this->base_slug . '-settings';
	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'register_page' ), 12 );
	}

	/**
	 * [register_page description]
	 * @return [type] [description]
	 */
	public function register_page() {

		add_submenu_page(
			Admin::get_instance()->admin_page_slug,
			esc_html__( 'Settings', 'jet-reviews' ),
			esc_html__( 'Settings', 'jet-reviews' ),
			'manage_options',
			$this->get_slug(),
			array( $this, 'render_page' )
		);

	}

	/**
	 * [render_page description]
	 * @return [type] [description]
	 */
	public function render_page() {
		include jet_reviews()->get_template( 'admin/pages/settings/settings-page.php' );
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_script(
			'jet-reviews-settings-page-js',
			jet_reviews()->plugin_url( 'assets/js/admin/settings-page.js' ),
			array( 'cx-vue-ui', 'wp-api-fetch' ),
			jet_reviews()->get_version(),
			true
		);

		wp_localize_script( 'jet-reviews-settings-page-js', 'JetReviewsSettingsConfig', $this->localize_config() );

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function localize_config() {
		$post_types = jet_reviews_tools()->get_post_types();

		$plugin_settings_data = array();
		$avaliable_post_types_data = array();
		$avaliable_post_types_options = array();
		$avaliable_review_types = Reviews_Data::get_instance()->get_review_types_list();

		foreach ( $post_types as $slug => $name ) {
			$review_post_type_option = $slug . '-type-settings';

			$plugin_settings_data[ $review_post_type_option ] = jet_reviews()->settings->get_post_type_data( $slug );

			$avaliable_post_types_options[] = array(
				'label' => $name,
				'value' => $slug,
			);
		}

		$config = array(
			'saveSettingsRoute'    => '/jet-reviews-api/v1/save-settings',
			'avaliablePostTypes'   => $avaliable_post_types_options,
			'avaliableReviewTypes' => $avaliable_review_types,
			'allRolesOptions'      => jet_reviews_tools()->get_roles_options(),
			'verificationOptions'  => jet_reviews()->user_manager->get_verification_options(),
			'settingsData'         => $plugin_settings_data,
		);

		return $config;

	}
}
