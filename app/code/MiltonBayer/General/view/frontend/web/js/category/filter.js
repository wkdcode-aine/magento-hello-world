define(["jquery", 'category_door_configurator', 'price_filter_selection'], function($, doorConf, priceSelect){

    $(document).ready(function() {

        $(".js-items:not(.js-price) .js-item div").click(e => {
            $(e.currentTarget).parent().toggleClass('js-active active');
            doorConf.updateDoorList();
        });

        // forces the user to select only a continuous price range
        $(".js-items.js-price .js-item div").click(e => {
            priceSelect.filterClick(e.currentTarget);
            doorConf.updateDoorList();
        });

        doorConf.updateDoorList();
    });

    return $;
});
