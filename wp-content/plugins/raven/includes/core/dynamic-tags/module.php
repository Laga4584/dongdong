<?php
/**
 * Dynamic tags.
 *
 * @package Raven
 * @since 1.5.0
 */

namespace Raven\Core\Dynamic_Tags;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module {

	public static $instance;

	public function __construct() {
		if ( class_exists( 'ElementorPro/Plugin' ) ) {
			return;
		}

		add_action( 'elementor/dynamic_tags/register_tags', [ $this, 'register_tags' ] );
	}

	public function get_tag_classes_names() {
		return [
			'Archive_Description',
			'Archive_Meta',
			'Archive_Title',
			'Author_Info',
			'Author_Meta',
			'Author_Name',
			'Featured_Image_Data',
			'Author_Info',
			'Post_Custom_Field',
			'Post_Date',
			'Post_Excerpt',
			'Post_ID',
			'Post_Terms',
			'Post_Time',
			'Post_Title',
			'Post_Featured_Image',
			'Shortcode',
		];
	}

	public function get_groups() {
		return [
			'site' => [
				'title' => __( 'Site', 'raven' ),
			],
			'post' => [
				'title' => __( 'Post', 'raven' ),
			],
			'archive' => [
				'title' => __( 'Archive', 'raven' ),
			],
			'media' => [
				'title' => __( 'Media', 'raven' ),
			],
			'author' => [
				'title' => __( 'Author', 'raven' ),
			],
		];
	}

	/**
	 * Register tags.
	 *
	 * Add all the available dynamic tags.
	 *
	 * @since  1.5.0
	 * @access public
	 *
	 * @param Manager $dynamic_tags
	 */
	public function register_tags( $dynamic_tags ) {

		foreach ( $this->get_groups() as $group_name => $group_title ) {
			$dynamic_tags->register_group( $group_name, [
				'title' => $group_title['title'],
			] );
		}

		// Files already included by autoload.
		foreach ( $this->get_tag_classes_names() as $tag_class ) {

			if ( class_exists( 'Raven\Core\Dynamic_Tags\Tags\\' . $tag_class ) ) {
				$dynamic_tags->register_tag( 'Raven\Core\Dynamic_Tags\Tags\\' . $tag_class );
			}
		}
	}
}
