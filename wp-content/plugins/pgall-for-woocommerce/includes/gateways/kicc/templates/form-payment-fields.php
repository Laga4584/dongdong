<?php
$uid = uniqid( 'pafw_kicc_' );

if ( ! is_account_page() && is_user_logged_in() && 'user' == pafw_get( $gateway->settings, 'management_batch_key', 'subscription' ) ) {
	$bill_key = get_user_meta( get_current_user_id(), $gateway->get_subscription_meta_key( 'bill_key' ), true );

	if ( ! empty( $bill_key ) ) {
		$issue_nm = get_user_meta( get_current_user_id(), $gateway->get_subscription_meta_key( 'card_name' ), true );
		$pay_id   = get_user_meta( get_current_user_id(), $gateway->get_subscription_meta_key( 'card_num' ), true );
		$pay_id   = substr_replace( $pay_id, '********', 4, 8 );
		$pay_id   = implode( '-', str_split( $pay_id, 4 ) );

	}
}

?>
<script>
    jQuery( document ).ready( function ( $ ) {
        var $wrapper = $( 'div.kicc-payment-fields' );

        $( '.pafw-card-info .pafw_card_type', $wrapper ).on( 'change', function () {
            if ( this.checked ) {
                $( 'input[name=pafw_kicc_cert_no]', $wrapper )
                    .attr( 'placeholder', $( this ).data( 'placeholder' ) )
                    .attr( 'maxlength', $( this ).data( 'size' ) )
                    .attr( 'size', $( this ).data( 'size' ) )
                    .val( '' );
            }
        } );

        $( 'input.change-card', $wrapper ).on( 'click', function () {
            $( 'div.billing_info', $wrapper ).css( 'display', 'none' );
            $( 'div.pafw-card-info', $wrapper ).css( 'display', 'block' );
            $( 'input[name=kicc_issue_bill_key]', $wrapper ).val( 'yes' );
        } );
    } );
</script>

<div class="kicc-payment-fields">
	<?php if ( ! empty( $bill_key ) ) : ?>
        <div class="billing_info">
            <span style="margin-right: 20px;"><?php echo $issue_nm . ' ( ' . $pay_id . ' ) '; ?></span>
            <input type="button" class="button change-card" style="margin: 0 !important;" value="<?php _e( '카드변경', 'pgall-for-woocommerce' ); ?>">
        </div>
	<?php endif; ?>
    <input type="hidden" name="kicc_issue_bill_key" value="<?php echo empty( $bill_key ) ? 'yes' : 'no'; ?>">
    <div class="pafw-card-info" style="<?php echo ! empty( $bill_key ) ? 'display:none' : ''; ?>">
        <div class="fields-wrap card_type">
            <div class="item">
                <input type="radio" id='kicc_card_type_p<?php echo $uid; ?>' class='pafw_card_type' name="pafw_kicc_card_type" value='0' data-label="<?php _e( '법정 생년월일', 'pgall-for-woocommerce' ); ?>" data-placeholder="<?php _e( '주민번호 앞 6자리', 'pgall-for-woocommerce' ); ?>" data-size="6" checked>
                <label for="kicc_card_type_p<?php echo $uid; ?>"><?php _e( '개인카드', 'pgall-for-woocommerce' ); ?></label>
                <div class="check"></div>
            </div>
            <div class="item">
                <input type="radio" id="kicc_card_type_c<?php echo $uid; ?>" class='pafw_card_type' name="pafw_kicc_card_type" value='1' data-label="<?php _e( '사업자번호', 'pgall-for-woocommerce' ); ?>" data-placeholder="<?php _e( '사업자번호 10자리', 'pgall-for-woocommerce' ); ?>" data-size="10">
                <label for="kicc_card_type_c<?php echo $uid; ?>"><?php _e( '법인카드', 'pgall-for-woocommerce' ); ?></label>
                <div class="check"></div>
            </div>
        </div>
        <div class="fields-wrap">
            <div class="card_no">
                <input type="text" maxlength="16" size="16" name="pafw_kicc_card_no" placeholder="카드번호는 '-'를 빼고 숫자만 입력해주세요." value="">
            </div>
        </div>
        <div class="fields-wrap flex">
            <div class="expiry">
                <select name="pafw_kicc_expiry_month">
					<?php
					for ( $i = 1; $i <= 12; $i++ ) {
						echo sprintf( '<option value="%02d">%d 월</option>', $i, $i );
					}
					?>
                </select>
            </div>
            <div class="expiry">
                <select name="pafw_kicc_expiry_year">
					<?php
					for ( $i = 0; $i <= 10; $i++ ) {
						echo sprintf( '<option value="%04d">%04d 년</option>', intval( date( 'Y' ) ) + $i, intval( date( 'Y' ) ) + $i );
					}
					?>
                </select>
            </div>
        </div>
        <div class="fields-wrap flex">
            <div class="cert_no">
                <div>
                    <input type="text" maxlength="10" size="10" name="pafw_kicc_cert_no" placeholder="<?php _e( '주민번호 앞 6자리', 'pgall-for-woocommerce' ); ?>" value="">
                </div>
            </div>
            <div class="cust-type">
                <div>
                    <input type="password" maxlength="2" size="2" name="pafw_kicc_card_pw" placeholder="<?php _e( '비밀번호 앞 2자리', 'pgall-for-woocommerce' ); ?>" value="">
                </div>
            </div>
        </div>
    </div>
	<?php if ( ! is_account_page() ) : ?>
		<?php if ( 'yes' == pafw_get( $gateway->settings, 'enable_quota', 'no' ) ) : ?>
            <div class="pafw-card-info">
                <div class="fields-wrap">
                    <select name="pafw_kicc_card_quota">
                        <option value="00"><?php _e( '일시불', 'pgall-for-woocommerce' ); ?></option>
						<?php
						$quotas = explode( ',', pafw_get( $gateway->settings, 'quota' ) );
						?>
						<?php foreach ( $quotas as $quota ) : ?>
                            <option value="<?php echo sprintf( "%02d", $quota ); ?>"><?php echo $quota . __( '개월', 'pgall-for-woocommerce' ); ?></option>
						<?php endforeach; ?>
                    </select>
                </div>
            </div>
		<?php else: ?>
            <input type="hidden" name="pafw_kicc_card_quota" value="00">
		<?php endif; ?>
	<?php endif; ?>
</div>
