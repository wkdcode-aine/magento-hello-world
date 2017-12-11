define(["jquery", "price_filter_selection"], function($, priceSelect){
    'use strict'

    let item_selector = ".js-items.js-price .js-item";

    // forces the user to select only a continuous price range
    $(item_selector + " div").click(function(e) {

        if( !priceSelect.filterClick($(this)) ) {
            e.preventDefault();
            window.location = updateUrl($(this).data('href'));
            return false;
        }
    });

    let updateUrl = url => {
        let request_var = $(".js-items.js-price").data('param');

        let params = [];
        if( url.indexOf(request_var) > -1 && url.indexOf('?') > -1 ) {
            params = url.substring(url.indexOf('?') + 1);
            url = url.substring(0, url.indexOf('?'));
            params = params.split('&');

            $.each(
                params,
                (index, param) => {
                    if( param !== undefined && param.indexOf(request_var) > -1 ) {
                        params.splice(index, 1);
                    }
                }
            );
        }

        if( $(item_selector + ":not(.js-active)").length > 0 ) {
            let param = request_var + "=";
            $(item_selector + ":not(.js-active)").each((index, item) => {
                param += $(item).data('value') + ",";
            });
            params.push(param.substring(0, param.length - 1));
        }

        return url + "?" + params.join('&');
    }

    return $;
})
