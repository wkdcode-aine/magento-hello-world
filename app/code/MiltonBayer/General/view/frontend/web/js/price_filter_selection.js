define(["jquery"], function($){
    'use strict'

    let _this = {}

    let item_selector = ".js-items.js-price .js-item";
    let total_price_items = $(item_selector).length;

    _this.filterClick = element => {
        let is_selected = $(element).parent().hasClass('js-active');
        let parent_index = $(element).parent().data('index');

        let previous_selected = parent_index > 0 && $(item_selector + "[data-index=" + (parent_index - 1) + "]").hasClass('js-active');
        let next_selected = parent_index < total_price_items && $(item_selector + "[data-index=" + (parent_index + 1) + "]").hasClass('js-active');

        if( is_selected && _this.unselectItems(parent_index, previous_selected, next_selected) ) return true;
        else if( !is_selected && _this.selectItems(parent_index, previous_selected, next_selected) ) return true;

        return false;
    };

    _this.unselectItems = (parent_index, previous_selected, next_selected) => {
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
    };

    _this.selectItems = (parent_index, previous_selected, next_selected) => {
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
    };

    return {
        filterClick: _this.filterClick
    }
});
