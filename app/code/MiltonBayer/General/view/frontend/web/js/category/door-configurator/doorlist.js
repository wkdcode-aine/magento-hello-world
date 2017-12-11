define(["jquery", 'price_filter_selection'], function($, priceSelect){
    'use strict'

    let last_param_str = ""
    let _this = {};

    _this.setup = () => {
        $(".js-items:not(.js-price) .js-item div").click(e => {
            $(e.currentTarget).parent().toggleClass('js-active active');
            _this.getList();
        });

        // forces the user to select only a continuous price range
        $(".js-items.js-price .js-item div").click(e => {
            priceSelect.filterClick(e.currentTarget);

            $(".js-manufacturer-options").val('');
            _this.getList();
        });

        $(".js-filter-clear").click(e => {
            $(".js-items .js-item").addClass('active js-active');
            $(".js-manufacturer-options").val('');
            _this.getList();
        })

        _this.getList();
    };

    _this.getList = () => {
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

        if( $(".js-manufacturer-options").length > 0 && $(".js-manufacturer-options").val() !== '' ) params.manufacturer = $(".js-manufacturer-options").val();

        params.cat = $("input#category_id").val();

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

                    $(".js-manufacturer-options").bind('change', e => _this.getList());
                }
            });
        }
    };

    return {
        getList: _this.getList,
        setup: _this.setup,
    };
});
