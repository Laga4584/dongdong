<?php

// Include Jupiter X.
require_once( get_template_directory() . '/lib/init.php' );

/**
 * Enqueue assets.
 *
 * Add theme style and script to Jupiter X assets files.
 */

/*YE editted===========================================*/


function yith_add_loop_wishlist(){
	alert("sorry :-(");
    echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
} 
add_action( 'woocommerce_shop_loop_item' , 'yith_add_loop_wishlist', 15  );


function php_execute($html){
	if(strpos($html,"<"."?php")!==false){ ob_start(); eval("?".">".$html);
		$html=ob_get_contents();
		ob_end_clean();
		}
	return $html;
}
add_filter('widget_text','php_execute',100);



add_action('wp_login_failed', 'redirect_login_failed');
	function redirect_login_failed() {
	    wp_redirect(get_bloginfo('url') . '/login-failed' );
}


function check_user($atts, $content = null) {
     
    if($atts['for_not_logged_in'] == "yes") {
        //check that the user is logged in
        if ( is_user_logged_in() ){
            //user IS logged in so HIDE the content
            return;
        }
        else {
            //user IS NOT logged in so SHOW the content
            return $content;
        }
    } else {
        //Otherwise no attributes in shortcode so default behaviour (show only to those logged in)
        //check that the user is logged in
        if ( is_user_logged_in() ){
            //user IS logged in so SHOW the content
            return $content;
        }
        else {
            //user IS NOT logged in so HIDE the content
            return;
        }
    }   
}
 
//add a shortcode which calls the above function
add_shortcode('loggedin', 'check_user' );

/*YE editted===========================================*/


function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

jupiterx_add_smart_action( 'wp_enqueue_scripts', 'jupiterx_child_enqueue_scripts', 8 );



function jupiterx_child_enqueue_scripts() {

	// Add the theme style as a fragment to have access to all the variables.
	jupiterx_compiler_add_fragment( 'jupiterx', get_stylesheet_directory_uri() . '/assets/less/style.less', 'less' );

	// Add the theme script as a fragment.
	jupiterx_compiler_add_fragment( 'jupiterx', get_stylesheet_directory_uri() . '/assets/js/script.js', 'js' );

}

/**
 * Search Product(s) By SKU Woocommerce Product ADMIN
 */
function m_request_query( $query_vars ) {

	global $typenow;
	global $wpdb;
	global $pagenow;

	if ( 'product' === $typenow && isset( $_GET['s'] ) && 'edit.php' === $pagenow ) {
		$search_term  = esc_sql( sanitize_text_field( $_GET['s'] ) );
    // Split the search term by comma.
		$search_terms = explode( ',', $search_term );
    // If there are more terms make sure we also search for the whole thing, maybe it's not a list of terms.
		if ( count( $search_terms ) > 1 ) {
			$search_terms[] = $search_term;
		}
    // Cleanup the array manually to avoid issues with quote escaping.
		array_walk( $search_terms, 'trim' );
		array_walk( $search_terms, 'esc_sql' );
		$meta_key               = '_sku';
		$post_types             = array( 'product', 'product_variation' );
		$query                  = "SELECT DISTINCT posts.ID as product_id, posts.post_parent as parent_id FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id WHERE postmeta.meta_key = '{$meta_key}' AND postmeta.meta_value IN  ('" . implode( "','", $search_terms ) . "') AND posts.post_type IN ('" . implode( "','", $post_types ) . "') ORDER BY posts.post_parent ASC, posts.post_title ASC";
		$search_results         = $wpdb->get_results( $query );
		$product_ids            = wp_parse_id_list( array_merge( wp_list_pluck( $search_results, 'product_id' ), wp_list_pluck( $search_results, 'parent_id' ) ) );
		$query_vars['post__in'] = array_merge( $product_ids, $query_vars['post__in'] );
	}

	return $query_vars;
}

add_filter( 'request', 'm_request_query', 20 );

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     $fields['billing']['billing_phone']['label'] = '携帯番号';
	 $fields['billing']['billing_email']['label'] = 'メールアドレス';
	 $fields['order']['order_comments']['label'] = 'その他お問い合わせ';
	 
     return $fields;
}

// Hook in
add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

