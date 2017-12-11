define(["jquery", 'category_door_configurator_doorlist'], function($, doorlist){
    'use strict'

    // var _this =

    // $("li.js-tab, .js-next").click(function() {
    //     showTab($(this).data('tab'));
    // });
    //
    // var showTab = tab =>  {
    //     $(".js-tab, .js-tab-pane").removeClass('active');
    //     $(".js-tab."+ tab + ", .js-tab-pane." + tab).addClass('active');
    //
    //     switch(tab) {
    //         case 'js-door-search':
    //             setupDoorList();
    //             break;
    //     }
    // }

    // $(document).ready(function() {
    //
    //     _this.setupDoorList();
    // });

    return {
        updateDoorList: function() {
            doorlist.get();
        }
    };
});
