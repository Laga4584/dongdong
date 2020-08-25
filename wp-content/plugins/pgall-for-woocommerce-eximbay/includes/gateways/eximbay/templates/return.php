<?php
?>

<?php if ( 'popup' == pafw_get( $this->settings, 'payment_window_mode', 'popup' ) ) : ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript">
            function loadForm () {
                if ( opener && opener.document.regForm ) {
                    var frm           = opener.document.regForm;
                    frm.rescode.value = '<?php echo $res_code; ?>';
                    frm.resmsg.value  = '<?php echo $res_msg; ?>';
                    frm.target        = '';
                    frm.action        = '<?php echo $this->get_api_url( 'finish' ); ?>';

                    frm.submit();
                }
                self.close();
            }
        </script>
    </head>
    <body onload="javascript:loadForm();">
    </body>
    </html>
<?php else: ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript">
            function loadForm () {
                document.eximbay.submit();
            }
        </script>
    </head>
    <body onload="javascript:loadForm();">
    <form name="eximbay" method="post" action="<?php echo $this->get_api_url( 'finish' ); ?>">
		<?php
		unset( $_REQUEST['type'] );
		unset( $_REQUEST['_wpnonce'] );
		unset( $_REQUEST['woocommerce-login-nonce'] );
		?>
		<?php foreach ( $_REQUEST as $key => $value ) : ?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
		<?php endforeach; ?>
    </form>
    </body>
    </html>
<?php endif; ?>
