<?php
/**
 * Add Jupiter settings for Footer > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

$section = 'jupiterx_portfolio_archive_settings';

// Template.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-template',
	'settings'        => 'jupiterx_portfolio_archive_template',
	'section'         => $section,
	'label'           => __( 'My Templates', 'jupiterx-core' ),
	'placeholder'     => __( 'Select one', 'jupiterx-core' ),
	'template_type'   => 'archive',
	'locked'          => true,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_portfolio_archive',
	'section'   => $section,
	'css_var'   => 'portfolio-archive',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.archive.post-type-archive-portfolio .jupiterx-main-content, .archive.tax-portfolio_category .jupiterx-main-content, .archive.tax-portfolio_tag .jupiterx-main-content',
		],
	],
] );
