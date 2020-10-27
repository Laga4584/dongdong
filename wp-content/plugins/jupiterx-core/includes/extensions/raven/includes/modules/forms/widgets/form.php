<?php
namespace JupiterX_Core\Raven\Modules\Forms\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Forms\Module;
use JupiterX_Core\Raven\Utils;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Form extends Base_Widget {

	public function get_name() {
		return 'raven-form';
	}

	public function get_title() {
		return __( 'Form', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-form';
	}

	protected function _register_controls() {
		$this->register_section_form_fields();
		$this->register_section_submit_button();
		$this->register_section_settings();
		$this->register_section_messages();
		$this->register_section_general();
		$this->register_section_label();
		$this->register_section_field();
		$this->register_section_select();
		$this->register_section_checkbox();
		$this->register_section_radio();
		$this->register_section_button();
	}

	private function register_section_form_fields() {

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => __( 'Form Fields', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => __( 'Form', 'jupiterx-core' ),
				'type' => 'text',
				'default' => 'New form',
				'placeholder' => __( 'Enter your form name', 'jupiterx-core' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'type',
			[
				'label' => __( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'options' => Module::get_field_types(),
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'type' => 'text',
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'jupiterx-core' ),
				'type' => 'text',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '!in',
							'value' => [
								'acceptance',
								'recaptcha',
								'recaptcha_v3',
								'checkbox',
								'radio',
								'select',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'name' => 'field_options',
				'label' => __( 'Options', 'jupiterx-core' ),
				'type' => 'textarea',
				'default' => '',
				'description' => __( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'jupiterx-core' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'inline_list',
			[
				'name' => 'inline_list',
				'label' => __( 'Inline List', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'raven-subgroup-inline',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'native_html5',
			[
				'label' => __( 'Native HTML5', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [ 'date', 'time' ],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'multiple_selection',
			[
				'label' => __( 'Multiple Selection', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'select',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'jupiterx-core' ),
				'name' => 'rows',
				'type' => 'number',
				'default' => 5,
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => 'in',
							'value' => [
								'textarea',
								'select',
							],
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'type',
									'operator' => '===',
									'value' => 'textarea',
								],
								[
									'name' => 'multiple_selection',
									'operator' => '===',
									'value' => 'true',
								],
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => __( 'Required', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '!in',
							'value' => [
								'recaptcha',
								'recaptcha_v3',
								'checkbox',
							],
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => __( 'Column Width', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'jupiterx-core' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
				'conditions' => [
					'terms' => [
						[
							'name' => 'type',
							'operator' => '!in',
							'value' => [
								'recaptcha',
								'recaptcha_v3',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'fields',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'label' => 'Name',
						'type' => 'text',
						'placeholder' => 'Name',
					],
					[
						'label' => 'Email',
						'type' => 'email',
						'placeholder' => 'Email',
						'required' => 'true',
					],
					[
						'label' => 'Message',
						'type' => 'textarea',
						'placeholder' => 'Message',
					],
				],
				'frontend_available' => true,
				'title_field' => '{{{ label }}}',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_submit_button() {
		$this->start_controls_section(
			'section_submit_button',
			[
				'label' => __( 'Submit Button', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'submit_button_text',
			[
				'label' => __( 'Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => __( 'Send', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'submit_button_icon_new',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'submit_button_icon',
			]
		);

		$this->add_responsive_control(
			'submit_button_width',
			[
				'label' => __( 'Column Width', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => __( 'Default', 'jupiterx-core' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'required_mark',
			[
				'label' => __( 'Required Mark', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'actions',
			[
				'label' => __( 'Add Action', 'jupiterx-core' ),
				'type' => 'select2',
				'multiple' => true,
				'options' => Module::get_action_types(),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$this->end_controls_section();
	}

	private function register_section_messages() {
		$this->start_controls_section(
			'section_messages',
			[
				'label' => __( 'Feedback Messages', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'messages_custom',
			[
				'label' => __( 'Custom Messages', 'jupiterx-core' ),
				'type' => 'switcher',
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'messages_success',
			[
				'label' => __( 'Success Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => Module::$messages['success'],
				'label_block' => true,
				'render_type' => 'ui',
				'condition' => [
					'messages_custom' => 'yes',
				],
			]
		);

		$this->add_control(
			'messages_error',
			[
				'label' => __( 'Error Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => Module::$messages['error'],
				'label_block' => true,
				'render_type' => 'ui',
				'condition' => [
					'messages_custom' => 'yes',
				],
			]
		);

		$this->add_control(
			'messages_required',
			[
				'label' => __( 'Required Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => Module::$messages['required'],
				'label_block' => true,
				'render_type' => 'ui',
				'condition' => [
					'messages_custom' => 'yes',
				],
			]
		);

		$this->add_control(
			'messages_subscriber',
			[
				'label' => __( 'Subscriber Already Exists Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => Module::$messages['subscriber'],
				'label_block' => true,
				'render_type' => 'ui',
				'condition' => [
					'messages_custom' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_general() {
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'general_column_spacing',
			[
				'label' => __( 'Column Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-group' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 );padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .raven-form' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 );margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
				],
			]
		);

		$this->add_responsive_control(
			'general_row_spacing',
			[
				'label' => __( 'Row Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_label() {
		$this->start_controls_section(
			'section_style_label',
			[
				'label' => __( 'Label', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .raven-field-label',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-group > .raven-field-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_field() {
		$this->start_controls_section(
			'section_style_field',
			[
				'label' => __( 'Field', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);
		$this->start_controls_tabs( 'field_tabs_state' );

		$this->start_controls_tab(
			'field_tab_state_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_background_color_normal',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'field_tab_border_normal',
				'selector' => '{{WRAPPER}} .raven-field',
			]
		);

		$this->add_responsive_control(
			'field_tab_border_radius_normal',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'field_tab_box_shadow_normal',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-field',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'field_tab_state_focus',
			[
				'label' => __( 'Focus', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_background_color_focus',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'field_tab_border_focus',
				'selector' => '{{WRAPPER}} .raven-field:focus',
			]
		);

		$this->add_responsive_control(
			'field_tab_border_radius_focus',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'field_tab_box_shadow_focus',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-field:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'field_tabs_separator',
			[
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'field_tabs' );

		$this->start_controls_tab(
			'field_tab_placeholder',
			[
				'label' => __( 'Placeholder', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_color_placeholder',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field::-ms-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'field_tab_typography_placeholder',
				'selector' => '{{WRAPPER}} .raven-field::placeholder',
				'scheme' => '3',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'field_tab_value',
			[
				'label' => __( 'Value', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'field_tab_color_value',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'field_tab_typography_value',
				'selector' => '{{WRAPPER}} .raven-field',
				'scheme' => '3',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .raven-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_select() {
		$this->start_controls_section(
			'section_style_select',
			[
				'label' => __( 'Select', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'select_arrow_icon_new',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'select_arrow_icon',
				'default' => 'fa fa-angle-down',
				'default' => [
					'value' => 'fas fa-angle-down',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'select_arrow_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-select-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-field-select-arrow > svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'select_arrow_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => '20',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-select-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-select-arrow > svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'select_arrow_vertical_offset',
			[
				'label' => __( 'Vertical Offset', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-select-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'select_arrow_horizontal_offset',
			[
				'label' => __( 'Horizontal Offset', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => '13',
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-select-arrow' => is_rtl() ? 'left: {{SIZE}}{{UNIT}};' : 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_checkbox() {
		$this->start_controls_section(
			'section_style_checkbox',
			[
				'label' => __( 'Checkbox', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'checkbox_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label' => 'padding-left: calc({{SIZE}}{{UNIT}} + 8px);line-height: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:after' => 'width: calc({{SIZE}}{{UNIT}} - 8px); height: calc({{SIZE}}{{UNIT}} - 8px);',
				],
			]
		);

		$this->add_control(
			'checkbox_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'checkbox_typography',
				'selector' => '{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'checkbox_spacing_between',
			[
				'label' => __( 'Spacing Between', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-option' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'checkbox_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'checkbox_tabs_state' );

		$this->start_controls_tab(
			'checkbox_tab_state_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'checkbox_tab_background_color_normal',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'checkbox_tab_border_normal',
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'checkbox_tab_box_shadow_normal',
				'selector' => '{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkbox_tab_state_checked',
			[
				'label' => __( 'Checked', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'checkbox_tab_background_color_checked',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'checkbox_tab_border_checked',
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-field-type-checkbox .raven-field:checked ~ .raven-field-label:before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'checkbox_tab_box_shadow_checked',
				'selector' => '{{WRAPPER}} .raven-field-type-checkbox .raven-field:checked ~ .raven-field-label:before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'checkbox_separator',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'checkbox_border_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-checkbox .raven-field-subgroup .raven-field-label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_radio() {
		$this->start_controls_section(
			'section_style_radio',
			[
				'label' => __( 'Radio', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'radio_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label' => 'padding-left: calc({{SIZE}}{{UNIT}} + 8px);line-height: calc({{SIZE}}{{UNIT}} + 2px);',
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:after' => 'width: calc({{SIZE}}{{UNIT}} - 8px); height: calc({{SIZE}}{{UNIT}} - 8px);',
				],
			]
		);

		$this->add_control(
			'radio_color',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'radio_typography',
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'radio_spacing_between',
			[
				'label' => __( 'Spacing Between', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-option' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'radio_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'radio_tabs_state' );

		$this->start_controls_tab(
			'radio_tab_state_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'radio_tab_background_color_normal',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'radio_tab_border_normal',
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'radio_tab_box_shadow_normal',
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'radio_tab_state_checked',
			[
				'label' => __( 'Checked', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'radio_tab_background_color_checked',
			[
				'label' => __( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-field-type-radio .raven-field-subgroup .raven-field-label:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'radio_tab_border_checked',
				'fields_options' => [
					'width' => [
						'label' => __( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field:checked ~ .raven-field-label:before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'radio_tab_box_shadow_checked',
				'selector' => '{{WRAPPER}} .raven-field-type-radio .raven-field:checked ~ .raven-field-label:before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/** * @SuppressWarnings(PHPMD) */
	private function register_section_button() {
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => __( 'Button', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label' => __( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_spacing',
			[
				'label' => __( 'Spacing', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label'  => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => '',
				'prefix_class' => 'raven%s-form-button-align-',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_tab_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_tab_color_normal',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_tab_typography_normal',
				'selector' => '{{WRAPPER}} .raven-submit-button > span',
				'scheme' => '3',
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'button_tab_background_normal',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-submit-button',
			]
		);

		$this->add_control(
			'button_border_heading',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .raven-submit-button',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'unit'   => 'px',
							'top'    => '1',
							'left'   => '1',
							'right'  => '1',
							'bottom' => '1',
						],
					],
					'color' => [
						'default' => '#2ecc71',
					],
				],
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'button_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-submit-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_tab_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_tab_typography_hover',
				'selector' => '{{WRAPPER}} .raven-submit-button:hover span',
				'scheme' => '3',
			]
		);

		$this->add_group_control(
			'raven-background',
			[
				'name' => 'button_tab_background_hover',
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-submit-button:hover',
			]
		);

		$this->add_control(
			'button_border_heading_hover',
			[
				'label' => __( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'button_border_hover',
				'selector' => '{{WRAPPER}} .raven-submit-button:hover',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'unit'   => 'px',
							'top'    => '1',
							'left'   => '1',
							'right'  => '1',
							'bottom' => '1',
						],
					],
					'color' => [
						'default' => '#2ecc71',
					],
				],
			]
		);

		$this->add_responsive_control(
			'button_radius_hover',
			[
				'label' => __( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'button_box_shadow_hover',
				'exclude' => [
					'box_shadow_position',
				],
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .raven-submit-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_icon_heading',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'submit_button_icon_new[value]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'button_icon_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'condition' => [
					'submit_button_icon_new[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-submit-button svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_icon_space_between',
			[
				'label' => __( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'condition' => [
					'submit_button_icon_new[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.raven-form-button-icon-left .raven-submit-button i, {{WRAPPER}}.raven-form-button-icon-left .raven-submit-button svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.raven-form-button-icon-right .raven-submit-button i, {{WRAPPER}}.raven-form-button-icon-right .raven-submit-button svg' => 'margin-left: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'toggle' => false,
				'default' => 'left',
				'prefix_class' => 'raven-form-button-icon-',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'fa fa-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'condition' => [
					'submit_button_icon_new[value]!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'button_icon_tabs' );

		$this->start_controls_tab(
			'button_icon_tabs_normal',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_tab_icon_color_normal',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-submit-button svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'submit_button_icon_new[library]!' => [ '', 'svg' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_icon_tabs_hover',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'button_tab_icon_color_hover',
			[
				'label' => __( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-submit-button:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-submit-button:hover svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'submit_button_icon_new[library]!' => [ '', 'svg' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/** * @SuppressWarnings(PHPMD) */
	protected function render() {
		$settings = $this->get_active_settings();
		$fields   = $settings['fields'];

		$this->add_render_attribute( 'form', [
			'class' => 'raven-form raven-flex raven-flex-wrap raven-flex-bottom',
			'method' => 'post',
			'name' => $settings['form_name'],
		] );

		if ( empty( $settings['required_mark'] ) ) {
			$this->add_render_attribute(
				'form',
				'class',
				'raven-hide-required-mark'
			);
		}

		// @todo Move it into a separate method.
		$this->add_render_attribute(
			'submit-button',
			'class',
			'raven-field-group raven-field-type-submit-button elementor-column elementor-col-' . $settings['submit_button_width']
		);

		if ( ! empty( $settings['submit_button_width_tablet'] ) ) {
			$this->add_render_attribute(
				'submit-button',
				'class',
				'elementor-md-' . $settings['submit_button_width_tablet']
			);
		}

		if ( ! empty( $settings['submit_button_width_mobile'] ) ) {
			$this->add_render_attribute(
				'submit-button',
				'class',
				'elementor-sm-' . $settings['submit_button_width_mobile']
			);
		}

		$this->add_render_attribute(
			'submit-button',
			'class',
			'raven-field-align-' . empty( $settings['button_align'] ) ? 'justify' : $settings['button_align']
		);

		?>
		<form <?php echo $this->get_render_attribute_string( 'form' ); ?>>
			<input type="hidden" name="post_id" value="<?php echo Utils::get_current_post_id(); ?>" />
			<input type="hidden" name="form_id" value="<?php echo $this->get_id(); ?>" />
			<?php

			foreach ( $fields as $field ) {
				Module::render_field( $this, $field );
			}

			?>
			<div <?php echo $this->get_render_attribute_string( 'submit-button' ); ?>>
				<button type="submit" class="raven-submit-button">
					<?php
						$this->render_submit_icon();
					?>
					<span><?php echo $settings['submit_button_text']; ?></span>
				</button>
			</div>
		</form>

		<?php
		if ( $this->has_address_field( $fields ) ) {
			$this->autocomplete_address_fields();
		}
	}

	protected function autocomplete_address_fields() {
		$google_api_key = get_option( 'elementor_raven_google_api_key' );

		if ( empty( $google_api_key ) ) {
			return;
		}
		// phpcs:disable WordPress.WP.EnqueuedResources
		?>
		<script>
			function initRavenAddressFieldsAutocomplete() {
				var addressFields =  document.querySelectorAll('.raven-form input[data-type="address"]')
				for (var i = 0; i < addressFields.length; i++) {
					var autocomplete = new google.maps.places.Autocomplete(addressFields.item(i), {types: ['geocode']});
					autocomplete.setFields(['address_component']);
				}
			}
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initRavenAddressFieldsAutocomplete" async defer></script>
		<?php
		// phpcs:enable WordPress.WP.EnqueuedResources
	}

	protected function has_address_field( $fields ) {
		foreach ( $fields as $field ) {
			if ( 'address' === $field['type'] ) {
				return true;
			}
		}

		return false;
	}

	protected function render_submit_icon() {
		$settings          = $this->get_active_settings();
		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $settings['__fa4_migrated']['submit_button_icon_new'] );
		$is_new            = empty( $settings['submit_button_icon'] ) && $migration_allowed;

		if ( ! empty( $settings['submit_button_icon'] ) || ! empty( $settings['submit_button_icon_new']['value'] ) ) :
			if ( ! empty( $settings['submit_button_icon_new']['value'] || $is_new || $migrated ) ) {
				Elementor::$instance->icons_manager->render_icon( $settings['submit_button_icon_new'], [ 'aria-hidden' => 'true' ] );
			} else {
				?>
			<i class="<?php echo esc_attr( $settings['submit_button_icon'] ); ?>" aria-hidden="true"></i>
				<?php
			}
		endif;
	}
}
