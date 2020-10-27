<div id="woo_min_max_qty" class="woocommerce_options_panel panel wc-metaboxes-wrapper">
	<div class="options_group">
		<?php  
			global $woocommerce, $thepostid, $post;        
        	
        	$thepostid = empty( $thepostid ) ? $post->ID : $thepostid;

        	// minimum allowed quantity
	        woocommerce_wp_text_input(
	        	array(
	        		'id' => 'minimum_allowed_quantity', 
	        		'label' => __('Minimum quantity', 'woo_min_max_quantities'), 
	        		'description' => __('Enter a quantity to prevent the user buying this product if they have fewer than the allowed quantity in their cart', 'woo_min_max_quantities'),
	        		'desc_tip' => true
	    		)
			);
			
			// maximum allowed quantity
	        woocommerce_wp_text_input(
	        	array(
	        		'id' => 'maximum_allowed_quantity', 
	        		'label' => __('Maximum quantity', 'woo_min_max_quantities'), 
	        		'description' => __('Enter a quantity to prevent the user buying this product if they have more than the allowed quantity in their cart', 'woo_min_max_quantities'),
	        		'desc_tip' => true
	    		)
			);
			
			// Group of quantity
	        woocommerce_wp_text_input(
	        	array(
	        		'id' => 'group_of_quantity', 
	        		'label' => __('Group of (Step)...', 'woo_min_max_quantities'),
	        		'description' => __('Enter a quantity to only allow this product to be purchased in groups of X', 'woo_min_max_quantities'),
	        		'desc_tip' => true
				)
			);
			
			// Do not count product in order rules
	        woocommerce_wp_checkbox(
	        	array(
	        		'id' => 'minmax_do_not_count', 
	        		'label' => __('Order rules: Do not count', 'woo_min_max_quantities'), 
	        		'description' => __('Don\'t count this product against your minimum order quantity/value rules.', 'woo_min_max_quantities')
	    		)
			);
			
			// Exlude Order rules
	        woocommerce_wp_checkbox(
	        	array(
	        		'id' => 'minmax_cart_exclude', 
	        		'label' => __('Order rules: Exclude', 'woo_min_max_quantities'), 
	        		'description' => __('Exclude this product from minimum order quantity/value rules. If this is the only item in the cart, rules will not apply.', 'woo_min_max_quantities')
	    		)
			);
			
			// exlude group of category
	        woocommerce_wp_checkbox(
	        	array(
		        	'id' => 'minmax_category_group_of_exclude', 
		        	'label' => __('Category rules: Exclude', 'woo_min_max_quantities'), 
		        	'description' => __('Exclude this product from category group-of-quantity rules. This product will not be counted towards category groups.', 'woo_min_max_quantities')
	    		)
			);

			do_action('woo_min_max_qty_after_settings');
		?>
	</div>
</div>