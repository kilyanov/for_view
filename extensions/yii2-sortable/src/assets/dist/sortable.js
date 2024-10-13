(function ($) {

    $.fn.sortableWidget = function (options) {

        let settings = $.extend({
            url: location.href
        }, options);

        let data = {};
        $(".js-sort")
            .find('li')
            .each(function () {
                let current = $(this);
                data[current.attr('data-id')] = current.attr('data-id');
            });

        $.ajax({
            url: settings.url,
            method: 'POST',
            data: {items:data}
        }).done(function (response) {
            /* todo */
            console.log(response);
        }).fail(function (response) {
            /* todo */
            console.log(response);
        });

    };
})(jQuery);
