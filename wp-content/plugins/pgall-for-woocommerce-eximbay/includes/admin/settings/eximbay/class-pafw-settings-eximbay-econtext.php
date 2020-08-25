<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Settings_Eximbay_Econtext' ) ) {
	class PAFW_Settings_Eximbay_Econtext extends PAFW_Settings_Eximbay {
		function get_setting_fields() {
			return array (
				array (
					'type'     => 'Section',
					'title'    => 'eContext(일본 편의점, 인터넷뱅킹) 설정',
					'elements' => array (
						array (
							'id'        => 'eximbay_econtext_title',
							'title'     => '결제수단 이름',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '일본 편의점',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제 페이지에서 구매자들이 결제 진행 시 선택하는 결제수단명 입니다.', 'pgall-for-woocommerce-eximbay' )
								)
							)
						),
						array (
							'id'        => 'eximbay_econtext_description',
							'title'     => '결제수단 설명',
							'className' => 'fluid',
							'type'      => 'TextArea',
							'default'   => '일본 편의점(Econtext) 결제수단으로 결제합니다.',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제 페이지에서 구매자들이 결제 진행 시 제공되는 결제수단 상세설명 입니다.', 'pgall-for-woocommerce-eximbay' )
								)
							)
						),
						array (
							'id'        => 'eximbay_econtext_payment_due',
							'title'     => '지불기한',
							"className" => "",
							"type"      => "LabeledInput",
							'inputType' => 'number',
							"leftLabel" => "결제일로 부터",
							"label"     => "일",
							"default"   => "7"
						)
					)
				),
			);
		}
	}
}
