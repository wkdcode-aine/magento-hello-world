define(["jquery"], function($){
    'use strict'

    $(".js-category-description-toggle").on('click', function() {
        $(".js-category-description-wrapper").toggle();
    });
    $(".js-category-description-wrapper").hide();

    return $;
})
