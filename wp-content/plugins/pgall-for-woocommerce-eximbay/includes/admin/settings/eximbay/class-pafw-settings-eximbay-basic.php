<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Settings_Eximbay_Basic' ) ) {
	class PAFW_Settings_Eximbay_Basic extends PAFW_Settings_Eximbay {
		function get_setting_fields() {
			return array (
				array (
					'type'     => 'Section',
					'title'    => '기본 설정',
					'elements' => array (
						array (
							'id'       => 'pc_pay_method',
							'title'    => '결제수단',
							'default'  => 'eximbay_easypay',
							'type'     => 'Select',
							'multiple' => 'true',
							'options'  => WC_Gateway_PAFW_Eximbay::get_supported_payment_methods()
						),
					)
				),
				array (
					'type'     => 'Section',
					'title'    => '결제 설정',
					'elements' => array (
						array (
							'id'        => 'operation_mode',
							'title'     => '운영 모드',
							'className' => '',
							'type'      => 'Select',
							'default'   => 'sandbox',
							'options'   => array (
								'sandbox'    => '개발환경(Sandbox)',
								'production' => '실환경(Production)'
							)
						),
						array (
							'id'          => 'test_user_id',
							'title'       => '테스트 사용자 아이디',
							'className'   => 'fluid',
							'placeHolder' => '테스트 사용자 아이디를 선택하세요.',
							'showIf'      => array ( 'operation_mode' => 'sandbox' ),
							'type'        => 'Text',
							'default'     => 'pgall_test_user',
							'desc2'       => __( '<div class="desc2">개발환경(Sandbox) 모드에서는 관리자 및 테스트 사용자에게만 결제수단이 노출됩니다.</div>', 'pgall-for-woocommerce-eximbay' ),
						),
						array (
							'id'        => 'payment_method',
							'title'     => '결제수단',
							'className' => '',
							'type'      => 'Select',
							'default'   => '',
							'options'   => array (
								'ALL'  => '전체',
								'P000' => 'CreditCard',
								'P101' => 'VISA',
								'P102' => 'MasterCard',
								'P103' => 'AMEX',
								'P104' => 'JCB',
								'P001' => 'PayPal',
								'P002' => 'CUP',
								'P003' => 'Alipay',
								'P004' => 'Tenpay',
								'P005' => '99Bill',
								'P006' => 'eContext',
								'P007' => 'Molpay',
								'P008' => 'PaysBuy',
							)
						),
						array (
							'id'        => 'merchant_id',
							'title'     => '가맹점 아이디',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '1849705C64',
							'desc2'     => __( '<div class="desc2">결제 테스트용 가맹점 아이디는 <code>1849705C64</code>입니다.</div>', 'pgall-for-woocommerce-eximbay' ),
						),
						array (
							'id'        => 'secret_key',
							'title'     => '가맹점키',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '289F40E6640124B2628640168C3C5464',
							'desc2'     => __( '<div class="desc2">결제 테스트용 가맹점키는 <code>289F40E6640124B2628640168C3C5464</code>입니다.</div>', 'pgall-for-woocommerce-eximbay' ),
						)
					)
				)
			);
		}
	}
}
