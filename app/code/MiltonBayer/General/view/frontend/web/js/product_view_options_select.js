define(["jquery", "Magento_Catalog/js/price-box"], function($, priceBox){
    'use strict'

    var selectChange = function( select ) {
        select.find("option[depends-on-value!='']").each(function(index, option) {
            var dependant_on = $("#select_" + $(option).attr('depends-on-option') + ".product-custom-option")

            if( dependant_on.length == 1 ) {

                if( dependant_on.val() == $(option).attr('depends-on-value') ) {
                    $(option).show();
                } else {
                    $(option).hide();

                    if( select.val() == $(option).attr('value') ) {
                        select.val('');
                        select.trigger('change');
                    }
                }
            }
        });
    }

    $(".product-options-wrapper select").on('change', function() {
        $(".product-options-wrapper select[data-dependant='true']").each(function() {
            selectChange( $(this) );
        });
    });

    $(".product-options-wrapper select[data-dependant='true']").each(function() {
        selectChange( $(this) );
    });

    return $;
})
