<?php
namespace Raven\Modules\Post_Meta\Widgets;

use Raven\Base\Base_Widget;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Temporary supressed.
 *
 * @SuppressWarnings(ExcessiveClassLength)
 * @SuppressWarnings(ExcessiveClassComplexity)
 */
class Post_Meta extends Base_Widget {

	public function get_name() {
		return 'raven-post-meta';
	}

	public function get_title() {
		return __( 'Post Meta', 'raven' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-post-meta';
	}

	public function get_keywords() {
		return [ 'post', 'info', 'meta', 'date', 'time', 'author', 'taxonomy', 'comments', 'terms', 'avatar' ];
	}

	protected function _register_controls() {
		$this->register_settings_section();
		$this->register_list_section();
		$this->register_icon_section();
		$this->register_text_section();
	}

	/**
	 * Temporary supressed.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_settings_section() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Settings', 'raven' ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'Layout', 'raven' ),
				'type' => 'choose',
				'default' => 'inline',
				'options' => [
					'traditional' => [
						'title' => __( 'Default', 'raven' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => __( 'Inline', 'raven' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'render_type' => 'template',
				'classes' => 'elementor-control-start-end',
				'label_block' => false,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			[
				'label' => __( 'Type', 'raven' ),
				'type' => 'select',
				'default' => 'date',
				'options' => [
					'author' => __( 'Author', 'raven' ),
					'date' => __( 'Date', 'raven' ),
					'time' => __( 'Time', 'raven' ),
					'comments' => __( 'Comments', 'raven' ),
					'terms' => __( 'Terms', 'raven' ),
					'custom' => __( 'Custom', 'raven' ),
				],
			]
		);

		$repeater->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'raven' ),
				'type' => 'select',
				'label_block' => false,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => _x( 'July 12, 2019 (F j, Y)', 'Date Format', 'raven' ),
					'1' => '2019-07-12 (Y-m-d)',
					'2' => '07/12/2019 (m/d/Y)',
					'3' => '12/07/2019 (d/m/Y)',
					'custom' => __( 'Custom', 'raven' ),
				],
				'condition' => [
					'type' => 'date',
				],
			]
		);

		$repeater->add_control(
			'custom_date_format',
			[
				'label' => __( 'Custom Date Format', 'raven' ),
				'type' => 'text',
				'default' => 'F j, Y',
				'label_block' => false,
				'condition' => [
					'type' => 'date',
					'date_format' => 'custom',
				],
				'description' => sprintf(
					/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
					__( 'Use the letters: %s', 'raven' ),
					'l D d j S F m M n Y y'
				),
			]
		);

		$repeater->add_control(
			'time_format',
			[
				'label' => __( 'Time Format', 'raven' ),
				'type' => 'select',
				'label_block' => false,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => '10:29 pm (g:i a)',
					'1' => '10:29 PM (g:i A)',
					'2' => '22:29 (H:i)',
					'custom' => __( 'Custom', 'raven' ),
				],
				'condition' => [
					'type' => 'time',
				],
			]
		);
		$repeater->add_control(
			'custom_time_format',
			[
				'label' => __( 'Custom Time Format', 'raven' ),
				'type' => 'text',
				'default' => 'g:i a',
				'placeholder' => 'g:i a',
				'label_block' => false,
				'condition' => [
					'type' => 'time',
					'time_format' => 'custom',
				],
				'description' => sprintf(
					/* translators: %s: Allowed time letters (see: http://php.net/manual/en/function.time.php). */
					__( 'Use the letters: %s', 'raven' ),
					'g G H i a A'
				),
			]
		);

		$repeater->add_control(
			'taxonomy',
			[
				'label' => __( 'Taxonomy', 'raven' ),
				'type' => 'select2',
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),
				'condition' => [
					'type' => 'terms',
				],
			]
		);

		$repeater->add_control(
			'text_prefix',
			[
				'label' => __( 'Before', 'raven' ),
				'type' => 'text',
				'label_block' => false,
				'condition' => [
					'type!' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'show_avatar',
			[
				'label' => __( 'Avatar', 'raven' ),
				'type' => 'switcher',
				'condition' => [
					'type' => 'author',
				],
			]
		);

		$repeater->add_responsive_control(
			'avatar_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .raven-icon-list-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_avatar' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'comments_custom_strings',
			[
				'label' => __( 'Custom Format', 'raven' ),
				'type' => 'switcher',
				'default' => false,
				'condition' => [
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_no_comments',
			[
				'label' => __( 'No Comments', 'raven' ),
				'type' => 'text',
				'label_block' => false,
				'placeholder' => __( 'No Comments', 'raven' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_one_comment',
			[
				'label' => __( 'One Comment', 'raven' ),
				'type' => 'text',
				'label_block' => false,
				'placeholder' => __( 'One Comment', 'raven' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_comments',
			[
				'label' => __( 'Comments', 'raven' ),
				'type' => 'text',
				'label_block' => false,
				/* translators: %s: No. of comments. */
				'placeholder' => __( '%s Comments', 'raven' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'custom_text',
			[
				'label' => __( 'Custom', 'raven' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'condition' => [
					'type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'raven' ),
				'type' => 'switcher',
				'default' => 'yes',
				'condition' => [
					'type!' => 'time',
				],
			]
		);

		$repeater->add_control(
			'custom_url',
			[
				'label' => __( 'Custom URL', 'raven' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'show_icon',
			[
				'label' => __( 'Icon', 'raven' ),
				'type' => 'select',
				'options' => [
					'none' => __( 'None', 'raven' ),
					'default' => __( 'Default', 'raven' ),
					'custom' => __( 'Custom', 'raven' ),
				],
				'default' => 'default',
				'condition' => [
					'show_avatar!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'icon_new',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => 'icons',
				'fa4compatibility' => 'icon',
				'label_block' => true,
				'condition' => [
					'show_icon' => 'custom',
					'show_avatar!' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_list',
			[
				'label' => '',
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'type' => 'author',
						'icon_new' => [
							'value' => 'far fa-user-circle',
							'library' => 'fa-regular',
						],
					],
					[

						'type' => 'date',
						'icon_new' => [
							'value' => 'fas fa-calendar-alt',
							'library' => 'fa-solid',
						],
					],
					[
						'type' => 'time',
						'icon_new' => [
							'value' => 'far fa-clock',
							'library' => 'fa-regular',
						],
					],
					[
						'type' => 'comments',
						'icon_new' => [
							'value' => 'far fa-comment-dots',
							'library' => 'fa-regular',
						],
					],
				],
				'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, fallbackControl = ( "undefined" === typeof icon ) ? false : icon; #> {{{ (fallbackControl && !migrated) ? "<i class=\"" + fallbackControl + "\"></i>" : elementor.helpers.renderIcon(null, icon_new, {}, "i", "panel") }}} <span style="text-transform: capitalize;">{{{ type }}}</span>',
			]
		);

		$this->end_controls_section();
	}

	protected function register_list_section() {
		$this->start_controls_section(
			'section_icon_list',
			[
				'label' => __( 'List', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'raven' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-items:not(.raven-inline-items) .raven-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .raven-icon-list-items:not(.raven-inline-items) .raven-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .raven-icon-list-items.raven-inline-items .raven-icon-list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .raven-icon-list-items.raven-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body.rtl {{WRAPPER}} .raven-icon-list-items.raven-inline-items .raven-icon-list-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}} .raven-icon-list-items.raven-inline-items .raven-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'raven' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => __( 'Start', 'raven' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'raven' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'End', 'raven' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
			]
		);

		$this->add_control(
			'divider',
			[
				'label' => __( 'Divider', 'raven' ),
				'type' => 'switcher',
				'label_off' => __( 'Off', 'raven' ),
				'label_on' => __( 'On', 'raven' ),
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-item:not(:last-child):after' => 'content: ""',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'raven' ),
				'type' => 'select',
				'options' => [
					'solid' => __( 'Solid', 'raven' ),
					'double' => __( 'Double', 'raven' ),
					'dotted' => __( 'Dotted', 'raven' ),
					'dashed' => __( 'Dashed', 'raven' ),
				],
				'default' => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-items:not(.raven-inline-items) .raven-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} .raven-icon-list-items.raven-inline-items .raven-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'raven' ),
				'type' => 'slider',
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-items:not(.raven-inline-items) .raven-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-inline-items .raven-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Width', 'raven' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view!' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label' => __( 'Height', 'raven' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'default' => '#ddd',
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_icon_section() {
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'raven' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-icon i' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'raven' ),
				'type' => 'slider',
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_text_section() {
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __( 'Text', 'raven' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'text_indent',
			[
				'label' => __( 'Indent', 'raven' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .raven-icon-list-text' => 'padding-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .raven-icon-list-text' => 'padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'raven' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-text, {{WRAPPER}} .raven-icon-list-text a' => 'color: {{VALUE}}',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .raven-icon-list-item',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [
			'show_in_nav_menus' => true,
		], 'objects' );

		$options = [
			'' => __( 'Choose', 'raven' ),
		];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_author_meta_data( $repeater_item ) {
		$item_data = [];
		$author_id = get_post_field( 'post_author' );

		$item_data['text']     = get_the_author_meta( 'display_name', $author_id );
		$item_data['icon_new'] = [
			'value' => 'fas fa-user-circle',
			'library' => 'fa-solid',
		];
		$item_data['itemprop'] = 'author';

		if ( 'yes' === $repeater_item['link'] ) {
			$item_data['url'] = [
				'url' => get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ),
			];
		}

		if ( 'yes' === $repeater_item['show_avatar'] ) {
			$item_data['image'] = get_avatar_url( get_the_author_meta( 'ID', $author_id ), 96 );
		}

		return $item_data;
	}

	protected function get_date_meta_data( $repeater_item ) {
		$item_data = [];

		$custom_date_format = empty( $repeater_item['custom_date_format'] ) ? 'F j, Y' : $repeater_item['custom_date_format'];

		$format_options = [
			'default' => 'F j, Y',
			'0'       => 'F j, Y',
			'1'       => 'Y-m-d',
			'2'       => 'm/d/Y',
			'3'       => 'd/m/Y',
			'custom'  => $custom_date_format,
		];

		$item_data['text']     = get_the_time( $format_options[ $repeater_item['date_format'] ] );
		$item_data['icon_new'] = [
			'value' => 'fas fa-calendar',
			'library' => 'fa-solid',
		];
		$item_data['itemprop'] = 'datePublished';

		if ( 'yes' === $repeater_item['link'] ) {
			$item_data['url'] = [
				'url' => get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ),
			];
		}

		return $item_data;
	}

	protected function get_time_meta_data( $repeater_item ) {
		$item_data = [];

		$custom_time_format = 'g:i a';

		if ( ! empty( $repeater_item['custom_time_format'] ) ) {
			$custom_time_format = $repeater_item['custom_time_format'];
		}

		$format_options = [
			'default' => 'g:i a',
			'0' => 'g:i a',
			'1' => 'g:i A',
			'2' => 'H:i',
			'custom' => $custom_time_format,
		];

		$item_data['text']     = get_the_time( $format_options[ $repeater_item['time_format'] ] );
		$item_data['icon_new'] = [
			'value' => 'fas fa-clock',
			'library' => 'fa-solid',
		];

		return $item_data;
	}

	/**
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function get_comments_meta_data( $repeater_item ) {
		$item_data = [];

		if ( function_exists( 'jupiterx_post_element_enabled' ) && ! jupiterx_post_element_enabled( 'comments' ) ) {
			return $item_data;
		}

		if ( ! ( comments_open() || get_comments_number() ) ) {
			return $item_data;
		}

		$default_strings = [
			'string_no_comments' => __( 'No Comments', 'raven' ),
			'string_one_comment' => __( 'One Comment', 'raven' ),
			/* translators: %s: Comment count. */
			'string_comments'    => __( '%s Comments', 'raven' ),
		];

		if ( 'yes' === $repeater_item['comments_custom_strings'] ) {
			if ( ! empty( $repeater_item['string_no_comments'] ) ) {
				$default_strings['string_no_comments'] = $repeater_item['string_no_comments'];
			}

			if ( ! empty( $repeater_item['string_one_comment'] ) ) {
				$default_strings['string_one_comment'] = $repeater_item['string_one_comment'];
			}

			if ( ! empty( $repeater_item['string_comments'] ) ) {
				$default_strings['string_comments'] = $repeater_item['string_comments'];
			}
		}

		$num_comments = intval( get_comments_number() );

		if ( 0 === $num_comments ) {
			$item_data['text'] = $default_strings['string_no_comments'];
		} else {
			$item_data['text'] = sprintf( _n( $default_strings['string_one_comment'], $default_strings['string_comments'], $num_comments, 'raven' ), $num_comments ); // phpcs:ignore WordPress.WP.I18n
		}

		if ( 'yes' === $repeater_item['link'] ) {
			$item_data['url'] = [
				'url' => get_comments_link(),
			];
		}

		$item_data['icon_new'] = [
			'value' => 'fas fa-comment-dots',
			'library' => 'fa-solid',
		];
		$item_data['itemprop'] = 'commentCount';

		return $item_data;
	}

	protected function get_terms_meta_data( $repeater_item ) {
		$item_data = [];

		$item_data['icon_new'] = [
			'value' => 'fas fa-tags',
			'library' => 'fa-solid',
		];
		$item_data['itemprop'] = 'about';

		$taxonomy = $repeater_item['taxonomy'];
		$terms    = wp_get_post_terms( get_the_ID(), $taxonomy );
		foreach ( $terms as $term ) {
			$item_data['terms_list'][ $term->term_id ]['text'] = $term->name;
			if ( 'yes' === $repeater_item['link'] ) {
				$item_data['terms_list'][ $term->term_id ]['url'] = get_term_link( $term );
			}
		}

		return $item_data;
	}

	protected function get_custom_meta_data( $repeater_item ) {
		$item_data = [];

		$item_data['text']     = $repeater_item['custom_text'];
		$item_data['icon_new'] = [
			'value' => 'fas fa-info-circle',
			'library' => 'fa-solid',
		];

		if ( 'yes' === $repeater_item['link'] && ! empty( $repeater_item['custom_url'] ) ) {
			$item_data['url'] = $repeater_item['custom_url'];
		}

		return $item_data;
	}

	protected function get_meta_data( $repeater_item ) {
		$item_data = [];

		switch ( $repeater_item['type'] ) {
			case 'author':
				$item_data = $this->get_author_meta_data( $repeater_item );
				break;

			case 'date':
				$item_data = $this->get_date_meta_data( $repeater_item );
				break;

			case 'time':
				$item_data = $this->get_time_meta_data( $repeater_item );
				break;

			case 'comments':
				$item_data = $this->get_comments_meta_data( $repeater_item );
				break;

			case 'terms':
				$item_data = $this->get_terms_meta_data( $repeater_item );
				break;

			case 'custom':
				$item_data = $this->get_custom_meta_data( $repeater_item );
				break;
		}

		$item_data['type'] = $repeater_item['type'];

		if ( ! empty( $repeater_item['text_prefix'] ) ) {
			$item_data['text_prefix'] = esc_html( $repeater_item['text_prefix'] );
		}

		return $item_data;
	}

	/**
	 * Temporary supressed.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render_item( $repeater_item ) {
		$item_data      = $this->get_meta_data( $repeater_item );
		$repeater_index = $repeater_item['_id'];

		if ( empty( $item_data['text'] ) && empty( $item_data['terms_list'] ) ) {
			return;
		}

		$has_link = false;
		$link_key = 'link_' . $repeater_index;
		$item_key = 'item_' . $repeater_index;

		$this->add_render_attribute( $item_key, 'class',
			[
				'raven-icon-list-item',
				'elementor-repeater-item-' . $repeater_item['_id'],
			]
		);

		$active_settings = $this->get_active_settings();

		if ( 'inline' === $active_settings['view'] ) {
			$this->add_render_attribute( $item_key, 'class', 'raven-inline-item' );
		}

		if ( ! empty( $item_data['url']['url'] ) ) {
			$has_link = true;

			$url = $item_data['url'];
			$this->add_render_attribute( $link_key, 'href', $url['url'] );

			if ( ! empty( $url['is_external'] ) ) {
				$this->add_render_attribute( $link_key, 'target', '_blank' );
			}

			if ( ! empty( $url['nofollow'] ) ) {
				$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
			}
		}

		if ( ! empty( $item_data['itemprop'] ) ) {
			$this->add_render_attribute( $item_key, 'itemprop', $item_data['itemprop'] );
		}

		?>
		<li <?php echo $this->get_render_attribute_string( $item_key ); ?>>
			<?php if ( $has_link ) : ?>
			<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
				<?php endif; ?>
				<?php $this->render_item_icon_or_image( $item_data, $repeater_item, $repeater_index ); ?>
				<?php $this->render_item_text( $item_data, $repeater_index ); ?>
				<?php if ( $has_link ) : ?>
			</a>
		<?php endif; ?>
		</li>
		<?php
	}

	/**
	 * Render icon or image markups.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	protected function render_item_icon_or_image( $item_data, $repeater_item, $repeater_index ) {

		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $repeater_item['__fa4_migrated']['icon_new'] );
		$is_new            = empty( $repeater_item['icon'] ) && $migration_allowed;

		if ( 'none' === $repeater_item['show_icon'] && empty( $item_data['image'] ) ) {
			return;
		}

		if ( 'default' === $repeater_item['show_icon'] ) {
			$repeater_item['icon_new'] = $is_new ? $item_data['icon_new'] : ( isset( $item_data['icon'] ) ? $item_data['icon'] : '' );
		}

		?>
		<span class="raven-icon-list-icon">
			<?php
			if ( ! empty( $item_data['image'] ) ) :
				$image_data = 'image_' . $repeater_index;
				$this->add_render_attribute( $image_data, 'src', $item_data['image'] );
				$this->add_render_attribute( $image_data, 'alt', $item_data['text'] );
				?>
				<img class="raven-avatar" <?php echo $this->get_render_attribute_string( $image_data ); ?>>
			<?php else : ?>
				<?php
				if ( $is_new || $migrated ) {
					Elementor::$instance->icons_manager->render_icon( $repeater_item['icon_new'], [ 'aria-hidden' => 'true' ] );
				} else {
					?>
					<i class="<?php echo esc_attr( $repeater_item['icon'] ); ?>"></i>
				<?php } ?>
			<?php endif; ?>
		</span>
		<?php
	}

	protected function render_item_text( $item_data, $repeater_index ) {
		$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $repeater_index );

		$this->add_render_attribute(
			$repeater_setting_key,
			'class',
			[
				'raven-icon-list-text',
				'raven-post-meta-item',
				'raven-post-meta-item-type-' . $item_data['type'],
			]
		);

		if ( ! empty( $item_data['terms_list'] ) ) {
			$this->add_render_attribute( $repeater_setting_key, 'class', 'raven-terms-list' );
		}

		?>
		<span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>>
			<?php if ( ! empty( $item_data['text_prefix'] ) ) : ?>
				<span class="raven-post-meta-item-prefix"><?php echo esc_html( $item_data['text_prefix'] ); ?></span>
			<?php endif; ?>
			<?php
			if ( ! empty( $item_data['terms_list'] ) ) :
				$terms_list = [];
				$item_class = 'raven-post-meta-terms-list-item';
				?>
				<span class="raven-post-meta-terms-list">
				<?php
				foreach ( $item_data['terms_list'] as $term ) :
					if ( ! empty( $term['url'] ) ) :
						$terms_list[] = '<a href="' . esc_attr( $term['url'] ) . '" class="' . $item_class . '">' . esc_html( $term['text'] ) . '</a>';
					else :
						$terms_list[] = '<span class="' . $item_class . '">' . esc_html( $term['text'] ) . '</span>';
					endif;
				endforeach;

				echo implode( ', ', $terms_list );
				?>
				</span>
			<?php else : ?>
				<?php
				echo wp_kses( $item_data['text'], [
					'a' => [
						'href' => [],
						'title' => [],
						'rel' => [],
					],
				] );
				?>
			<?php endif; ?>
		</span>
		<?php
	}

	protected function render() {
		// Load both f4 & f5 for backward compatibility.
		Elementor::$instance->icons_manager->enqueue_shim();

		$settings = $this->get_settings_for_display();

		ob_start();

		if ( ! empty( $settings['icon_list'] ) ) {
			foreach ( $settings['icon_list'] as $repeater_item ) {
				$this->render_item( $repeater_item );
			}
		}

		$items_markup = ob_get_clean();

		if ( empty( $items_markup ) ) {
			return;
		}

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute(
				'icon_list',
				'class',
				'raven-inline-items'
			);
		}

		$this->add_render_attribute(
			'icon_list',
			'class',
			[ 'raven-icon-list-items', 'raven-post-meta' ]
		);
		?>
		<ul <?php echo $this->get_render_attribute_string( 'icon_list' ); ?>>
			<?php echo $items_markup; ?>
		</ul>
		<?php
	}
}
