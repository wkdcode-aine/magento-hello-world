define(["jquery"], function($){
    'use strict'

    var showTab;

    showTab = function( tab )
    {
        $(".js-tab, .js-tab-pane").removeClass('active');
        $(".js-tab."+ tab + ", .js-tab-pane." + tab).addClass('active');

        switch(tab) {
            case 'js-door-search':
                setupDoorList();
                break;
        }
    }

    $("li.js-tab, .js-next").click(function() {
        showTab($(this).data('tab'));
    });

    // var request = $.ajax({
    //   url: '/rest/V1/design-a-door/filters',
    //   method: "GET"
    // });
    //
    // request.done(function( filters ) {
    //     $(".js-filters-container").html('');
    //     var html;
    //
    //     $.each(filters, function(index, filter) {
    //         if( filter.type == 'filter' ) {
    //             html = '<div class="filter-container js-filter-container">';
    //                 html += '<h4>' + filter.label + '</h4>';
    //                 html += '<ul>';
    //                 $.each(filter.options, function(unused, option) {
    //                     html += '<li data-id=""><div class="filter-button">' + option.label + '</div></li>';
    //                 });
    //                 html += '</ul>';
    //             html += '</div>';
    //
    //             $(".js-filters-container").append(html);
    //         }
    //     });
    // });
    //
    // request.fail(function( jqXHR, textStatus ) {
    //   console.error( "Request failed: " + textStatus );
    // });
    //
    let setupDoorList = () => {
        $.ajax({
            url: '/ajax/designadoor/doorlist',
            success: ( response ) => {
                $(".js-tab-pane.js-door-search .js-results").html(response);
            }
        })
    };

    $(document).ready(function() {

        setupDoorList();
    })

    return $;
});
