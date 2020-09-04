<?php
/**
 * Class: Jet_Reviews_Simple
 * Name: Jet Reviews Simple
 * Slug: jet-reviews-simple
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Reviews_Advanced extends Jet_Reviews_Base {

	public function get_name() {
		return 'jet-reviews-advanced';
	}

	public function get_title() {
		return esc_html__( 'Reviews Listing', 'jet-reviews' );
	}

	public function get_icon() {
		return 'eicon-favorite';
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/article-category/jetreviews/?utm_source=jetreviews&utm_medium=jet-reviews-advanced&utm_campaign=need-help';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-reviews/advanced-widget/css-scheme',
			array(
				'header'       => '.jet-reviews-advanced__header',
				'reviews'      => '.jet-reviews-advanced__reviews',
				'review'       => '.jet-reviews-advanced__review',
				'comments'      => '.jet-reviews-advanced__review-comments',
				'comment'      => '.jet-reviews-advanced__review-comment',
				'control'      => '.jet-reviews-button',
				'rating-field' => '.jet-reviews-field',
				'stars-field'  => '.jet-reviews-stars-field',
				'form-input'   => '.jet-reviews-input',
			)
		);

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'rating_layout',
			array(
				'label'   => esc_html__( 'Rating Layout', 'jet-reviews' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'stars-field',
				'options' => array(
					'stars-field'  => esc_html__( 'Stars', 'jet-reviews' ),
					'points-field' => esc_html__( 'Points', 'jet-reviews' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icons',
			array(
				'label' => esc_html__( 'Icons', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'empty_star_icon',
			array(
				'label'            => __( 'Empty Star Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'far fa-star',
					'library' => 'fa-regular',
				),
				'condition' => array(
					'rating_layout' => 'stars-field',
				),
			)
		);

		$this->add_control(
			'filled_star_icon',
			array(
				'label'            => __( 'Filled Star Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'rating_layout' => 'stars-field',
				),
			)
		);

		$this->add_control(
			'new_review_button_icon',
			array(
				'label'            => __( 'New Review Button Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-pen',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'show_comments_button_icon',
			array(
				'label'            => __( 'Show Comments Button Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-comment',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'new_comment_button_icon',
			array(
				'label'            => __( 'New Comment Button Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-pen',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'pinned_icon',
			array(
				'label'            => __( 'Pinned Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-thumbtack',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'review_empty_like_icon',
			array(
				'label'            => __( 'Empty Like Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'far fa-thumbs-up',
					'library' => 'fa-regular',
				),
			)
		);

		$this->add_control(
			'review_filled_like_icon',
			array(
				'label'            => __( 'Filled Like Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-thumbs-up',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'review_empty_dislike_icon',
			array(
				'label'            => __( 'Empty Dislike Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'far fa-thumbs-down',
					'library' => 'fa-regular',
				),
			)
		);

		$this->add_control(
			'review_filled_dislike_icon',
			array(
				'label'            => __( 'Filled Dislike Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-thumbs-down',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'reply_button_icon',
			array(
				'label'            => __( 'Reply Button Icon', 'jet-reviews' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-reply',
					'library' => 'fa-solid',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_labels',
			array(
				'label' => esc_html__( 'Labels', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'no_reviews_label',
			array(
				'label'       => esc_html__( 'No Reviews Label', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'No reviews found', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'new_review_button_label',
			array(
				'label'       => esc_html__( 'New Review Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Write a review', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'review_content_placeholder',
			array(
				'label'       => esc_html__( 'Review Content Placeholder', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Your review', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'review_title_placeholder',
			array(
				'label'       => esc_html__( 'Review Title Placeholder', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Title of your review', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'submit_review_button_label',
			array(
				'label'       => esc_html__( 'Submit Review Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Submit a review', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'cancel_button_label',
			array(
				'label'       => esc_html__( 'Cancel Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Cancel', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'new_comment_button_label',
			array(
				'label'       => esc_html__( 'New Comment Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Leave a comment', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'comment_placeholder',
			array(
				'label'       => esc_html__( 'Comments Placeholder', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Leave your comments', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'show_comments_button_label',
			array(
				'label'       => esc_html__( 'Show Comments Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Show Comments', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'hide_comments_button_label',
			array(
				'label'       => esc_html__( 'Hide Comments Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Hide Comments', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'comments_title_label',
			array(
				'label'       => esc_html__( 'Comments Title', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Comments', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'submit_comment_button_label',
			array(
				'label'       => esc_html__( 'Submit Comment Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Submit Comment', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'reply_comment_button_label',
			array(
				'label'       => esc_html__( 'Reply Comment Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Reply', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'reply_placeholder',
			array(
				'label'       => esc_html__( 'Reply Placeholder', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Leave you reply', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'submit_reply_comment_button_label',
			array(
				'label'       => esc_html__( 'Submit Reply Button', 'jet-reviews' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Submit a reply', 'jet-reviews' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rating_style',
			array(
				'label'      => esc_html__( 'Rating', 'jet-reviews' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'rating_star_size',
			array(
				'label'   => esc_html__( 'Star Size', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['stars-field'] . ' .jet-reviews-stars i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rating_layout' => 'stars-field',
				),
			)
		);

		$this->add_responsive_control(
			'rating_star_gap',
			array(
				'label'   => esc_html__( 'Star Gap', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['stars-field'] . ' .jet-reviews-stars i' => 'padding: 0 {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rating_layout' => 'stars-field',
				),
			)
		);

		$this->add_responsive_control(
			'rating_bar_height',
			array(
				'label'   => esc_html__( 'Bar Height', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . ' .jet-reviews-points-field__adjuster' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rating_layout' => 'points-field',
				),
			)
		);

		$this->add_control(
			'rating_empty_color',
			array(
				'label'  => esc_html__( 'Empty Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#bec0c1',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . ' .jet-reviews-stars-field__empty i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . ' .jet-reviews-points-field__empty' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rating_very_low_color',
			array(
				'label'  => esc_html__( 'Very Low Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#C92C2C',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.very-low-rating .jet-reviews-stars--filled i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.very-low-rating .jet-reviews-points-field__filled' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rating_low_color',
			array(
				'label'  => esc_html__( 'Low Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#E36F04',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.low-rating .jet-reviews-stars--filled i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.low-rating .jet-reviews-points-field__filled' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rating_medium_color',
			array(
				'label'  => esc_html__( 'Medium Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#E3C004',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.medium-rating .jet-reviews-stars--filled i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.medium-rating .jet-reviews-points-field__filled' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rating_high_color',
			array(
				'label'  => esc_html__( 'High Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#A9E304',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.high-rating .jet-reviews-stars--filled i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.high-rating .jet-reviews-points-field__filled' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rating_very_high_color',
			array(
				'label'  => esc_html__( 'Very High Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#46B450',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.very-high-rating .jet-reviews-stars--filled i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . '.very-high-rating .jet-reviews-points-field__filled' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rating_label_color',
			array(
				'label'  => esc_html__( 'Label Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating-field'] . ' .jet-reviews-field__label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Label Typography', 'jet-reviews' ),
				'name'     => 'rating_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} ' . $css_scheme['rating-field'] . ' .jet-reviews-field__label',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_controls_style',
			array(
				'label'      => esc_html__( 'Controls', 'jet-reviews' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'control_icon_size',
			array(
				'label'   => esc_html__( 'Icon Size', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-reviews-button__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-reviews-button__icon svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Label Typography', 'jet-reviews' ),
				'name'     => 'control_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-reviews-button__text',
			)
		);

		$this->start_controls_tabs( 'tabs_controls' );

		$this->start_controls_tab(
			'tab_controls_primary',
			array(
				'label' => __( 'Primary', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'primary_control_icon_color',
			array(
				'label'  => esc_html__( 'Icon Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--primary .jet-reviews-button__icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--primary .jet-reviews-button__icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'primary_control_label_color',
			array(
				'label'  => esc_html__( 'Label Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--primary .jet-reviews-button__text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'primary_control_border',
				'label'          => esc_html__( 'Border', 'jet-reviews' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--primary',
			)
		);

		$this->add_responsive_control(
			'primary_control_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'primary_control_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_controls_secondary',
			array(
				'label' => __( 'Secondary', 'jet-reviews' ),
			)
		);

		$this->add_control(
			'secondary_control_icon_color',
			array(
				'label'  => esc_html__( 'Icon Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--secondary .jet-reviews-button__icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--secondary .jet-reviews-button__icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'secondary_control_label_color',
			array(
				'label'  => esc_html__( 'Label Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--secondary .jet-reviews-button__text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'secondary_control_border',
				'label'          => esc_html__( 'Border', 'jet-reviews' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--secondary',
			)
		);

		$this->add_responsive_control(
			'secondary_control_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'secondary_control_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.jet-reviews-button--secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_forms_style',
			array(
				'label'      => esc_html__( 'Forms', 'jet-reviews' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'form_inputs_style',
			array(
				'label'     => esc_html__( 'Inputs', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'form_input_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form-input'] => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['form-input'] . '::placeholder' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_input_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form-input'] . ', {{WRAPPER}} .jet-reviews-range-input span',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'form_input_border',
				'label'          => esc_html__( 'Border', 'jet-reviews' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['form-input'],
			)
		);

		$this->add_responsive_control(
			'form_input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form-input'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form-input'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_slider_style',
			array(
				'label'     => esc_html__( 'Slider', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'field_slider_color',
			array(
				'label'  => esc_html__( 'Slider Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-reviews-range-input input[type=range]::-webkit-slider-runnable-track' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-reviews-range-input input[type=range]::-moz-range-track'              => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-reviews-range-input input[type=range]::-webkit-slider-thumb'          => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-reviews-range-input input[type=range]::-moz-range-thumb'              => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-reviews-range-input span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			array(
				'label'      => esc_html__( 'Header', 'jet-reviews' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'header_title_color',
			array(
				'label'  => esc_html__( 'Title Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['header'] . ' .jet-reviews-advanced__header-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'  => esc_html__( 'Title Typography', 'jet-reviews' ),
				'name'     => 'header_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['header'] . ' .jet-reviews-advanced__header-title',
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['header'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'header_border',
				'label'          => esc_html__( 'Border', 'jet-reviews' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['header'],
			)
		);

		$this->add_responsive_control(
			'header_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['header'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'header_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['header'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_review_style',
			array(
				'label'      => esc_html__( 'Reviews', 'jet-reviews' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'review_author_heading',
			array(
				'label'     => esc_html__( 'Author', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'review_author_avatar_size',
			array(
				'label'   => esc_html__( 'Avatar Size', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 16,
						'max' => 64,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__avatar' => 'min-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'review_author_avatar_space',
			array(
				'label' => __( 'Avatar Spacing', 'jet-reviews' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__avatar' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'review_author_name_color',
			array(
				'label'  => esc_html__( 'Author Name Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__name > span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Author Name Typography', 'jet-reviews' ),
				'name'     => 'review_author_name_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__name > span',
			)
		);

		$this->add_control(
			'review_publish_time_color',
			array(
				'label'  => esc_html__( 'Publish Time Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__name time' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Publish Time Typography', 'jet-reviews' ),
				'name'     => 'review_author_publish_time_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__name  time',
			)
		);

		$this->add_control(
			'review_title_heading',
			array(
				'label'     => esc_html__( 'Title', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'review_title_bottom_space',
			array(
				'label' => __( 'Spacing', 'jet-reviews' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-advanced__review-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'review_title_color',
			array(
				'label'  => esc_html__( 'Title Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-advanced__review-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Title Typography', 'jet-reviews' ),
				'name'     => 'review_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-advanced__review-title',
			)
		);

		$this->add_control(
			'review_content_heading',
			array(
				'label'     => esc_html__( 'Content', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'review_content_color',
			array(
				'label'  => esc_html__( 'Content Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-advanced__review-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Content Typography', 'jet-reviews' ),
				'name'     => 'review_content_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-advanced__review-content',
			)
		);

		$this->add_control(
			'review_styles_heading',
			array(
				'label'     => esc_html__( 'Review Item', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'review_gap',
			array(
				'label'   => esc_html__( 'Review Gap', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'review_padding',
			array(
				'label'      => esc_html__( 'Review Padding', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['review'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'review_border',
				'label'          => esc_html__( 'Review Border', 'jet-reviews' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['review'],
			)
		);

		$this->add_responsive_control(
			'review_border_radius',
			array(
				'label'      => esc_html__( 'Review Border Radius', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['review'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => esc_html__( 'Review Box Shadow', 'jet-reviews' ),
				'name'     => 'review_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['review'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_review_misc_style',
			array(
				'label'      => esc_html__( 'Reviews Misc', 'jet-reviews' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'review_misc_verify_label_heading',
			array(
				'label'     => esc_html__( 'Virify Label', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'review_verify_label_color',
			array(
				'label'  => esc_html__( 'Verify Label Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__verification .verification-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__verification .verification-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'review_verify_icon_size',
			array(
				'label'   => esc_html__( 'Verify Icon Size', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__verification .verification-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__verification .verification-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Verify Label Typography', 'jet-reviews' ),
				'name'     => 'review_verify_label_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__verification .verification-label',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'review_verify_label_border',
				'label'          => esc_html__( 'Border', 'jet-reviews' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__verification',
			)
		);

		$this->add_responsive_control(
			'review_verify_label_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-reviews' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['review'] . ' .jet-reviews-user-data__verification' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_comments_style',
			array(
				'label'      => esc_html__( 'Comments', 'jet-reviews' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'comments_general_heading',
			array(
				'label'     => esc_html__( 'General', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'comments_title_bottom_space',
			array(
				'label' => __( 'Title Spacing', 'jet-reviews' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comments'] . ' .jet-reviews-advanced__comments-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'comments_title_color',
			array(
				'label'  => esc_html__( 'Title Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comments'] . ' .jet-reviews-advanced__comments-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Title Typography', 'jet-reviews' ),
				'name'     => 'comments_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['comments'] . ' .jet-reviews-advanced__comments-title',
			)
		);

		$this->add_control(
			'comment_author_heading',
			array(
				'label'     => esc_html__( 'Author', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'comment_author_avatar_size',
			array(
				'label'   => esc_html__( 'Avatar Size', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 16,
						'max' => 64,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ' .jet-reviews-comment-user-avatar' => 'min-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'comment_author_avatar_space',
			array(
				'label' => __( 'Avatar Spacing', 'jet-reviews' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ' .jet-reviews-comment-user-avatar' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'comment_author_name_color',
			array(
				'label'  => esc_html__( 'Author Name Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ' .jet-reviews-comment-user-name > span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Author Name Typography', 'jet-reviews' ),
				'name'     => 'comment_author_name_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['comment'] . ' .jet-reviews-comment-user-name > span',
			)
		);

		$this->add_control(
			'comment_publish_time_color',
			array(
				'label'  => esc_html__( 'Publish Time Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ' .jet-reviews-comment-user-name time' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Publish Time Typography', 'jet-reviews' ),
				'name'     => 'comment_publish_time_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['comment'] . ' .jet-reviews-comment-user-name time',
			)
		);

		$this->add_control(
			'comment_content_heading',
			array(
				'label'     => esc_html__( 'Content', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'comment_content_color',
			array(
				'label'  => esc_html__( 'Content Color', 'jet-reviews' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ' .jet-reviews-comment-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Content Typography', 'jet-reviews' ),
				'name'     => 'comment_content_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['comment'] . ' .jet-reviews-comment-content',
			)
		);

		$this->add_control(
			'comment_styles_heading',
			array(
				'label'     => esc_html__( 'Comment Item', 'jet-reviews' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'comment_gap',
			array(
				'label'   => esc_html__( 'Comment Gap', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-reviews-advanced__review-comments > ' . $css_scheme['comment'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'comment_reply_gap',
			array(
				'label'   => esc_html__( 'Reply Gap', 'jet-reviews' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-reviews-comment-reply-list > ' . $css_scheme['comment'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		$settings = $this->get_settings();

		$instance_settings = array(
			'ratingLayout' => $settings['rating_layout'],
			'icons'        => array(
				'pinnedIcon'              => $settings[ 'pinned_icon' ],
				'emptyStarIcon'           => $settings[ 'empty_star_icon' ],
				'filledStarIcon'          => $settings[ 'filled_star_icon' ],
				'newReviewButtonIcon'     => $settings[ 'new_review_button_icon' ],
				'showCommentsButtonIcon'  => $settings[ 'show_comments_button_icon' ],
				'newCommentButtonIcon'    => $settings[ 'new_comment_button_icon' ],
				'reviewEmptyLikeIcon'     => $settings[ 'review_empty_like_icon' ],
				'reviewFilledLikeIcon'    => $settings[ 'review_filled_like_icon' ],
				'reviewEmptyDislikeIcon'  => $settings[ 'review_empty_dislike_icon' ],
				'reviewFilledDislikeIcon' => $settings[ 'review_filled_dislike_icon' ],
				'replyButtonIcon'         => $settings[ 'reply_button_icon' ],
			),
			'labels'       => array(
				'cancelButtonLabel'        => esc_attr( $settings['cancel_button_label'] ),
				'noReviewsLabel'           => esc_attr( $settings['no_reviews_label'] ),
				'newReviewButton'          => esc_attr( $settings['new_review_button_label'] ),
				'reviewContentPlaceholder' => esc_attr( $settings['review_content_placeholder'] ),
				'reviewTitlePlaceholder'   => esc_attr( $settings['review_title_placeholder'] ),
				'submitReviewButton'       => esc_attr( $settings['submit_review_button_label'] ),
				'newCommentButton'         => esc_attr( $settings['new_comment_button_label'] ),
				'showCommentsButton'       => esc_attr( $settings['show_comments_button_label'] ),
				'hideCommentsButton'       => esc_attr( $settings['hide_comments_button_label'] ),
				'сommentsTitle'            => esc_attr( $settings['comments_title_label'] ),
				'commentPlaceholder'       => esc_attr( $settings['comment_placeholder'] ),
				'submitCommentButton'      => esc_attr( $settings['submit_comment_button_label'] ),
				'replyButton'              => esc_attr( $settings['reply_comment_button_label'] ),
				'replyPlaceholder'         => esc_attr( $settings['reply_placeholder'] ),
				'submitReplyButton'        => esc_attr( $settings['submit_reply_comment_button_label'] ),
			),
		);

		$render_widget_instance = new \Jet_Reviews\Reviews\Elementor_Widget_Render( $instance_settings );

		$render_widget_instance->render();

		$this->__close_wrap();

	}

}
