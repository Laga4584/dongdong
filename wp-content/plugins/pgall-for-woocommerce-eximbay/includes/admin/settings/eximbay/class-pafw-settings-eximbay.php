<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Settings_Eximbay' ) ) {
	abstract class PAFW_Settings_Eximbay extends PAFW_Settings {

		public function __construct() {
			$this->master_id = 'eximbay';

			$this->prefix = '';

			parent::__construct();
		}
		function get_basic_setting_fields() {
			$instance = pafw_get_settings( 'eximbay_basic' );

			return $instance->get_setting_fields();
		}
		function get_advanced_setting_fields() {
			$instance = pafw_get_settings( 'eximbay_advanced' );

			return $instance->get_setting_fields();
		}
	}
}
