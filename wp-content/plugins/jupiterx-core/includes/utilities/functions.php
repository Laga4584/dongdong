<?php
/**
 * Utility functions.
 *
 * @package JupiterX_Core\Utilities
 */

if ( ! function_exists( 'jupiterx_get_update_plugins' ) ) {
	/**
	 * Get important plugins to update.
	 *
	 * @since 1.10.0
	 *
	 * @param boolean $jupiterx_plugins Filter only Jupiter X plugins.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_get_update_plugins( $jupiterx_plugins = true ) {
		$update_plugins = [];

		$headers = [
			'api-key'      => jupiterx_get_option( 'api_key' ),
			'domain'       => $_SERVER['SERVER_NAME'], // phpcs:ignore
			'theme-name'   => 'JupiterX',
			'from'         => 0,
			'count'        => 0,
			'list-of-attr' => wp_json_encode( [
				'slug',
				'version',
				'name',
				'basename',
			] ),
		];

		$response = json_decode( wp_remote_retrieve_body( wp_remote_get( 'https://artbees.net/api/v2/tools/plugin-custom-list', [
			'headers'   => $headers,
		] ) ) );

		if ( ! $jupiterx_plugins ) {
			return $response->data;
		}

		// Filter to get pro and core plugins only.
		$data = array_filter( $response->data, function( $plugin ) {
			return in_array( $plugin->slug, [ 'jupiterx-pro', 'jupiterx-core', 'raven' ], true );
		} );

		foreach ( $data as $plugin ) {
			$file = trailingslashit( WP_PLUGIN_DIR ) . $plugin->basename;

			if ( ! is_readable( $file ) ) {
				continue;
			}

			$cur_plugin = get_file_data( $file, [
				'Version' => 'Version',
			] );

			if ( version_compare( $plugin->version, $cur_plugin['Version'], '>' ) ) {
				$update_plugins[] = [
					'basename' => $plugin->basename,
					'name'     => $plugin->name,
					'slug'     => $plugin->slug,
					'action'   => 'update',
				];
			}
		}

		$slugs = array_column( $update_plugins, 'slug' );

		if ( ! in_array( 'jupiterx-pro', $slugs, true ) && ! function_exists( 'jupiterx_pro' ) ) {
			$update_plugins[] = [
				'basename' => 'jupiterx-pro/jupiterx-pro.php',
				'name'     => 'Jupiter X Pro',
				'slug'     => 'jupiterx-pro',
				'action'   => 'install',
			];
		}

		foreach ( $update_plugins as $index => $plugin ) {
			if ( ! jupiterx_is_registered() && in_array( $plugin['slug'], [ 'jupiterx-pro', 'raven' ], true ) ) {
				unset( $update_plugins[ $index ] );
			}
		}

		return $update_plugins;
	}
}

if ( ! function_exists( 'jupiterx_get_plugin_conflicts' ) ) {
	/**
	 * Get conflicts with themes & plugins for a specfic plugin.
	 *
	 * @param array $plugin_data Plugin to check for conflicts.
	 * @param array $plugins List of plugins.
	 *
	 * @since 1.10.0
	 *
	 * @return array
	 */
	function jupiterx_get_plugin_conflicts( $plugin_data, $plugins ) {
		$conflicts = [
			'themes'  => [],
			'plugins' => [],
		];

		$plugin_data = apply_filters( 'jupiterx_check_plugin_conflicts', $plugin_data );
		if ( empty( $plugin_data['compatible_with'] ) ) {
			return $conflicts;
		}
		$compatibility = $plugin_data['compatible_with'];
		foreach ( $plugins as $plugin_basename => $plugin ) {
			$plugin_slug = explode( '/', $plugin_basename );
			$plugin_slug = array_shift( $plugin_slug );
			// Ignore comparing to itself.
			if ( $plugin_slug === $plugin_data['slug'] ) {
				continue;
			}
			if ( empty( $plugin_data['compatible_with'] ) ) {
				continue;
			}
			if ( ! in_array( 'plugin/' . $plugin_slug, array_keys( $compatibility ), true ) ) {
				continue;
			}
			if (
				version_compare( $plugin['Version'], $compatibility[ 'plugin/' . $plugin_slug ] ) === -1
			) {
				$conflicts['plugins'][] = [
					'name'        => $plugin['Name'],
					'slug'        => $plugin_slug,
					'min_version' => $compatibility[ 'plugin/' . $plugin_slug ],
					'message'     => sprintf(// translators: 1: Plugin name, 2: Plugin slug.
						__( 'Update %1$s Plugin to %2$s', 'jupiterx' ),
						$plugin['Name'],
						$compatibility[ 'plugin/' . $plugin_slug ]
					),
				];
			}
		}
		if (
			! empty( $compatibility[ 'theme/' . JUPITERX_SLUG ] ) &&
			version_compare( JUPITERX_VERSION, $compatibility[ 'theme/' . JUPITERX_SLUG ] ) === -1
		) {
			$conflicts['themes'][] = [
				'name'        => JUPITERX_NAME,
				'min_version' => $compatibility[ 'theme/' . JUPITERX_SLUG ],
				'slug'        => JUPITERX_SLUG,
				'message'     => sprintf(// translators: 1: Theme name, 2: Theme slug.
					__( 'Update %1$s Theme to %2$s', 'jupiterx' ),
					JUPITERX_NAME,
					$compatibility[ 'theme/' . JUPITERX_SLUG ]
				),
			];
		}
		return $conflicts;
	}
}

