define(["jquery", "Magento_Catalog/js/price-box"], function($, priceBox){
    'use strict'

    var coloursChange = function( input ) {
        $(".js-colours-wrapper .js-colour-swatch").removeClass('active');
        input.parent().addClass('active');

        return input;
    }

    $(".js-colours-wrapper .js-colour-value").on('change', function(event) {
        if( $(this).is(":checked")) {
            coloursChange( $(this) );
        }
    });

    $(".js-colours-wrapper .js-colour-swatch").on('click', function() {
        $(this).find(".js-colour-value")
            .prop('checked', true)
            .trigger('change');
    });

    return $;
})
