<?php
/**
 * Add WPML Compatibility Module.
 *
 * @package Raven
 * @since 1.0.4
 */

namespace Raven\Core\Compatibility\Wpml;

defined( 'ABSPATH' ) || die();

/**
 * Raven WPML compatibility module.
 *
 * Raven compatibility module handler class is responsible for registering and
 * managing translatable fields with WPML plugin.
 *
 * @since 1.0.4
 */
class Module {

	/**
	 * Constructor.
	 *
	 * @since 1.0.4
	 */
	public function __construct() {
		add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'register_widgets_fields' ] );
	}

	/**
	 * Register widgets fields for translation.
	 *
	 * @since 1.0.4
	 *
	 * @param array $fields Fields to translate.
	 *
	 * @return array
	 */
	public function register_widgets_fields( $fields ) {

		// Alert.
		$fields['raven-alert'] = [
			'conditions' => [ 'widgetType' => 'raven-alert' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Raven Alert: Title', 'raven' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description',
					'type'        => esc_html__( 'Raven Alert: Content', 'raven' ),
					'editor_type' => 'VISUAL',
				],
			],
		];

		// Button.
		$fields['raven-button'] = [
			'conditions' => [ 'widgetType' => 'raven-button' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Raven Button: Text', 'raven' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Button: Link', 'raven' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Categories.
		$fields['raven-categories'] = [
			'conditions' => [ 'widgetType' => 'raven-categories' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Raven Categories: Text', 'raven' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Categories: Link', 'raven' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Counter.
		$fields['raven-counter'] = [
			'conditions' => [ 'widgetType' => 'raven-counter' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\Modules\Counter',
		];

		// Form.
		$fields['raven-form'] = [
			'conditions' => [ 'widgetType' => 'raven-form' ],
			'fields'     => [
				[
					'field'       => 'form_name',
					'type'        => esc_html__( 'Raven Form: Form name', 'raven' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'submit_button_text',
					'type'        => esc_html__( 'Raven Form: Submit button Text', 'raven' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messages_success',
					'type'        => esc_html__( 'Raven Form: Success message', 'raven' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messages_error',
					'type'        => esc_html__( 'Raven Form: Error message', 'raven' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messages_required',
					'type'        => esc_html__( 'Raven Form: Required message', 'raven' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messages_subscriber',
					'type'        => esc_html__( 'Raven Form: Subscriber already exists message', 'raven' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\Modules\Form',
		];

		// Heading.
		$fields['raven-heading'] = [
			'conditions' => [ 'widgetType' => 'raven-heading' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Raven Heading: Title', 'raven' ),
					'editor_type' => 'AREA',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Heading: Link', 'raven' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Icon.
		$fields['raven-icon'] = [
			'conditions' => [ 'widgetType' => 'raven-icon' ],
			'fields'     => [
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Icon: Link', 'raven' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Image.
		$fields['raven-image'] = [
			'conditions' => [ 'widgetType' => 'raven-image' ],
			'fields'     => [
				[
					'field'       => 'caption',
					'type'        => esc_html__( 'Raven Image: Caption', 'raven' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Image: Link', 'raven' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Photo Album.
		$fields['raven-photo-album'] = [
			'conditions' => [ 'widgetType' => 'raven-photo-album' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\Modules\Photo_Album',
		];

		// Search Form.
		$fields['raven-search-form'] = [
			'conditions' => [ 'widgetType' => 'raven-search-form' ],
			'fields'     => [
				[
					'field'       => 'placeholder',
					'type'        => esc_html__( 'Raven Search Form: Placeholder', 'raven' ),
					'editor_type' => 'LINE',
				],
			],
		];

		// Site Logo.
		$fields['raven-site-logo'] = [
			'conditions' => [ 'widgetType' => 'raven-site-logo' ],
			'fields'     => [
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Site Logo: Link', 'raven' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Tabs.
		$fields['raven-tabs'] = [
			'conditions' => [ 'widgetType' => 'raven-tabs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\Modules\Tabs',
		];

		// Video.
		$fields['raven-video'] = [
			'conditions' => [ 'widgetType' => 'raven-video' ],
			'fields'     => [
				'youtube_link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Video: YouTube link', 'raven' ),
					'editor_type' => 'LINK',
				],
				'vimeo_link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Video: Vimeo link', 'raven' ),
					'editor_type' => 'LINK',
				],
				'hosted_link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Video: Hosted video - MP4', 'raven' ),
					'editor_type' => 'LINK',
				],
				'hosted_link_webm' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Video: Hosted video - WebM', 'raven' ),
					'editor_type' => 'LINK',
				],
			],
		];

		return $fields;
	}
}
