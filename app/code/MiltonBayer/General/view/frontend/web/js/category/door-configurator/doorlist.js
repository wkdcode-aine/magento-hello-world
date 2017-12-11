define(["jquery"], function($){
    'use strict'

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

            $.ajax({
                url: '/ajax/designadoor/doorlist',
                method: 'POST',
                data: params,
                success: ( response ) => {
                    console.log(response);
                    $(".js-tab-pane.js-door-search .js-results").html(response);
                }
            })
        }
    };
});
