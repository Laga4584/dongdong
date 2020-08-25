<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$secret_key     = pafw_get( $this->settings, 'secret_key', "289F40E6640124B2628640168C3C5464" );
$request_url    = $this->get_request_url();
$fgkey          = "";
$sorting_params = "";
$hash_map       = array ();

foreach ( $_POST as $Key => $value ) {
	$hash_map[ $Key ] = $value;
}
$size = count( $hash_map );
ksort( $hash_map );
$counter = 0;

foreach ( $hash_map as $key => $val ) {
	if ( $counter == $size - 1 ) {
		$sorting_params .= $key . "=" . $val;
	} else {
		$sorting_params .= $key . "=" . $val . "&";
	}
	++ $counter;
}

$link_buffer = $secret_key . "?" . $sorting_params;
$fgkey       = hash( "sha256", $link_buffer );

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body leftmargin="0" topmargin="0" align="center" onload="javascript:document.regForm.submit();">
        <form name="regForm" method="post" action="<?php echo $this->get_request_url(); ?>">
            <input type="hidden" name="fgkey" value="<?php echo $fgkey; ?>"/>

            <?php
            foreach ( $_POST as $key => $value ) {
                ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
            <?php } ?>
        </form>
    </body>
</html>

