<?php

// Include Jupiter X.
require_once( get_template_directory() . '/lib/init.php' );

/**
 * Enqueue assets.
 *
 * Add theme style and script to Jupiter X assets files.
 */
jupiterx_add_smart_action( 'wp_enqueue_scripts', 'jupiterx_child_enqueue_scripts', 8 );

function jupiterx_child_enqueue_scripts() {

	// Add the theme style as a fragment to have access to all the variables.
	jupiterx_compiler_add_fragment( 'jupiterx', get_stylesheet_directory_uri() . '/assets/less/style.less', 'less' );

	// Add the theme script.
	wp_enqueue_script('jupiterx-child', get_stylesheet_directory_uri() . '/assets/js/script.js', [ 'jquery' ], '', true );
}

/**
 * Example 1
 *
 * Modify markups and attributes.
 */
// jupiterx_add_smart_action( 'wp', 'jupiterx_setup_document' );

function jupiterx_setup_document() {

	// Header
	jupiterx_add_attribute( 'jupiterx_header', 'class', 'jupiterx-child-header' );

	// Breadcrumb
	jupiterx_remove_action( 'jupiterx_breadcrumb' );

	// Post image
	jupiterx_modify_action_hook( 'jupiterx_post_image', 'jupiterx_post_header_before_markup' );

	// Post read more
	jupiterx_replace_attribute( 'jupiterx_post_more_link', 'class' , 'btn-outline-secondary', 'btn-danger' );

	// Post related
	jupiterx_modify_action_priority( 'jupiterx_post_related', 11 );

}

/**
 * Example 2
 *
 * Modify the sub footer credit text.
 */
// jupiterx_add_smart_action( 'jupiterx_subfooter_credit_text_output', 'jupiterx_child_modify_subfooter_credit' );

function jupiterx_child_modify_subfooter_credit() { ?>

	<a href="https//jupiterx.com" target="_blank">Jupiter X Child</a> theme for <a href="http://wordpress.org" target="_blank">WordPress</a>

<?php }

// Hook in
add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

// Our hooked in function - $address_fields is passed via the filter!
function custom_override_default_address_fields( $address_fields ) {
     $address_fields['first_name']['label'] = 'ªªÙ£îñ(àó)';
        $address_fields['last_name']['label'] = 'ªªÙ£îñ(Ù£)';
        $address_fields['company']['label'] = 'ªªÙ£îñ(«««Ê)';
		$address_fields['company']['required'] = true;
		$address_fields['country']['label'] = 'ÏÐÊ«';
        $address_fields['address_1']['label'] = 'ñ¬á¶1';
		$address_fields['address_1']['placeholder'] = 'Ô´Ô³Ý¤?, ã¼?ïëõ½';
        $address_fields['address_2']['label'] = 'ñ¬á¶2';
		$address_fields['address_2']['placeholder'] = 'Ûãò¢, ËïÚªÙ£';
		$address_fields['address_2']['required'] = true;
		$address_fields['postcode']['label'] = 'éèøµÛã?';
		

     return $address_fields;
}
/* WooCommerce: The Code Below Removes address edit Fields */
add_filter( 'woocommerce_default_address_fields', 'oks_custom_remove_fields_on_edit_address' );
function oks_custom_remove_fields_on_edit_address($fields) {
       unset( $fields ['city'] );
	   unset( $fields ['state'] );
	   
        return $fields;
}

