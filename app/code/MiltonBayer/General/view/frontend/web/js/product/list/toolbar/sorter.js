define([
    'jquery',
  "Magento_Catalog/js/product/list/toolbar"
], function($, productListToolbarForm){
  "use strict";

    // $.widget('miltonBayer.productListToolbarForm', productListToolbarForm, {
    //
    // });
  /**
   * Rewrite original UI Component:
   */
  return productListToolbarForm.extend({
      changeUrl: function(paramName, paramValue, defaultValue) {
          console.log("change");
      }
  })
});
