<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Settings_Eximbay_Bestpay' ) ) {
	class PAFW_Settings_Eximbay_Bestpay extends PAFW_Settings_Eximbay {
		function get_setting_fields() {
			return array (
				array (
					'type'     => 'Section',
					'title'    => 'BestPay 설정',
					'elements' => array (
						array (
							'id'        => 'eximbay_bestpay_title',
							'title'     => '결제수단 이름',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => 'BestPay',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제 페이지에서 구매자들이 결제 진행 시 선택하는 결제수단명 입니다.', 'pgall-for-woocommerce-eximbay' )
								)
							)
						),
						array (
							'id'        => 'eximbay_bestpay_description',
							'title'     => '결제수단 설명',
							'className' => 'fluid',
							'type'      => 'TextArea',
							'default'   => 'BestPay로 결제합니다.',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제 페이지에서 구매자들이 결제 진행 시 제공되는 결제수단 상세설명 입니다.', 'pgall-for-woocommerce-eximbay' )
								)
							)
						)
					)
				),
			);
		}
	}
}
