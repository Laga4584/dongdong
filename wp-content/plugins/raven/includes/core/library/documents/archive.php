<?php
/**
 * Add Library Header Document.
 *
 * @package Raven
 * @since 1.0.0
 */

namespace Raven\Core\Library\Documents;

use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

/**
 * Raven header library document.
 *
 * Raven header library document handler class is responsible for
 * handling a document of a header type.
 *
 * @since 1.0.0
 */
class Archive extends Library_Document {

	/**
	 * Get document properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Document properties.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		return $properties;
	}

	/**
	 * Get document name.
	 *
	 * Retrieve the document name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Document name.
	 */
	public function get_name() {
		return 'archive';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return __( 'Archive', 'raven' );
	}

	public function get_content( $with_css = false ) {
		$content = parent::get_content( $with_css );

		return $content;
	}

	public function print_content() {
		$plugin = Plugin::instance();

		if ( $plugin->preview->is_preview_mode( $this->get_main_id() ) ) {
			echo $plugin->preview->builder_wrapper( '' );
		} else {
			echo $this->get_content( true );
		}
	}

	/**
	 * Get document library category.
	 *
	 * @since 1.6.0
	 * @access protected
	 *
	 * @return array Document config.
	 */
	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = 'Jupiter X archive';

		return $config;
	}
}