if ( ! function_exists( 'jupiterx_get_managed_plugins' ) ) {
	/**
	 * Get managed plugins.
	 *
	 * @since 1.10.0
	 *
	 * @param boolean $force Force plugins from API.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_get_managed_plugins( $force = false ) {
		$api_url         = 'https://artbees.net/api/v2/tools/plugin-custom-list';
		$managed_plugins = get_site_transient( 'jupiterx_managed_plugins' );

		if ( false !== $managed_plugins && ! $force ) {
			return $managed_plugins;
		}

		$managed_plugins = [];

		$headers = [
			'api-key'      => jupiterx_get_api_key(),
			'domain'       => $_SERVER['SERVER_NAME'], // phpcs:ignore
			'theme-name'   => 'JupiterX',
			'from'         => 0,
			'count'        => 0,
			'list-of-attr' => wp_json_encode( [
				'name',
				'slug',
				'required',
				'version',
				'source',
				'pro',
			] ),
		];

		$response = json_decode( wp_remote_retrieve_body( wp_remote_get( $api_url, [
			'headers'   => $headers,
		] ) ) );

		if ( ! isset( $response->data ) || ! is_array( $response->data ) ) {
			return [];
		}

		$managed_plugins = apply_filters( 'jupiterx_managed_plugins', $response->data );

		set_site_transient( 'jupiterx_managed_plugins', $managed_plugins, DAY_IN_SECONDS );

		return $managed_plugins;
	}
}

add_shortcode( 'jupiterx_current_date', 'jupiterx_current_date_shortcode' );
	/**
	 * Return current date.
	 *
	 * @since 1.16.0
	 *
	 * @param array $atts shortcode attribute date format.
	 *
	 * @return string date format.
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
function jupiterx_current_date_shortcode( $atts ) {

	/**
	 * Shortcode attributes.
	 * [current_date format=’d/m/Y’] =>  01/05/2020
	 * [current_date format=’F d, Y’] => Feb 04, 2020
	 */

	$atts = shortcode_atts(
		[
			'format' => '',
		], $atts
	);

	if ( ! empty( $atts['format'] ) ) {
		$date_format = $atts['format'];
	} else {
		$date_format = 'l jS \of F Y h:i:s A';
	}

	if ( 'z' === $date_format ) {
		return date_i18n( $date_format ) + 1;
	} else {
		return date_i18n( $date_format );
	}

}
