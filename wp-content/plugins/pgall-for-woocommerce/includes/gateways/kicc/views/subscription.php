<?php

?>

<?php if ( ! empty( $bill_key ) ) : ?>
    <div class="pafw_button_wrapper">
        <input type="text" class="repay_price pafw_action_button" name="subscription_batch_key" id="subscription_batch_key" value="<?php echo $bill_key; ?>" readonly style="flex:3;">
        <input type="button" class="button pafw_action_button" id="pafw-cancel-batch-key" name="pafw-cancel-batch-key" value="취소하기">
    </div>
<?php else : ?>
    <div class="pafw_payment_info">
        <p><?php _e( '빌링키 정보가 없습니다.', 'pgall-for-woocommerce' ); ?></p>
    </div>
<?php endif; ?>
