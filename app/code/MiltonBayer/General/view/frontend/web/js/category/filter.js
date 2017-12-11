define(["jquery", 'category_door_configurator'], function($, doorConf){

    $(document).ready(function() {

        $(".js-items:not(.js-price) .js-item div").click(e => {
            $(e.target).parent().toggleClass('js-active active');
            doorConf.updateDoorList();
        });

        doorConf.updateDoorList();
    });

    return $;
});