// Our hooked in function - $address_fields is passed via the filter!
function custom_override_default_address_fields( $address_fields ) {
     $address_fields['first_name']['label'] = 'お名前(姓)';
        $address_fields['last_name']['label'] = 'お名前(名)';
        $address_fields['company']['label'] = 'お名前(カナ)';
		$address_fields['company']['required'] = true;
		$address_fields['country']['label'] = '國家';
        $address_fields['address_1']['label'] = '住所1';
		$address_fields['address_1']['placeholder'] = '都道府県, 市区町村';
        $address_fields['address_2']['label'] = '住所2';
		$address_fields['address_2']['placeholder'] = '番地, 建物名';
		$address_fields['address_2']['required'] = true;
		$address_fields['postcode']['label'] = '郵便番号';
		

     return $address_fields;
}
/* WooCommerce: The Code Below Removes address edit Fields */
add_filter( 'woocommerce_default_address_fields', 'oks_custom_remove_fields_on_edit_address' );
function oks_custom_remove_fields_on_edit_address($fields) {
       unset( $fields ['city'] );
	   unset( $fields ['state'] );
	   
        return $fields;
}

// KEVIN's ADDON (GA ADDON & A8)

add_action( 'woocommerce_thankyou', 'bct_wc_ga_integration' );
function bct_wc_ga_integration( $order_id ) {
	$order = new WC_Order( $order_id );
?>

	<!--Adding Order Information to Google Analytics-->
	<script type="text/javascript">
	__gaTracker('require', 'ecommerce', 'ecommerce.js');

	// Transaction Details
	__gaTracker('ecommerce:addTransaction', {
		'id': '<?php echo $order_id;?>',
		'affiliation': '<?php echo get_option( "blogname" );?>',
		'revenue': '<?php echo $order->get_total();?>',
		'shipping': '<?php echo $order->get_total_shipping();?>',
		'tax': '<?php echo $order->get_total_tax();?>',
		'currency': '<?php echo get_woocommerce_currency();?>'
	});

	<?php
	// Item Details
	if ( sizeof( $order->get_items() ) > 0 ) {
		foreach( $order->get_items() as $item ) {
			$product_cats = get_the_terms( $item["product_id"], 'product_cat' );
				if ($product_cats) {
					$cat = $product_cats[0];
				}?>
			__gaTracker('ecommerce:addItem', {
				'id': '<?php echo $order_id;?>',
				'name': '<?php echo $item['name'];?>',
				'sku': '<?php echo get_post_meta($item["product_id"], '_sku', true);?>',
				'category': '<?php echo $cat->name;?>',
				'price': '<?php echo $item['line_subtotal'];?>',
				'quantity': '<?php echo $item['qty'];?>',
				'currency': '<?php echo get_woocommerce_currency();?>'
			});
		<?php
		}
	} ?>
	__gaTracker('ecommerce:send');
	</script>
	<!--End of Code for Google Analytics-->

	<!--Importing JQuery-->
	<script
	  src="https://code.jquery.com/jquery-3.2.1.js"
	  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
	  crossorigin="anonymous"></script>
	<!--End of Import-->

	<!--Conversion JS Tag-->
	<span id="a8sales"></span>
	<script src="//statics.a8.net/a8sales/a8sales.js"></script>
	<script>
  a8sales({
		"pid":'s00000017644001', // SeoulLife A8 ID
		"order_number":'<?php echo $order_id ?>', // Order ID
		"currency":'<?php echo get_woocommerce_currency(); ?>', // Order Currency
		"items":[
<?php
    $counter = 0;
		$total_price = 0;
    foreach( $order->get_items() as $items ){
			$productinfo = $order->get_product_from_item($items);
	    $counter = $counter + 1;
?>
      {
				"code":'<?php echo get_post_meta($items["product_id"], '_sku', true);?>',
				"price":<?php echo $productinfo->get_regular_price()?>,
				"quantity":<?php echo $items['qty'];?>
			}
<?php
			$item_sum = $productinfo->get_regular_price() * $items['qty'];
			$total_price = $item_sum + $total_price;
      if ( sizeof( $order->get_items() ) > $counter ) {
        echo ",";
      }
    }
?>
		],
		"total_price":<?php echo $total_price;?> // Sub Total Price
	});
	</script>

<?php
}
?>

