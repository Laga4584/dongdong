( function( $, data, wp, ajaxurl ) {				
	var $wc_ast_trackship_form = $("#wc_ast_trackship_form");	
		
	
	var trackship_js = {
		
		init: function() {						
							
			$("#wc_ast_trackship_form").on( 'click', '.woocommerce-save-button', this.save_wc_ast_trackship_form );			
			
			$(".tipTip").tipTip();

		},				
		
		save_wc_ast_trackship_form: function( event ) {
			event.preventDefault();
			
			$("#wc_ast_trackship_form").find(".spinner").addClass("active");
			//$wc_ast_settings_form.find(".success_msg").hide();
			var ajax_data = $("#wc_ast_trackship_form").serialize();
			
			$.post( ajaxurl, ajax_data, function(response) {
				$("#wc_ast_trackship_form").find(".spinner").removeClass("active");
				var snackbarContainer = document.querySelector('#trackship-snackbar');
				var data = {message: trackship_script.i18n.data_saved};
				snackbarContainer.MaterialSnackbar.showSnackbar(data);				
			});
			
		},					
	};
	$(window).load(function(e) {
        trackship_js.init();
    });
})( jQuery, trackship_script, wp, ajaxurl );

jQuery( document ).ready(function() {	
	jQuery('#wc_ast_select_primary_color').wpColorPicker({
		change: function(e, ui) {
			var color = ui.color.toString();		
			jQuery('#tracking_preview_iframe').contents().find('.bg-secondary').css('background-color',color);
			jQuery('#tracking_preview_iframe').contents().find('.tracker-progress-bar-with-dots .secondary .dot').css('border-color',color);
			jQuery('#tracking_preview_iframe').contents().find('.text-secondary').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.progress-bar.bg-secondary:before').css('background-color',color);
			jQuery('#tracking_preview_iframe').contents().find('.tracking-number').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.view_table_rows').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.hide_table_rows').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.tracking-detail.tracking-layout-2').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.view_old_details').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.hide_old_details').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.tracking-table tbody tr td').css('color',color);			
		},
	});		
	jQuery('#wc_ast_select_border_color').wpColorPicker({
		change: function(e, ui) {
			var color = ui.color.toString();		
			jQuery('#tracking_preview_iframe').contents().find('.col.tracking-detail').css('border','1px solid '+color);
		},
	});	
});

jQuery(document).on("change", "#wc_ast_use_tracking_page", function(){
	if(jQuery(this).prop("checked") == true){
		jQuery('.hide_if_disable').show();
		jQuery('.show_if_disable').hide();
		jQuery('#tracking_preview_iframe').height( '' );
		jQuery(this).closest('table').removeClass('disable_tracking_page');		
		setTimeout(
		function() 
		{
			var iframe = document.getElementById("tracking_preview_iframe");
			iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px'; 			
		}, 1000);
	} else{		
		jQuery('.hide_if_disable').hide();
		jQuery('.show_if_disable').show();
		jQuery(this).closest('table').addClass('disable_tracking_page');
	}
});

jQuery(document).on("change", ".select_t_layout_section .radio-img", function(){
	jQuery('#tracking_preview_iframe').height( '' );
	var val = jQuery(this).val();	
	if(val == 't_layout_1'){
		jQuery('#tracking_preview_iframe').contents().find('.tracking-layout-1').show();
		jQuery('#tracking_preview_iframe').contents().find('.tracking-layout-2').hide();
	} else{
		jQuery('#tracking_preview_iframe').contents().find('.tracking-layout-1').hide();
		jQuery('#tracking_preview_iframe').contents().find('.tracking-layout-2').show();
	}		
	var iframe = document.getElementById("tracking_preview_iframe");
	iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';	
});

jQuery('#tracking_preview_iframe').load(function(){
    var iframe = jQuery('#tracking_preview_iframe').contents();
    iframe.find(".view_old_details").click(function(){		
		jQuery('#tracking_preview_iframe').contents().find('.hide_old_details').show();
		jQuery('#tracking_preview_iframe').contents().find('.old-details').fadeIn();
		jQuery('#tracking_preview_iframe').height( '' );
		var iframe1 = document.getElementById("tracking_preview_iframe");		
		iframe1.style.height = iframe1.contentWindow.document.body.scrollHeight + 'px';	
    });
});

