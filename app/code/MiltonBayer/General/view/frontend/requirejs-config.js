var config = {
    map: {
        '*': {
            'category_description': 'MiltonBayer_General/js/category/description',
            'product_list_toolbar_sorter' : 'MiltonBayer_General/js/product/list/toolbar/sorter',
            'product_view_options_select': 'MiltonBayer_General/js/product/view/options/select',
            'price_filter': 'MiltonBayer_General/js/price_filter',
            'category_door_configurator': 'MiltonBayer_General/js/category/door-configurator'
        }
    },
    config: {
        mixins: {
            "Magento_Catalog/js/product/list/toolbar" : {
                'MiltonBayer_General/js/product/list/toolbar-mixin': true
            },
            "MiltonBayer_General/js/category/door-configurator": {
                "MiltonBayer_General/js/category/door-configurator/door-select-mixin": true,
                "MiltonBayer_General/js/category/door-configurator/door-size-mixin": true,
            }
        }
    }
};
