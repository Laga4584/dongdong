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

add_filter( 'woocommerce_checkout_coupon_message', 'bbloomer_have_coupon_message');
 
function bbloomer_have_coupon_message() {
return 'ク―ポンをお持ちですか？<a href="#" class="showcoupon">ここにお持ちのク―ポンをご入力ください！</a>';
}

add_filter( 'gettext', 'woocommerce_change_coupon_field_instruction_text' );

function woocommerce_change_coupon_field_instruction_text($translated) {
$translated = str_ireplace('If you have a coupon code, please apply it below.', 'ここにお持ちのク―ポンをご入力ください', $translated);
return $translated;
}

add_filter( 'woocommerce_cart_totals_coupon_label', 'bt_rename_coupon_label',10, 1 );

function bt_rename_coupon_label( $err, $err_code=null, $something=null ){
	$err = str_ireplace("Coupon","ク―ポン",$err);
	return $err;
}



add_filter( 'gettext', 'my_text_strings', 20, 3 );

function my_text_strings( $translated_text, $text, $domain ) {
    switch ( $translated_text ) {
        case 'Apply coupon' :
            $translated_text = __( 'ク―ポン適用', 'woocommerce' );
            break;
			
		case 'Coupon code' :
            $translated_text = __( 'ク―ポンコ―ド', 'woocommerce' );
            break;
			
		case 'Update cart' :
            $translated_text = __( 'カ―ト更新', 'woocommerce' );
            break;
		
		case 'Continue Shopping' :
            $translated_text = __( 'ショッピングを続ける', 'woocommerce' );
            break;
			
		case 'Proceed to checkout' :
            $translated_text = __( '会計する', 'woocommerce' );
            break;
		
		case 'Product' :
            $translated_text = __( '商品', 'woocommerce' );
            break;
			
		case 'Price' :
            $translated_text = __( '価格', 'woocommerce' );
            break;
			
		case 'Quantity' :
            $translated_text = __( '個数', 'woocommerce' );
            break;
			
		case 'Subtotal' :
            $translated_text = __( '小計', 'woocommerce' );
            break;
			
			
		case 'Total' :
            $translated_text = __( '総計', 'woocommerce' );
            break;
		
		case 'Billing details' :
            $translated_text = __( '決済情報', 'woocommerce' );
            break;
			
		case 'Shipping details' :
            $translated_text = __( '配送情報', 'woocommerce' );
            break;
			
		case 'Add to cart' :
            $translated_text = __( 'カートに入れる', 'woocommerce' );
            break;
			
		case 'Choose an option' :
            $translated_text = __( 'オプション選択', 'woocommerce' );
            break;
			
		case 'Select options' :
            $translated_text = __( 'オプション選択', 'woocommerce' );
            break;
		
		case 'Description' :
            $translated_text = __( '商品詳細', 'woocommerce' );
            break;
			
		case 'Reviews' :
            $translated_text = __( '購入レビュー', 'woocommerce' );
            break;
			
			
		case 'Proceed to PayPal' :
            $translated_text = __( 'ペイパルに進む', 'woocommerce' );
            break;
		
		case 'First name' :
            $translated_text = __( '名前', 'woocommerce' );
            break;
		
		case 'Last name' :
            $translated_text = __( '姓', 'woocommerce' );
            break;
		
		case 'Display name' :
            $translated_text = __( 'ニックネーム', 'woocommerce' );
            break;
			
		case 'This will be how your name will be displayed in the account section and in reviews' :
            $translated_text = __( 'ニックネームは、お客様のアカウントやレビューに一緒に表示されます。', 'woocommerce' );
            break;
			
		case 'Email address' :
            $translated_text = __( 'メールアドレス', 'woocommerce' );
            break;
		
		case 'Password change' :
            $translated_text = __( 'パスワード変更', 'woocommerce' );
            break;
		case 'Current password (leave blank to leave unchanged)' :
            $translated_text = __( '現在パスワード（変更を希望しない場合、空白のままにしておいてください。）', 'woocommerce' );
            break;
		case 'New password (leave blank to leave unchanged)' :
            $translated_text = __( '新パスワード（変更を希望しない場合、空白のままにしておいてください。）', 'woocommerce' );
            break;
		case 'Confirm new password' :
            $translated_text = __( '新パスワード確認', 'woocommerce' );
            break;
		case 'Save changes' :
            $translated_text = __( '変更事項保存', 'woocommerce' );
            break;
		case 'Please enter your current password.' :
            $translated_text = __( 'お客様の現在のパスワードを入力してください。', 'woocommerce' );
            break;
			
		case 'is a required field.' :
            $translated_text = __( 'は必須項目です。', 'woocommerce' );
            break;
    }
    return $translated_text;
}


add_filter('wpmenucart_viewcarttext', 'wpmenucart_view_cart_text' );
function wpmenucart_view_cart_text ( $text ) {
    $text = 'カートをご確認ください :)';
    return $text;
}

add_filter( 'woocommerce_shipping_package_name', 'custom_shipping_package_name' );
function custom_shipping_package_name( $name ) {
  return '配送料';
}

add_filter( 'woocommerce_cart_shipping_method_full_label', 'wc_ninja_change_flat_rate_label', 10, 2 );
function wc_ninja_change_flat_rate_label( $label, $method ) {
	if ( 'flat_rate' === $method->method_id && $method->cost == 0 ) {
		$label = "定額料金";
	}

	return $label;
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

