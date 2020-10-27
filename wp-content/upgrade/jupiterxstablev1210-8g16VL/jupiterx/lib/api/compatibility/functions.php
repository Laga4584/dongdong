<?php
/**
 * Functions for updating theme version.
 *
 * @package JupiterX\Framework\API\Compatibility
 *
 * @since 1.0.0
 */

/**
 * Version updates.
 *
 * @since 1.0.2
 *
 * @return void
 */
function jupiterx_update_v102() {
	if ( is_null( get_option( 'jupiterx_setup_wizard_hide_notice', null ) ) ) {
		update_option( 'jupiterx_setup_wizard_hide_notice', true );
	}
}

/**
 * Version updates.
 *
 * @since 1.3.0
 *
 * @return void
 */
function jupiterx_update_v130() {
	set_site_transient( 'jupiterx_update_plugins_notice', 'yes' );
}

/**
 * Version updates.
 *
 * @since 1.11.0
 *
 * @return void
 */
function jupiterx_update_v1110() {
	$options = [
		'artbees_api_key',
		'jupiterx_adobe_fonts_project_id',
		'jupiterx_svg_support',
		'jupiterx_setup_wizard_current_page',
		'jupiterx_setup_wizard_hide_notice',
		'jupiterx_template_installed',
		'jupiterx_template_installed_id',
		'jupiterx_dev_mode',
		'jupiterx_unboarding_hide_popup',
		'jupiterx_post_types',
		'jupiterx_custom_sidebars',
		'jupiterx_tracking_codes_after_head',
		'jupiterx_tracking_codes_before_head',
		'jupiterx_tracking_codes_after_body',
		'jupiterx_tracking_codes_before_body',
		'jupiterx_cache_busting',
		'jupiterx_google_analytics_id',
		'jupiterx_google_analytics_anonymization',
		'jupiterx_donut_twitter_consumer_key',
		'jupiterx_donut_twitter_consumer_secret',
		'jupiterx_donut_twitter_access_token',
		'jupiterx_donut_twitter_access_token_secret',
		'jupiterx_donut_mailchimp_api_key',
		'jupiterx_donut_mailchimp_list_id',
		'jupiterx_donut_google_maps_api_key',
	];

	foreach ( $options as $option ) {
		$name = preg_replace( '/(jupiterx|artbees)_/', '', $option, 1 );

		// Only save option that has a saved value.
		$value = get_option( $option, null );
		if ( ! is_null( $value ) ) {
			jupiterx_update_option( $name, $value );
		}
	}
}
