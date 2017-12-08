define(["jquery", 'category_door_configurator'], function($, doorConf){


    console.log("FFS", doorConf);
    $(document).ready(function() {

        $(".js-items .js-item").click(() => {
            console.log("CLICK");
            doorConf.setupDoorList();
        });
    });

    return $;
});
