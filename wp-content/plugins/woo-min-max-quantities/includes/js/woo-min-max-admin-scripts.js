jQuery(document).ready(function ($) {	
	
	jQuery( '.woocommerce_variations' ).on( 'change', '.checkbox.min_max_rules', function() {
		if ( jQuery( this ).is( ':checked' ) ) {
			jQuery( this ).parents( '.woocommerce_variable_attributes' ).find( '.min_max_rules_options' ).show();
		} else {
			jQuery( this ).parents( '.woocommerce_variable_attributes' ).find( '.min_max_rules_options' ).hide();
		}
	});
});