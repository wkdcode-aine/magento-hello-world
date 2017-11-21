var config = {
    map: {
        '*': {
            'category_description': 'MiltonBayer_General/js/category/description',
            'product_list_toolbar_sorter' : 'MiltonBayer_General/js/product/list/toolbar/sorter',
            'product_view_options_select': 'MiltonBayer_General/js/product/view/options/select'
        }
    },
    config: {
        mixins: {
            "Magento_Catalog/js/product/list/toolbar" : {
                'MiltonBayer_General/js/product/list/toolbar-mixin': true
            }
        }
    }
};
