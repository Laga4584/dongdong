jQuery(document).ready(function($){

	// Defined Ids of fields which need to lock
    var ids = [
    'minimum_allowed_quantity',
    'maximum_allowed_quantity',
    'group_of_quantity',
    'minmax_do_not_count',
    'minmax_cart_exclude',
    'minmax_category_group_of_exclude'];
	var i;
	
	// loop through all ids and disabled it
    for (i = 0; i < ids.length; i++) {
        $('#'+ids[i]).attr('disabled','disabled');
        $('#'+ids[i]).after($('.wcml_lock_img').clone().removeClass('wcml_lock_img').show());
    }

    // Define Classes of fields which need to lock
    var classes = ['woocommerce_variations .min_max_rules']   
    
    // loop through all classes and disabled it
    for (i = 0; i < classes.length; i++) {
        $('.'+classes[i]).attr('disabled','disabled');
        $('.'+classes[i]).after($('.wcml_lock_img').clone().removeClass('wcml_lock_img').show());
    }
});