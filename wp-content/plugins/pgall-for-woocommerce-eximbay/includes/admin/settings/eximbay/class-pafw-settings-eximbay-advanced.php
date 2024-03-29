<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Settings_Eximbay_Advanced' ) ) {
	class PAFW_Settings_Eximbay_Advanced extends PAFW_Settings_Eximbay {
		function get_setting_fields() {
			return array (
				array (
					'type'     => 'Section',
					'title'    => '고급 설정',
					'elements' => array (
						array (
							'id'        => 'language_code',
							'title'     => __( '결제창 언어', 'pgall-for-woocommerce-eximbay' ),
							'className' => '',
							'type'      => 'Select',
							'default'   => 'KR',
							'options'   => array (
								'KR' => '한국어',
								'EN' => '영어',
								'CN' => '중국어',
								'JP' => '일본어',
							),
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제창의 언어를 설정합니다.', 'pgall-for-woocommerce-eximbay' ),
								)
							)
						),
						array (
							'id'        => 'payment_window_mode',
							'title'     => __( '결제창 호출 방식', 'pgall-for-woocommerce-eximbay' ),
							'className' => '',
							'type'      => 'Select',
							'default'   => 'popup',
							'options'   => array (
								'popup'    => '팝업창',
								'redirect' => '페이지 전환'
							),
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제창의 언어를 설정합니다.', 'pgall-for-woocommerce-eximbay' ),
								)
							)
						),
						array (
							'id'        => 'payment_tag',
							'title'     => __( '결제 페이지 태그 설정', 'pgall-for-woocommerce-eximbay' ),
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '#order_review input[name=payment_method]:checked',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제 페이지가 우커머스 기본 결제 태그와 다른 경우, 결제수단 확인이 가능한 별도 태그를 넣어 지정할 수 있습니다.', 'pgall-for-woocommerce-eximbay' ),
								)
							)
						),
						array (
							'id'        => 'site_ajax_loader',
							'title'     => __( '결제 로딩 이미지', 'pgall-for-woocommerce-eximbay' ),
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => PAFW()->plugin_url() . '/assets/inicis/images/ajax_loader.gif',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제 창이 노출되기 전에 잠시 노출되는 로딩 이미지 경로를 설정할 수 있습니다. (예 : http://www.aaa.com/a.jpg)', 'pgall-for-woocommerce-eximbay' ),
								)
							)
						),
						array (
							'id'        => 'show_save_button',
							'title'     => __( '변경사항 버튼노출', 'pgall-for-woocommerce-eximbay' ),
							'className' => '',
							'type'      => 'Toggle',
							'default'   => 'no',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '우커머스 기본 설정 변경 버튼을 노출합니다. 설정된 경우 버튼이 노출되며 설정되지 않은 경우 버튼이 노출되지 않습니다. 특수한 경우에만 사용하도록 제공되는 옵션으로 일반적인 경우 사용하지 않아도 됩니다.', 'pgall-for-woocommerce-eximbay' ),
								)
							)
						),
					)
				)
			);
		}
	}
}