jQuery('#tracking_preview_iframe').load(function(){
    var iframe = jQuery('#tracking_preview_iframe').contents();
    iframe.find(".hide_old_details").click(function(){		
		jQuery('#tracking_preview_iframe').contents().find('.view_old_details').show();
		jQuery('#tracking_preview_iframe').contents().find('.old-details').fadeOut();	
		jQuery('#tracking_preview_iframe').height( '' );
		var iframe1 = document.getElementById("tracking_preview_iframe");
		iframe1.style.height = iframe1.contentWindow.document.body.scrollHeight + 'px';	
    });
});

jQuery(document).on("click", "#wc_ast_hide_tracking_provider_image", function(){	
	if(jQuery(this).prop("checked") == true){		
		jQuery('#tracking_preview_iframe').contents().find('.provider-image-div').hide();
	} else{
		jQuery('#tracking_preview_iframe').contents().find('.provider-image-div').show();
	}	
});
jQuery(document).on("click", "#wc_ast_hide_tracking_events", function(){
	jQuery('#tracking_preview_iframe').height( '' );	
	if(jQuery(this).prop("checked") == true){		
		jQuery('#tracking_preview_iframe').contents().find('.shipment_progress_div').hide();
		jQuery('#tracking_preview_iframe').contents().find('.tracking-details').hide();
	} else{
		jQuery('#tracking_preview_iframe').contents().find('.shipment_progress_div').show();
		jQuery('#tracking_preview_iframe').contents().find('.tracking-details').show();
	}	
	var iframe = document.getElementById("tracking_preview_iframe");
	iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';	
});
jQuery(document).on("click", "#wc_ast_remove_trackship_branding", function(){
	jQuery('#tracking_preview_iframe').height( '' );	
	if(jQuery(this).prop("checked") == true){		
		jQuery('#tracking_preview_iframe').contents().find('.trackship_branding').hide();
	} else{
		jQuery('#tracking_preview_iframe').contents().find('.trackship_branding').show();
	}	
	var iframe = document.getElementById("tracking_preview_iframe");
	iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';	
});
jQuery(document).on("click", ".tracking_page_label", function(){		
	setTimeout(
	function() 
	{
		jQuery('#tracking_preview_iframe').height( '' );
		var iframe = document.getElementById("tracking_preview_iframe");
		iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px'; 		
	}, 1000);	
});	
jQuery( document ).ready(function() {	
	if(jQuery('#wc_ast_use_tracking_page').prop("checked") == true){
		jQuery('.hide_if_disable').show();
		jQuery('.show_if_disable').hide();	
	} else{
		jQuery('.hide_if_disable').hide();
		jQuery('.show_if_disable').show();
	}
	if(jQuery('#wc_ast_use_tracking_page').prop("checked") == true){
		jQuery('#wc_ast_use_tracking_page').closest('table').removeClass('disable_tracking_page');
	} else{
		jQuery('#wc_ast_use_tracking_page').closest('table').addClass('disable_tracking_page');
	}	
});
jQuery(function(){
    jQuery('#tracking_preview_iframe').load(function(){
		var tab = getUrlParameter('tab');
		if(tab == 'tracking-page'){
			jQuery(this).show();
			var iframe = document.getElementById("tracking_preview_iframe");
			iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';        
		} else{
			jQuery(this).show();
		}		
    });       
});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

jQuery(document).on("click", ".tab_input", function(){
	var tab = jQuery(this).data('tab');
	var label = jQuery(this).data('label');
	jQuery('.zorem-layout__header-breadcrumbs .header-breadcrumbs-last').text(label);
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=trackship-for-woocommerce&tab="+tab;
	window.history.pushState({path:url},'',url);	
});

jQuery(document).click(function(){
	var $trigger = jQuery(".trackship_dropdown");
    if($trigger !== event.target && !$trigger.has(event.target).length){
		jQuery(".trackship-dropdown-content").hide();
    }   
});

jQuery(document).on("click", ".trackship-dropdown-menu", function(){	
	jQuery('.trackship-dropdown-content').show();
});

jQuery(document).on("click", ".trackship-dropdown-content li a", function(){
	var tab = jQuery(this).data('tab');
	var label = jQuery(this).data('label');
	var section = jQuery(this).data('section');
	jQuery('.inner_tab_section').hide();
	jQuery('.trackship_nav_div').find("[data-tab='" + tab + "']").prop('checked', true);
	jQuery('#'+section).show();
	jQuery('.zorem-layout__header-breadcrumbs .header-breadcrumbs-last').text(label);
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=trackship-for-woocommerce&tab="+tab;
	window.history.pushState({path:url},'',url);
	jQuery(".trackship-dropdown-content").hide();
});

