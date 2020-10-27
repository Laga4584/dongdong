<?php
/**
 * Adds utils.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;

/**
 * Raven utils class.
 *
 * Raven utils handler class is responsible for different utility methods
 * used by Raven.
 *
 * @since 1.0.0
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Utils {

	/**
	 * Get the svg directory path.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param  string $file_name SVG file name.
	 * @return string Directory path.
	 */
	public static function get_svg( $file_name = '' ) {
		if ( empty( $file_name ) ) {
			return $file_name;
		}

		return Plugin::$plugin_path . 'assets/img/' . $file_name . '.svg';
	}

	/**
	 * Generate data-parallax based on params.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param int $pxs_x          X position.
	 * @param int $pxs_y          Y position.
	 * @param int $pxs_z          Z position.
	 * @param int $pxs_smoothness Smoothness level.
	 *
	 * @return string Data attribute.
	 */
	public static function parallax_scroll( $pxs_x, $pxs_y, $pxs_z, $pxs_smoothness ) {
		$parallax_scroll = [];

		if ( ! empty( $pxs_x ) ) {
			$parallax_scroll[] = '"x":' . $pxs_x;
		}

		if ( ! empty( $pxs_y ) ) {
			$parallax_scroll[] = '"y":' . $pxs_y;
		}

		if ( ! empty( $pxs_z ) ) {
			$parallax_scroll[] = '"z":' . $pxs_z;
		}

		if ( ! empty( $pxs_smoothness ) ) {
			$parallax_scroll[] = '"smoothness":' . $pxs_smoothness;
		}

		if ( empty( $parallax_scroll ) ) {
			return;
		}

		return '{' . implode( ',', $parallax_scroll ) . '}';
	}

	/**
	 * Get site domain.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Site domain.
	 */
	public static function get_site_domain() {
		// @codingStandardsIgnoreStart
		// Get the site domain and get rid of www.
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );

		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		return $sitename;
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Get WP_Query arguments.
	 *
	 * Retrieving arguments from element settings.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return args Prepared WP_Query arguments.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public static function get_query_args( $settings ) {
		$settings = array_merge(
			[
				'query_post_type' => 'post',
				'query_posts_per_page' => 3,
				'query_orderby' => 'date',
				'query_order' => 'DESC',
				'category' => -1,
			],
			$settings
		);

		$args = [
			'post_type' => $settings['query_post_type'],
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => $settings['query_orderby'],
			'order' => $settings['query_order'],
			'post_status' => 'publish',
			'ignore_sticky_posts' => empty( $settings['query_ignore_sticky_posts'] ) ? 0 : 1,
			'paged' => max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) ),
		];

		// Only use offset on all category state.
		if ( -1 === $settings['category'] && ! empty( $settings['query_offset'] ) ) {
			$args['offset_proper'] = $settings['query_offset'];
		}

		if ( ! empty( $settings['paged'] ) ) {
			$args['paged'] = $settings['paged'];
		}

		if ( ! empty( $settings['query_excludes'] ) ) {
			$current_post_key = array_search( 'current_post', $settings['query_excludes'], true );

			// If current_post is existing in the array values replace it with the current post viewing ID.
			if ( false !== $current_post_key ) {
				$settings['query_excludes'][ $current_post_key ] = get_the_ID();
			}

			$args['post__not_in'] = $settings['query_excludes'];

			if ( ! empty( $settings['query_excludes_ids'] ) && is_array( $settings['query_excludes_ids'] ) ) {
				$args['post__not_in'] = array_merge( $args['post__not_in'], $settings['query_excludes_ids'] );
			}
		}

		if ( ! empty( $settings[ 'query_' . $args['post_type'] . '_includes' ] ) ) {
			$args['post__in'] = $settings[ 'query_' . $args['post_type'] . '_includes' ];
		}

		if ( ! empty( $settings['query_authors'] ) ) {
			$args['author__in'] = $settings['query_authors'];
		}

		$taxonomies = get_object_taxonomies( $args['post_type'], 'names' );

		if ( ! empty( $settings['category'] ) && $settings['category'] > 0 && ! empty( $taxonomies ) ) {
			$args['tax_query'] = [];

			$taxonomies_length = count( $taxonomies );

			for ( $i = 0; $i < $taxonomies_length; $i++ ) {
				if ( false === strpos( $taxonomies[ $i ], 'cat' ) ) {
					continue;
				}

				$args['tax_query'][] = [
					'taxonomy' => $taxonomies[ $i ],
					'field' => 'term_id',
					'terms' => $settings['category'],
				];

				break;
			}
		} elseif ( empty( $settings[ 'query_' . $args['post_type'] . '_includes' ] ) && ! empty( $taxonomies ) ) {
			$args['tax_query'] = [];

			foreach ( $taxonomies as $taxonomy ) {
				$taxonomy_control_id = 'query_' . $taxonomy . '_ids';

				if ( ! empty( $settings[ $taxonomy_control_id ] ) ) {
					$args['tax_query'][] = [
						'taxonomy' => $taxonomy,
						'field' => 'term_id',
						'terms' => $settings[ $taxonomy_control_id ],
					];
				}
			}
		}

		return $args;
	}

	/**
	 * Get responsive class base on settings key.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param  string $prefix Before class string.
	 * @param  array  $key Settings key.
	 * @param  string $settings Settings stored.
	 *
	 * @return string Responsive class.
	 */
	public static function get_responsive_class( $prefix = '', $key = '', $settings ) {
		if ( empty( $prefix ) || empty( $key ) ) {
			return;
		}

		$devices = [
			\Elementor\Controls_Stack::RESPONSIVE_DESKTOP,
			\Elementor\Controls_Stack::RESPONSIVE_TABLET,
			\Elementor\Controls_Stack::RESPONSIVE_MOBILE,
		];

		$classes = [];

		foreach ( $devices as $device_name ) {
			$temp_key = \Elementor\Controls_Stack::RESPONSIVE_DESKTOP === $device_name ? $key : $key . '_' . $device_name;

			if ( ! isset( $settings[ $temp_key ] ) ) {
				return;
			}

			$device = \Elementor\Controls_Stack::RESPONSIVE_DESKTOP === $device_name ? '' : '-' . $device_name;

			$classes[] = sprintf( $prefix . $settings[ $temp_key ], $device );
		}

		return implode( ' ', $classes );
	}


	/**
	 * Get element settings recursively.
	 *
	 * Retrieve specific element settings by model ID.
	 *
	 * @param  array  $elements Page elements.
	 * @param  string $model_id Element model id.
	 *
	 * @return array|false Return array if element found.
	 */
	public static function find_element_recursive( $elements, $model_id ) {
		foreach ( $elements as $element ) {
			if ( $model_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $model_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	/**
	 * Wrapper around the core WP get_plugins function, making sure it's actually available.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param string $plugin_folder Optional. Relative path to single plugin folder.
	 *
	 * @return array Array of installed plugins with plugin information.
	 */
	public static function get_plugins( $plugin_folder = '' ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return get_plugins( $plugin_folder );
	}

	/**
	 * Checks if a plugin is installed. Does not take must-use plugins into account.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param string $slug Required. Plugin slug.
	 *
	 * @return bool True if installed, false otherwise.
	 */
	public static function is_plugin_installed( $slug ) {
		return ! empty( self::get_plugins( '/' . $slug ) );
	}

	/**
	 * Get automatic direction based on RTL/LTR.
	 *
	 * @since 1.0.0
	 *
	 * @param string $direction The direction.
	 *
	 * @return string The direction.
	 */
	public static function get_direction( $direction ) {
		if ( ! is_rtl() ) {
			return $direction;
		}

		if ( false !== stripos( $direction, 'left' ) ) {
			return str_replace( 'left', 'right', $direction );
		}

		if ( false !== stripos( $direction, 'right' ) ) {
			return str_replace( 'right', 'left', $direction );
		}

		return $direction;
	}

	/**
	 * Get post ID based on document.
	 *
	 * @since 1.0.0
	 */
	public static function get_current_post_id() {
		if ( isset( Elementor::$instance->documents ) && ! empty( Elementor::$instance->documents->get_current() ) ) {
			return Elementor::$instance->documents->get_current()->get_main_id();
		}

		return get_the_ID();
	}

	/**
	 * Get Client IP Address.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @return string
	 */
	public static function get_client_ip() {
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
			$ip_address = $_SERVER['HTTP_X_FORWARDED'];
		} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
			$ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
		} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
			$ip_address = $_SERVER['HTTP_FORWARDED'];
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		// phpcs:enable
		return $ip_address;
	}

	/**
	 * Download File.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 */
	public static function handle_file_download() {
		$file  = filter_input( INPUT_GET, 'file' );
		$nonce = filter_input( INPUT_GET, '_wpnonce' );

		// Validate nonce.
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce ) ) {
			wp_die( '<script>window.close();</script>' );
		}

		$file       = base64_decode( $file ); // phpcs:ignore
		$upload_dir = wp_get_upload_dir();

		// Make sure file exists.
		if ( empty( $file ) || ! file_exists( $file ) ) {
			wp_die( '<script>window.close();</script>' );
		}

		// Restrict the download to WP upload directory.
		if ( strpos( $file, $upload_dir['basedir'] ) > 0 ) {
			wp_die( '<script>window.close();</script>' );
		}

		$file_name = pathinfo( $file, PATHINFO_BASENAME );
		$file_ext  = pathinfo( $file, PATHINFO_EXTENSION );

		// Strip hash.
		$file_name  = str_replace( $file_ext, '', $file_name );
		$file_parts = explode( '__', $file_name );
		$file_name  = array_shift( $file_parts );
		$file_name .= '.' . $file_ext;

		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename="' . $file_name . '"' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );
		header( 'Content-Length: ' . filesize( $file ) );
		// phpcs:ignore WordPress.WP.AlternativeFunctions
		readfile( $file );
	}
}
