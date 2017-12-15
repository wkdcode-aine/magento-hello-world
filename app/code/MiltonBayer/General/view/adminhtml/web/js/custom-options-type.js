/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Catalog/js/custom-options-type',
], function ($, _, registry, UiSelect) {
    'use strict';

    return UiSelect.extend({
        /**
         * Show, hide or clear components based on the current type value.
         *
         * @param {String} currentValue
         * @param {Boolean} isInitialization
         * @returns {Element}
         */
        updateComponents: function (currentValue, isInitialization) {
            var currentGroup = this.valuesMap[currentValue];

            if (currentGroup !== this.previousGroup) {
                _.each(this.indexesMap, function (groups, index) {
                    var template = this.filterPlaceholder + ', index = ' + index,
                        visible = groups.indexOf(currentGroup) !== -1,
                        component;

                    switch (index) {
                        case 'container_type_static':
                        case 'values':
                        // Module specific code
                        case 'colours':
                        // end module specific code
                            template = 'ns=' + this.ns +
                                ', dataScope=' + this.parentScope +
                                ', index=' + index;
                            break;
                    }

                    /*eslint-disable max-depth */
                    if (isInitialization) {
                        registry.async(template)(
                            function (currentComponent) {
                                currentComponent.visible(visible);
                            }
                        );
                    } else {
                        component = registry.get(template);

                        if (component) {
                            component.visible(visible);

                            /*eslint-disable max-depth */
                            if (_.isFunction(component.clear)) {
                                component.clear();
                            }
                        }
                    }
                }, this);

                this.previousGroup = currentGroup;
            }

            return this;
        }
    })
})
