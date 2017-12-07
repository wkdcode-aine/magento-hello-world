define([
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {

        $.widget('mage.productListToolbarForm', widget, {

            /** @inheritdoc */
            _create: function () {
                this.options.orderDefault = '0';
                this.options.manufacturerDefault = '';
                this.options.manufacturerControl = '[data-role="manufacturer"]';
                this.options.manufacturer = 'manufacturer';

                $(this.options.manufacturerControl).off('change');
                this._bind($(this.options.modeControl), this.options.mode, this.options.modeDefault);
                this._bind($(this.options.limitControl), this.options.limit, this.options.limitDefault);
                this._bind($(this.options.manufacturerControl), this.options.manufacturer, this.options.manufacturerDefault);
                this._bindMulti($('.js-sort-select'), this.options.order, this.options.direction, 'price-asc');
            },

            /** @inheritdoc */
            _bindMulti: function (element, orderParam, directionParam, defaultValue) {
                element.off('change');
                element.on('change', {
                    orderParam: orderParam,
                    directionParam: directionParam,
                    'default': defaultValue
                }, $.proxy(this._processMultiSelect, this));
            },

            /**
             * @param {jQuery.Event} event
             * @private
             */
            _processMultiSelect: function (event) {
                event.preventDefault();
                this.updateUrl(
                    event.data.orderParam,
                    event.data.directionParam,
                    event.currentTarget.value,
                    event.data.default
                );
            },

            /**
             * @param {String} paramName
             * @param {*} paramValue
             * @param {*} defaultValue
             */
            updateUrl: function (orderParam, directionParam, paramValue, defaultValue) {
                var decode = window.decodeURIComponent,
                    urlPaths = this.options.url.split('?'),
                    baseUrl = urlPaths[0],
                    urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                    paramData = {},
                    parameters, i;

                for (i = 0; i < urlParams.length; i++) {
                    parameters = urlParams[i].split('=');
                    paramData[decode(parameters[0])] = parameters[1] !== undefined ?
                        decode(parameters[1].replace(/\+/g, '%20')) :
                        '';
                }

                if( paramValue == '' ) return;

                var sort_by = paramValue.split('-');

                if( sort_by.length != 2 ) return;
                paramData[orderParam] = sort_by[0];
                paramData[directionParam] = sort_by[1];

                if (paramValue == defaultValue) { //eslint-disable-line eqeqeq
                    delete paramData[orderParam];
                    delete paramData[directionParam];
                }
                paramData = $.param(paramData);

                location.href = baseUrl + (paramData.length ? '?' + paramData : '');
            }
        });

        return $.mage.productListToolbarForm;
    }
});
