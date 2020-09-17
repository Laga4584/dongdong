<?php
/**
 * Add Jupiter settings for Footer > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_blog_archive_settings';

// Template.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-template',
	'settings'        => 'jupiterx_post_archive_template',
	'section'         => $section,
	'label'           => __( 'My Templates', 'jupiterx-core' ),
	'placeholder'     => __( 'Select one', 'jupiterx-core' ),
	'template_type'   => 'archive',
	'locked'          => true,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_blog_archive',
	'section'   => $section,
	'css_var'   => 'blog-archive',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.archive.date .jupiterx-main-content, .archive.author .jupiterx-main-content, .archive.category .jupiterx-main-content, .archive.tag .jupiterx-main-content',
		],
	],
] );
