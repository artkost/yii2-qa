(function($){
    "use strict";

    $(function(){
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
    });

})(jQuery);