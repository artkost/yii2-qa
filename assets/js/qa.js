(function($){
    "use strict";

    $(function(){

        var responseHtml = function(e) {
            $.post($(this).attr('href'), $(this).data('post'), function (response) {
                if (response.status) { $(e.delegateTarget).html(response.html); }
            });
            e.preventDefault();
        };

        $('div.qa-vote').on('click', 'a.qa-vote-up, a.qa-vote-down', responseHtml);
        $('div.qa-favorite').on('click','a.qa-favorite-link', responseHtml);
    });

})(jQuery);