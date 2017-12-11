define(["jquery"], function($){
    'use strict'

    let last_param_str = ""

    return {
        get: () => {
            let params = {};

            $(".js-items").each((index, list) => {
                let key = $(list).data('param');

                if( $(list).find('.js-item:not(.js-active)').length > 0 ) {
                    params[key] = [];

                    $(list).find(".js-item:not(.js-active)").each((unused, item) => {
                        params[key].push($(item).data('value'));
                    });
                }
            });

            $.each(
                params,
                (key, values) => {
                    params[key] = values.join(",");
                }
            );

            params.cat = $("input#category_id").val();

            console.log(params.cat);

            let _params = []
            $.each(params, (key, values) => _params.push(key + "=" + values) );

            if( _params.join("&") != last_param_str ) {

                $(".js-tab-pane.js-door-search .js-results").html("loading");

                $.ajax({
                    url: '/ajax/designadoor/doorlist',
                    method: 'POST',
                    data: params,
                    success: ( response ) => {
                        $(".js-tab-pane.js-door-search .js-results").html(response);

                        last_param_str = _params.join("&");

                        console.log(last_param_str);
                    }
                });
            }
        }
    };
});
