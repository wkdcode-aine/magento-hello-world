define(["jquery"], function($){
    'use strict'

    let item_selector = ".js-items.js-price .js-item";
    let total_price_items = $(item_selector).length;

    // forces the user to select only a continuous price range
    $(item_selector + " a").click(function(e) {

        let is_selected = $(this).parent().hasClass('js-active');
        let parent_index = $(this).parent().data('index');

        let previous_selected = parent_index > 0 && $(item_selector + "[data-index=" + (parent_index - 1) + "]").hasClass('js-active');
        let next_selected = parent_index < total_price_items && $(item_selector + "[data-index=" + (parent_index + 1) + "]").hasClass('js-active');

        if( is_selected && unselectItems(parent_index, previous_selected, next_selected) ) return true;
        else if( !is_selected && selectItems(parent_index, previous_selected, next_selected) ) return true;

        e.preventDefault();
        window.location = updateUrl($(this).attr('href'));
        return false;
    });

    let unselectItems = (parent_index, previous_selected, next_selected) => {
        if(
            parent_index == 0 ||
            parent_index == total_price_items ||
            ( !previous_selected && next_selected ) ||
            ( previous_selected && !next_selected )
        ) {
            return true;
        }

        let selected_count = $(item_selector + ".js-active").length;
        let first_selected = $(item_selector + ".js-active").first().data('index');
        let last_selected = $(item_selector + ".js-active").last().data('index');

        if( Math.ceil(selected_count / 2) > (parent_index - first_selected) ) {
            $(item_selector).each((index, item) => {
                if(index <= parent_index) {
                    $(item).removeClass('js-active active');
                }
            });
        } else {
            $(item_selector).each((index, item) => {
                if(index >= parent_index) {
                    $(item).removeClass('js-active active');
                }
            });
        }

        return false;
    }

    let selectItems = (parent_index, previous_selected, next_selected) => {
        if( previous_selected || next_selected || $(item_selector + ".js-active").length == 0 )  return true;

        let first_selected = $(item_selector + ".js-active").first().data('index');
        let last_selected = $(item_selector + ".js-active").last().data('index');

        if( parent_index < first_selected ) {
            $(item_selector).each((index, item) => {
                if( index < first_selected && index >= parent_index ) {
                    $(item).addClass('js-active active');
                }
            });
        } else {
            $(item_selector).each((index, item) => {
                if( index > last_selected && index <= parent_index ) {
                    $(item).addClass('js-active active');
                }
            });
        }
    }

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
                    if( param.indexOf(request_var) > -1 ) {
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
