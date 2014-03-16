(function($){
    "use strict";

    $('div.vote').each(function() {
        var $this = $(this);
        var $votes = $('span.vote-count', $this);

        $('a.vote-up, a.vote-down', $this).on('click', function(e) {
            var url = $(this).data('url'),
                post = $(this).data('post');
            $.post(url, post,  function (data) {
                if (data.status) {
                    $votes.html(data.votes);
                }
            });
            e.preventDefault();
        });
    });

    $('div.field-question-tags input').each(function() {
        var $this = $(this),
            url = $this.data('url');

        $this.tagsinput({
            typeahead: {
                source: function(query) {
                    return $.getJSON(url);
                }
            }
        })
    });


})(jQuery);