jQuery(document).on("click", ".bulk_shipment_status_button", function(){
	jQuery("#content3").block({
		message: null,
		overlayCSS: {
			background: "#fff",
			opacity: .6
		}	
    });	
	var ajax_data = {
		action: 'bulk_shipment_status_from_settings',		
	};
	jQuery.ajax({
		url: ajaxurl,		
		data: ajax_data,		
		type: 'POST',		
		success: function(response) {
			jQuery("#content3").unblock();
			jQuery( '.bulk_shipment_status_success' ).show();
			jQuery( '.bulk_shipment_status_button' ).attr("disabled", true)
			//window.location.href = response;			
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});

jQuery(document).on("click", ".bulk_shipment_status_button_for_empty_balance", function(){
	jQuery("#content3").block({
		message: null,
		overlayCSS: {
			background: "#fff",
			opacity: .6
		}	
    });	
	var ajax_data = {
		action: 'bulk_shipment_status_for_empty_balance_from_settings',		
	};
	jQuery.ajax({
		url: ajaxurl,		
		data: ajax_data,		
		type: 'POST',		
		success: function(response) {
			jQuery("#content3").unblock();
			jQuery( '.bulk_shipment_status_button_for_empty_balance' ).after( "<div class='bulk_shipment_status_success'>Tracking info sent to Trackship for all Orders.</div>" );
			jQuery( '.bulk_shipment_status_button_for_empty_balance' ).attr("disabled", true);
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});

jQuery(document).on("click", ".bulk_shipment_status_button_for_connection_issue", function(){
	jQuery("#content3").block({
		message: null,
		overlayCSS: {
			background: "#fff",
			opacity: .6
		}	
    });	
	var ajax_data = {
		action: 'bulk_shipment_status_for_do_connection_from_settings',		
	};
	jQuery.ajax({
		url: ajaxurl,		
		data: ajax_data,		
		type: 'POST',		
		success: function(response) {
			jQuery("#content3").unblock();
			jQuery( '.bulk_shipment_status_button_for_connection_issue' ).after( "<div class='bulk_shipment_status_success'>Tracking info sent to Trackship for all Orders.</div>" );
			jQuery( '.bulk_shipment_status_button_for_connection_issue' ).attr("disabled", true);
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});
jQuery(document).on("click", ".open_ts_video", function(){
	jQuery('.ts_video_popup').show();	 
});
jQuery(document).on("click", ".ts_video_popup .popupclose", function(){
	jQuery('#ts_video').each(function(index) {
		jQuery(this).attr('src', jQuery(this).attr('src'));
		return false;
    });
	jQuery('.ts_video_popup').hide();
});
jQuery(document).on("click", ".tool_link", function(){
	jQuery('#tab_tools').trigger( "click" );
});
jQuery(document).on("change", "#wc_ast_trackship_page_id", function(){
	var wc_ast_trackship_page_id = jQuery(this).val();
	if(wc_ast_trackship_page_id == 'other'){
		jQuery('.trackship_other_page_fieldset').show();
	} else{
		jQuery('.trackship_other_page_fieldset').hide();
	}
});

jQuery(document).on("change", ".shipment_status_toggle input", function(){
	jQuery("#content5 ").block({
    message: null,
    overlayCSS: {
        background: "#fff",
        opacity: .6
	}	
    });
	if(jQuery(this).prop("checked") == true){
		var wcast_enable_status_email = 1;
		jQuery(this).closest('tr').addClass('enable');
		jQuery(this).closest('tr').removeClass('disable');
	} else{
		jQuery(this).closest('tr').addClass('disable');
		jQuery(this).closest('tr').removeClass('enable');	
	}
	var settings_data = jQuery(this).data("settings");
	
	var id = jQuery(this).attr('id');
	var ajax_data = {
		action: 'update_shipment_status_email_status',
		id: id,
		wcast_enable_status_email: wcast_enable_status_email,
		settings_data: settings_data,		
	};
	jQuery.ajax({
		url: ajaxurl,		
		data: ajax_data,
		type: 'POST',
		success: function(response) {	
			jQuery("#content5 ").unblock();						
		},
		error: function(response) {					
		}
	});
});