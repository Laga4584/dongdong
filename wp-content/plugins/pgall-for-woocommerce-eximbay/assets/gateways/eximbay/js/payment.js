jQuery(function(a){"use strict";function b(a){var b=a[0];return b.eximbay=new c,b}var c=function(){this.$form=null,this.init=function(a,b){this.$form=a,this.$form.on(b,this.process_payment)},this.process_payment=function(b,c){if("popup"===_eximbayfw.payment_window_mode)if(window.open("","payment2","resizable=yes,scrollbars=yes,width=820,height=600")){document.getElementById("payment_form_eximbay")||a(document.body).append('<div id="payment_form_eximbay"></div>');var d=a("#payment_form_eximbay");d.empty().append(c.payment_form);var e=document.regForm;e.target="payment2",e.submit()}else alert(_pafw.i18n.popup_block_message),a.fn.pafw_block_controller.unblock();else document.getElementById("payment_form_eximbay")||a(document.body).append('<div id="payment_form_eximbay"></div>'),a("#payment_form_eximbay").empty().append(c.payment_form),document.regForm.submit()}};a("body").on("pafw_init_hook",function(){void 0!==a.fn.pafw_hook&&a.fn.pafw_hook.add_filter("pafw_gateway_objects",b)})});