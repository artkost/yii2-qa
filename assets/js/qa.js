(function($){
    "use strict";

    $(function(){

        $('div.qa-vote').each(function() {
            var $this = $(this),
                $votes = $('span.qa-vote-count', $this),
                vote = function(url, post) {
                    return $.post(url, post,  function (response) {
                        if (response.status) {
                            $votes.html(response.data.votes);
                        }
                    });
                };

            $('a.qa-vote-up, a.qa-vote-down', $this).on('click', function(e) {
                vote($(this).attr('href'), $(this).data('post'));
                e.preventDefault();
            });
        });

        $('div.qa-favorite').each(function() {
            var $this = $(this),
                $icon = $('.glyphicon', $this),
                activeClass = 'qa-favorite-active',
                isFavorited = $this.hasClass(activeClass),
                favorite = function(url, post) {
                    return $.post(url, post,  function (response) {
                        if (response.status) {
                            $this.removeClass(activeClass);
                            $icon.removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                            if (!isFavorited) {
                                $this.addClass(activeClass);
                                $icon.removeClass('glyphicon-star-empty').addClass('glyphicon-star');
                                isFavorited = true;
                            } else {
                                isFavorited = false;
                            }
                        }
                    });
                };

            $('a.qa-favorite-link', $this).on('click', function(e) {
                favorite($(this).attr('href'), $(this).data('post'));
                e.preventDefault();
            });
        });
    });

})(jQuery);