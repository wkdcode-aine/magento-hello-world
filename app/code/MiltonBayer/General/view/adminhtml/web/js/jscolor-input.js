define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
], function($, AbstractField) {
    'use strict';

    return AbstractField.extend({
        /**
         * Invokes initialize method of parent class,
         * contains initialization logic
         */
        initialize: function () {
            _.bindAll(this, 'reset', 'onColorRender');

            this._super()
                .setInitialValue()
                ._setClasses()
                .initSwitcher();

            return this;
        },

        /**
         * Gets initial value of element
         *
         * @returns {*} Elements' value.
         */
        getInitialValue: function () {
            var values = [this.value(), this.default],
                value;

            values.some(function (v) {
                if (v !== null && v !== undefined) {
                    value = v;

                    return true;
                }

                return false;
            });

            this.onColorRender();

            return this.normalizeData(value);
        },

        onColorRender: function() {
            window.jscolor.installByClassName("jscolor");

            return this;
        }
    });
})
