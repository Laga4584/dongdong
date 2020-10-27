<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Title extends Tag {
	public function get_name() {
		return 'archive-title';
	}

	public function get_title() {
		return __( 'Archive Title', 'jupiterx-core' );
	}

	public function get_group() {
		return 'archive';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	public function render() {
		$page_title = self::get_page_title();

		echo wp_kses_post( $page_title );
	}

	protected function _register_controls() {
		$this->add_control(
			'include_context',
			[
				'label' => __( 'Include Context', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
	}

	/**
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public static function get_page_title() {
		$page_title = '';

		if ( is_singular() ) {
			/* translators: %s: Search term. */
			$page_title    = get_the_title();
			$post_type_obj = get_post_type_object( get_post_type() );
			return sprintf( '%s: %s', $post_type_obj->labels->singular_name, $page_title );
		}

		if ( is_search() ) {
			/* translators: %s: Search term. */
			$page_title = sprintf( __( 'Search Results for: %s', 'jupiterx-core' ), get_search_query() );

			if ( get_query_var( 'paged' ) ) {
				/* translators: %s is the page number. */
				$page_title .= sprintf( __( '&nbsp;&ndash; Page %s', 'jupiterx-core' ), get_query_var( 'paged' ) );
			}

			return $page_title;
		}

		if ( is_category() ) {
			$page_title = single_cat_title( '', false );

			/* translators: Category archive title. 1: Category name */
			return sprintf( __( 'Category: %s', 'jupiterx-core' ), $page_title );
		}

		if ( is_tag() ) {
			$page_title = single_tag_title( '', false );
			/* translators: Tag archive title. 1: Tag name */
			return sprintf( __( 'Tag: %s', 'jupiterx-core' ), $page_title );

		}

		if ( is_author() ) {
			$page_title = '<span class="vcard">' . get_the_author() . '</span>';

			/* translators: Author archive title. 1: Author name */
			return sprintf( __( 'Author: %s', 'jupiterx-core' ), $page_title );
		}

		if ( is_year() ) {
			$page_title = get_the_date( _x( 'Y', 'yearly archives date format', 'jupiterx-core' ) );

			/* translators: Yearly archive title. 1: Year */
			return sprintf( __( 'Year: %s', 'jupiterx-core' ), $page_title );
		}

		if ( is_month() ) {
			$page_title = get_the_date( _x( 'F Y', 'monthly archives date format', 'jupiterx-core' ) );

			/* translators: Monthly archive title. 1: Month name and year */
			return sprintf( __( 'Month: %s', 'jupiterx-core' ), $page_title );
		}

		if ( is_day() ) {
			$page_title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'jupiterx-core' ) );

			/* translators: Daily archive title. 1: Date */
			return sprintf( __( 'Day: %s', 'jupiterx-core' ), $page_title );
		}

		if ( is_post_type_archive() ) {
			$page_title = post_type_archive_title( '', false );

			/* translators: Post type archive title. 1: Post type name */
			return sprintf( __( 'Archives: %s', 'jupiterx-core' ), $page_title );
		}

		if ( is_tax() ) {
			$page_title = single_term_title( '', false );

			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
			return sprintf( __( '%1$s: %2$s', 'jupiterx-core' ), $tax->labels->singular_name, $page_title );
		}

		if ( is_archive() ) {
			return __( 'Archives', 'jupiterx-core' );
		}

		if ( is_404() ) {
			return __( 'Page Not Found', 'jupiterx-core' );
		}

		return $page_title;
	}
}
