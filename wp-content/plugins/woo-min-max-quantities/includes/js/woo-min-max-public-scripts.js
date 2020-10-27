jQuery(document).ready(function ($) {

    // Change value of qty field on change of variation
    jQuery('body').on('show_variation', function (event, variation) {

        if (variation.min_max_rules) {

            var step = variation.step;
            if (variation.step == undefined)
                step = 1;

            jQuery('form.variations_form').find('input[name=quantity]').prop('step', step).prop('min', variation.min_qty).prop('max', variation.max_qty).val(variation.input_value);
        } else {

            var step = variation.step;
            if (variation.step == undefined)
                step = 1;

            var input_value = variation.input_value;
            if( !input_value ) {
                input_value = 1;
            }

            jQuery('form.variations_form').find('input[name=quantity]').prop('step', step).prop('min', variation.min_qty).val(input_value);
        }

    });
});