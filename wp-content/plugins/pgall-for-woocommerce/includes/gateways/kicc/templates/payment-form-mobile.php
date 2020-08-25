<?php

if ( 'sandbox' === pafw_get( $this->settings, 'operation_mode', 'sandbox' ) ) {
	$action_url = 'https://testsp.easypay.co.kr/ep8/MainAction.do';
} else {
	$action_url = 'https://sp.easypay.co.kr/ep8/MainAction.do';
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
    <input type="hidden" id="sp_mall_id" name="sp_mall_id" value="<?php echo pafw_get( $this->settings, 'merchant_id' ); ?>">
    <input type="hidden" id="sp_mall_nm" name="sp_mall_nm" value="<?php echo urlencode( get_option( 'blogname' ) ); ?>">
    <input type="hidden" id="sp_order_no" name="sp_order_no" value="<?php echo pafw_get_object_property( $order, 'id' ); ?>">
    <input type="hidden" id="sp_pay_type" name="sp_pay_type" value="<?php echo $this->pay_type; ?>">
    <input type="hidden" id="sp_currency" name="sp_currency" value="00">
    <input type="hidden" id="sp_product_nm" name="sp_product_nm" value="<?php echo urlencode( $this->make_product_info( $order ) ); ?>">
    <input type="hidden" id="sp_product_amt" name="sp_product_amt" value="<?php echo $order->get_total(); ?>">

    <input type="hidden" id="sp_tot_amt" name="sp_tot_amt" value="<?php echo $order->get_total(); ?>">
    <input type="hidden" id="sp_tax_flg" name="sp_tax_flg" value="TG01">
    <input type="hidden" id="sp_com_tax_amt" name="sp_com_tax_amt" value="<?php echo $this->get_tax_amount( $order ) ?>">
    <input type="hidden" id="sp_com_free_amt" name="sp_com_free_amt" value="<?php echo $this->get_tax_free_amount( $order ); ?>">
    <input type="hidden" id="sp_com_vat_amt" name="sp_com_vat_amt" value="<?php echo $this->get_total_tax( $order ); ?>">
    
    <input type="hidden" id="sp_return_url" name="sp_return_url" value="<?php echo $this->get_api_url( 'payment' ) ?>">
    <input type="hidden" id="sp_ci_url" name="sp_ci_url" value="<?php echo pafw_get( $this->settings, 'site_logo' ); ?>">
    <input type="hidden" id="sp_lang_flag" name="sp_lang_flag" value="<?php echo pafw_get( $this->settings, 'language_code', 'KOR' ); ?>">
    <input type="hidden" id="sp_charset" name="sp_charset" value="UTF-8">
    <input type="hidden" id="sp_user_nm" name="sp_user_nm" value="<?php echo urlencode( pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) ) ); ?>">
    <input type="hidden" id="sp_user_mail" name="sp_user_mail" value="<?php echo pafw_get_object_property( $order, 'billing_email' ); ?>">
    <input type="hidden" id="sp_user_phone1" name="sp_user_phone1" value="<?php echo pafw_get_customer_phone_number( $order ); ?>">
    <input type="hidden" id="sp_user_phone2" name="sp_user_phone2" value="">
    <input type="hidden" id="sp_user_define1" name="sp_user_define1" value="">         <!-- 가맹점 필드1 // -->
    <input type="hidden" id="sp_user_define2" name="sp_user_define2" value="">         <!-- 가맹점 필드2 // -->
    <input type="hidden" id="sp_user_define3" name="sp_user_define3" value="">         <!-- 가맹점 필드3 // -->
    <input type="hidden" id="sp_user_define4" name="sp_user_define4" value="">         <!-- 가맹점 필드4 // -->
    <input type="hidden" id="sp_user_define5" name="sp_user_define5" value="">         <!-- 가맹점 필드5 // -->
    <input type="hidden" id="sp_user_define6" name="sp_user_define6" value="">         <!-- 가맹점 필드6 // -->
    <input type="hidden" id="sp_product_type" name="sp_product_type" value="">         <!-- 상품정보구분 // -->
    <input type="hidden" id="sp_product_expr" name="sp_product_expr" value="">         <!-- 서비스 기간 // (YYYYMMDD) -->
    <input type="hidden" id="sp_disp_cash_yn" name="sp_disp_cash_yn" value="<?php echo pafw_get( $this->settings, 'receipt', 'N' ); ?>">         <!-- 현금영수증 화면표시여부 //미표시 : "N", 그외: DB조회 -->

    <!--------------------------->
    <!-- ::: 카드 인증 요청 값 -->
    <!--------------------------->
	<?php if ( 'kicc_card' == $this->id ) : ?>
        <input type="hidden" id="sp_usedcard_code" name="sp_usedcard_code" value="">      <!-- 사용가능한 카드 LIST // FORMAT->카드코드:카드코드: ... :카드코드 EXAMPLE->029:027:031 // 빈값 : DB조회-->
        <input type="hidden" id="sp_quota" name="sp_quota" value="<?php echo $quotabase; ?>">      <!-- 할부개월 (카드코드-할부개월) -->
        <input type="hidden" id="sp_os_cert_flag" name="sp_os_cert_flag" value="2">     <!-- 해외안심클릭 사용여부(변경불가) // -->
        <input type="hidden" id="sp_noinst_flag" name="sp_noinst_flag" value="<?php echo 'yes' == pafw_get( $this->settings, 'use_nointerest' ) ? 'Y' : ''; ?>">
        <input type="hidden" id="sp_noinst_term" name="sp_noinst_term" value="<?php echo 'yes' == pafw_get( $this->settings, 'use_nointerest' ) ? pafw_get( $this->settings, 'nointerest' ) : ''; ?>">
        <input type="hidden" id="sp_set_point_card_yn" name="sp_set_point_card_yn" value="">      <!-- 카드사포인트 사용여부 (Y/N) // -->
        <input type="hidden" id="sp_point_card" name="sp_point_card" value="">      <!-- 포인트카드 LIST  // -->
        <input type="hidden" id="sp_join_cd" name="sp_join_cd" value="">      <!-- 조인코드 // -->
        <input type="hidden" id="sp_kmotion_useyn" name="sp_kmotion_useyn" value="Y">     <!-- 국민앱카드 사용유무 (Y/N)// -->
	<?php endif; ?>

    <!------------------------------->
    <!-- ::: 가상계좌 인증 요청 값 -->
    <!------------------------------->
	<?php if ( 'kicc_vbank' == $this->id ) : ?>
		<?php
		$vbank_term     = pafw_get( $this->settings, 'account_date_limit', '3' );
		$vbank_end_date = Date( 'Ymd', strtotime( "+{$vbank_term} days" ) );
		?>
        <input type="hidden" id="sp_vacct_bank" name="sp_vacct_bank" value="">      <!-- 가상계좌 사용가능한 은행 LIST // -->
        <input type="hidden" id="sp_vacct_end_date" name="sp_vacct_end_date" value="<?php echo $vbank_end_date; ?>">      <!-- 입금 만료 날짜 // -->
        <input type="hidden" id="sp_vacct_end_time" name="sp_vacct_end_time" value="235959">      <!-- 입금 만료 시간 // -->
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
        <input type="hidden" id="sp_escr_type" name="sp_escr_type" value="K">
        <input type="hidden" id="sp_recv_nm" name="sp_recv_nm" value="<?php echo urlencode( pafw_remove_emoji( pafw_get_object_property( $order, 'billing_last_name' ) . pafw_get_object_property( $order, 'billing_first_name' ) ) ); ?>">
        <input type="hidden" id="sp_recv_mail" name="sp_recv_mail" value="<?php echo pafw_get_object_property( $order, 'billing_email' ); ?>">
        <input type="hidden" id="sp_recv_mob" name="sp_recv_mob" value="<?php echo pafw_get_customer_phone_number( $order ); ?>">
        <input type="hidden" id="sp_bk_totamt" name="sp_bk_totamt" value="<?php echo $order->get_total(); ?>">
        <input type="hidden" id="sp_bk_totamt" name="sp_bk_cnt" value="<?php echo $order->get_item_count() > 30 ? 30 : $order->get_item_count(); ?>">
        <input type="hidden" id="sp_bk_goodinfo" name="sp_bk_goodinfo" value="<?php echo $product_info; ?>">
	<?php endif; ?>

    <!--------------------------------->
    <!-- ::: 인증응답용 인증 요청 값 -->
    <!--------------------------------->

    <input type="hidden" id="sp_res_cd" name="sp_res_cd" value="">      <!--  응답코드 // -->
    <input type="hidden" id="sp_res_msg" name="sp_res_msg" value="">      <!--  응답메세지 // -->
    <input type="hidden" id="sp_tr_cd" name="sp_tr_cd" value="">      <!--  결제창 요청구분 // -->
    <input type="hidden" id="sp_ret_pay_type" name="sp_ret_pay_type" value="">      <!--  결제수단 // -->
    <input type="hidden" id="sp_ret_complex_yn" name="sp_ret_complex_yn" value="">      <!--  복합결제 여부 (Y/N) // -->
    <input type="hidden" id="sp_card_code" name="sp_card_code" value="">      <!--  카드코드 (ISP:KVP카드코드 MPI:카드코드) // -->
    <input type="hidden" id="sp_eci_code" name="sp_eci_code" value="">      <!--  MPI인 경우 ECI코드 // -->
    <input type="hidden" id="sp_card_req_type" name="sp_card_req_type" value="">      <!--  거래구분 // -->
    <input type="hidden" id="sp_save_useyn" name="sp_save_useyn" value="">      <!--  카드사 세이브 여부 (Y/N) // -->
    <input type="hidden" id="sp_trace_no" name="sp_trace_no" value="">      <!--  추적번호 // -->
    <input type="hidden" id="sp_sessionkey" name="sp_sessionkey" value="">      <!--  세션키 // -->
    <input type="hidden" id="sp_encrypt_data" name="sp_encrypt_data" value="">      <!--  암호화전문 // -->
    <input type="hidden" id="sp_spay_cp" name="sp_spay_cp" value="">      <!--  간편결제 CP 코드 // -->
    <input type="hidden" id="sp_card_prefix" name="sp_card_prefix" value="">      <!--  신용카드prefix // -->
    <input type="hidden" id="sp_card_no_7" name="sp_card_no_7" value="">      <!--  신용카드번호 앞7자리 // -->
</form>
</body>
</html>



