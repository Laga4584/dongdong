<?php

if ( 'sandbox' === pafw_get( $this->settings, 'operation_mode', 'sandbox' ) ) {
	$action_url = 'https://testpg.easypay.co.kr/webpay/MainAction.do';
} else {
	$action_url = 'https://pg.easypay.co.kr/webpay/MainAction.do';
}

$quotabase = pafw_get( $this->settings, 'quotabase' );

if ( empty( $quotabase ) ) {
	$quotabase = '00';
} else {
	$quota = array ( '00' );
	foreach ( explode( ',', $quotabase ) as $month ) {
		$quota[] = sprintf( '%02d', $month );
	}
	$quotabase = implode( ':', $quota );
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script>
        window.onload = function () {
            document.frm.submit();
        };
    </script>
</head>
<body>
<form name="frm" method="post" action="<?php echo $action_url; ?>?">
    <!--------------------------->
    <!-- ::: 공통 인증 요청 값 -->
    <!--------------------------->
    <input type="hidden" id="EP_mall_id" name="EP_mall_id" value="<?php echo pafw_get( $this->settings, 'merchant_id' ); ?>">
    <input type="hidden" id="EP_mall_nm" name="EP_mall_nm" value="<?php echo urlencode( get_option( 'blogname' ) ); ?>">
    <input type="hidden" id="EP_order_no" name="EP_order_no" value="<?php echo pafw_get_object_property( $order, 'id' ); ?>">
    <input type="hidden" id="EP_pay_type" name="EP_pay_type" value="<?php echo $this->pay_type; ?>">
    <input type="hidden" id="EP_currency" name="EP_currency" value="00">
    <input type="hidden" id="EP_product_nm" name="EP_product_nm" value="<?php echo urlencode( $this->make_product_info( $order ) ); ?>">
    <input type="hidden" id="EP_product_amt" name="EP_product_amt" value="<?php echo $order->get_total(); ?>">

    <input type="hidden" id="EP_tot_amt" name="EP_tot_amt" value="<?php echo $order->get_total(); ?>">
    <input type="hidden" id="EP_tax_flg" name="EP_tax_flg" value="TG01">
    <input type="hidden" id="sp_com_tax_amt" name="EP_com_tax_amt" value="<?php echo $this->get_tax_amount( $order ) ?>">
    <input type="hidden" id="sp_com_free_amt" name="EP_com_free_amt" value="<?php echo $this->get_tax_free_amount( $order ); ?>">
    <input type="hidden" id="sp_com_vat_amt" name="EP_com_vat_amt" value="<?php echo $this->get_total_tax( $order ); ?>">

    <input type="hidden" id="EP_return_url" name="EP_return_url" value="<?php echo $this->get_api_url( 'payment' ) ?>">
    <input type="hidden" id="EP_ci_url" name="EP_ci_url" value="<?php echo pafw_get( $this->settings, 'site_logo' ); ?>">
    <input type="hidden" id="EP_lang_flag" name="EP_lang_flag" value="<?php echo pafw_get( $this->settings, 'language_code', 'KOR' ); ?>">
    <input type="hidden" id="EP_charset" name="EP_charset" value="UTF-8">
    <input type="hidden" id="EP_user_nm" name="EP_user_nm" value="<?php echo urlencode( pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) ) ); ?>">
    <input type="hidden" id="EP_user_mail" name="EP_user_mail" value="<?php echo pafw_get_object_property( $order, 'billing_email' ); ?>">
    <input type="hidden" id="EP_user_phone1" name="EP_user_phone1" value="<?php echo pafw_get_customer_phone_number( $order ); ?>">
    <input type="hidden" id="EP_user_phone2" name="EP_user_phone2" value="">
    <input type="hidden" id="EP_user_define1" name="EP_user_define1" value="">         <!-- 가맹점 필드1 // -->
    <input type="hidden" id="EP_user_define2" name="EP_user_define2" value="">         <!-- 가맹점 필드2 // -->
    <input type="hidden" id="EP_user_define3" name="EP_user_define3" value="">         <!-- 가맹점 필드3 // -->
    <input type="hidden" id="EP_user_define4" name="EP_user_define4" value="">         <!-- 가맹점 필드4 // -->
    <input type="hidden" id="EP_user_define5" name="EP_user_define5" value="">         <!-- 가맹점 필드5 // -->
    <input type="hidden" id="EP_user_define6" name="EP_user_define6" value="">         <!-- 가맹점 필드6 // -->
    <input type="hidden" id="EP_product_type" name="EP_product_type" value="">         <!-- 상품정보구분 // -->
    <input type="hidden" id="EP_product_expr" name="EP_product_expr" value="">         <!-- 서비스 기간 // (YYYYMMDD) -->
    <input type="hidden" id="EP_disp_cash_yn" name="EP_disp_cash_yn" value="<?php echo pafw_get( $this->settings, 'receipt', 'N' ); ?>">

    <!--------------------------->
    <!-- ::: 카드 인증 요청 값 -->
    <!--------------------------->
	<?php if ( 'kicc_card' == $this->id ) : ?>
        <input type="hidden" id="EP_usedcard_code" name="EP_usedcard_code" value="">
        <input type="hidden" id="EP_quota" name="EP_quota" value="<?php echo $quotabase; ?>">
        <input type="hidden" id="EP_os_cert_flag" name="EP_os_cert_flag" value="2">
        <input type="hidden" id="EP_noinst_flag" name="EP_noinst_flag" value="<?php echo 'yes' == pafw_get( $this->settings, 'use_nointerest' ) ? 'Y' : ''; ?>">
        <input type="hidden" id="EP_noinst_term" name="EP_noinst_term" value="<?php echo 'yes' == pafw_get( $this->settings, 'use_nointerest' ) ? pafw_get( $this->settings, 'nointerest' ) : ''; ?>">
        <input type="hidden" id="EP_set_point_card_yn" name="EP_set_point_card_yn" value="">
        <input type="hidden" id="EP_point_card" name="EP_point_card" value="">
        <input type="hidden" id="EP_join_cd" name="EP_join_cd" value="">
        <input type="hidden" id="EP_kmotion_useyn" name="EP_kmotion_useyn" value="Y">
	<?php endif; ?>

    <!------------------------------->
    <!-- ::: 가상계좌 인증 요청 값 -->
    <!------------------------------->
	<?php if ( 'kicc_vbank' == $this->id ) : ?>
        <?php
		$vbank_term        = pafw_get( $this->settings, 'account_date_limit', '3' );
		$vbank_end_date = Date( 'Ymd', strtotime( "+{$vbank_term} days" ) );
        ?>
        <input type="hidden" id="EP_vacct_bank" name="EP_vacct_bank" value="">
        <input type="hidden" id="EP_vacct_end_date" name="EP_vacct_end_date" value="<?php echo $vbank_end_date; ?>">
        <input type="hidden" id="EP_vacct_end_time" name="EP_vacct_end_time" value="235959">
	<?php endif; ?>

	<?php if ( $this->supports( 'pafw-escrow' ) ) : ?>
		<?php
		$product_info = '';
		foreach ( $order->get_items() as $item ) {
			$product_info .= 'prd_no=' . $item->get_product_id() . chr( 31 );
			$product_info .= 'prd_amt=' . ( $item->get_total() / $item->get_quantity() ) . chr( 31 );
			$product_info .= 'prd_nm=' . urlencode( wp_strip_all_tags( $item->get_name() ) ) . chr( 31 ) . chr( 30 );
		}

		?>
        <input type="hidden" id="EP_escr_type" name="EP_escr_type" value="K">
        <input type="hidden" id="EP_recv_nm" name="EP_recv_nm" value="<?php echo urlencode( pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) ) ); ?>">
        <input type="hidden" id="EP_recv_mail" name="EP_recv_mail" value="<?php echo pafw_get_object_property( $order, 'billing_email' ); ?>">
        <input type="hidden" id="EP_recv_mob" name="EP_recv_mob" value="<?php echo pafw_get_customer_phone_number( $order ); ?>">
        <input type="hidden" id="EP_bk_totamt" name="EP_bk_totamt" value="<?php echo $order->get_total(); ?>">
        <input type="hidden" id="EP_bk_totamt" name="EP_bk_cnt" value="<?php echo $order->get_item_count() > 30 ? 30 : $order->get_item_count(); ?>">
        <input type="hidden" id="EP_bk_goodinfo" name="EP_bk_goodinfo" value="<?php echo $product_info; ?>">
	<?php endif; ?>
    <!--------------------------------->
    <!-- ::: 인증응답용 인증 요청 값 -->
    <!--------------------------------->

    <input type="hidden" id="EP_res_cd" name="EP_res_cd" value="">      <!--  응답코드 // -->
    <input type="hidden" id="EP_res_msg" name="EP_res_msg" value="">      <!--  응답메세지 // -->
    <input type="hidden" id="EP_tr_cd" name="EP_tr_cd" value="">      <!--  결제창 요청구분 // -->
    <input type="hidden" id="EP_ret_pay_type" name="EP_ret_pay_type" value="">      <!--  결제수단 // -->
    <input type="hidden" id="EP_ret_complex_yn" name="EP_ret_complex_yn" value="">      <!--  복합결제 여부 (Y/N) // -->
    <input type="hidden" id="EP_card_code" name="EP_card_code" value="">      <!--  카드코드 (ISP:KVP카드코드 MPI:카드코드) // -->
    <input type="hidden" id="EP_eci_code" name="EP_eci_code" value="">      <!--  MPI인 경우 ECI코드 // -->
    <input type="hidden" id="EP_card_req_type" name="EP_card_req_type" value="">      <!--  거래구분 // -->
    <input type="hidden" id="EP_save_useyn" name="EP_save_useyn" value="">      <!--  카드사 세이브 여부 (Y/N) // -->
    <input type="hidden" id="EP_trace_no" name="EP_trace_no" value="">      <!--  추적번호 // -->
    <input type="hidden" id="EP_sessionkey" name="EP_sessionkey" value="">      <!--  세션키 // -->
    <input type="hidden" id="EP_encrypt_data" name="EP_encrypt_data" value="">      <!--  암호화전문 // -->
    <input type="hidden" id="EP_spay_cp" name="EP_spay_cp" value="">      <!--  간편결제 CP 코드 // -->
    <input type="hidden" id="EP_card_prefix" name="EP_card_prefix" value="">      <!--  신용카드prefix // -->
    <input type="hidden" id="EP_card_no_7" name="EP_card_no_7" value="">      <!--  신용카드번호 앞7자리 // -->
</form>
</body>
</html>



