<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Gateway_PAFW_Eximbay' ) ) {

	include_once( PAFW()->plugin_path() . '/includes/gateways/pafw/class-wc-gateway-pafw.php' );
	class WC_Gateway_PAFW_Eximbay extends WC_Gateway_PAFW {
		public function __construct() {
			$this->id = 'mshop_eximbay';

			$this->init_settings();
			$this->method_title = __( '엑심베이', 'pgall-for-woocommerce-eximbay' );

			$this->title              = __( '엑심베이', 'pgall-for-woocommerce-eximbay' );
			$this->method_title       = __( '엑심베이', 'pgall-for-woocommerce-eximbay' );
			$this->method_description = '<div style="font-size: 0.9em;">엑심베이 결제를 이용합니다</div>';

			parent::__construct();

		}
		public static function get_supported_payment_methods() {
			return apply_filters( 'pafweb_supported_payment_methods', array(
				'eximbay_easypay'  => '엑심베이 통합결제창',
				'eximbay_card'     => '신용카드',
				'eximbay_paypal'   => '페이팔',
				'eximbay_unionpay' => 'UnionPay',
				'eximbay_alipay'   => 'Alipay',
				'eximbay_bestpay'  => 'BestPay',
				'eximbay_bankpay'  => 'BankPay',
				'eximbay_toss'     => '토스(TOSS)',
				'eximbay_yandex'   => 'Yandex',
				'eximbay_econtext' => '일본 편의점(Econtext)',
			) );
		}
		public function admin_options() {

			parent::admin_options();

			$options = get_option( 'pafw_mshop_eximbay' );

			$GLOBALS['hide_save_button'] = 'yes' != pafw_get( $options, 'show_save_button', 'no' );

			$settings = $this->get_settings( 'eximbay', self::get_supported_payment_methods() );

			$this->enqueue_script();

			$license_info = json_decode( get_option( 'msl_license_' . PAFWEB()->slug(), json_encode( array(
				'slug'   => PAFWEB()->slug(),
				'domain' => preg_replace( '#^https?://#', '', home_url() )
			) ) ), true );

			$license_info = apply_filters( 'mshop_get_license', $license_info, PAFWEB()->slug() );

			wp_localize_script( 'mshop-setting-manager', 'mshop_setting_manager', array(
				'element'     => 'mshop-setting-wrapper',
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'action'      => PAFW()->slug() . '-update_eximbay_settings',
				'settings'    => $settings,
				'slug'        => PAFWEB()->slug(),
				'domain'      => preg_replace( '#^https?://#', '', site_url() ),
				'licenseInfo' => json_encode( $license_info )
			) );

			?>
            <script>
                jQuery( document ).ready( function ( $ ) {
                    $( this ).trigger( 'mshop-setting-manager', ['mshop-setting-wrapper', '600', <?php echo json_encode( $this->get_setting_values( $this->id, $settings ) ); ?>, <?php echo json_encode( $license_info ); ?>, null] );
                } );
            </script>

            <div id="mshop-setting-wrapper"></div>
			<?php
		}

		public function validate() {
			return true;
		}
	}
}