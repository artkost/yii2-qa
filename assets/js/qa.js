(function($, window) {
    'use strict';

    var Questions = {

        voteSelector: 'a.qa-vote-up, a.qa-vote-down',
        favoriteSelector: 'a.qa-favorite-link',

        /**
         *
         * @param e
         */
        handleResponse: function(e) {
            $.post($(this).attr('href'), $(this).data('post'), function(response) {
                if (response.status) {
                    $(e.delegateTarget).html(response.html);
                }
            });
            e.preventDefault();
        },

        /**
         *
         * @param counter
         * @param data
         */
        fieldAutocomplete: function(counter, data) {
            var datum = new Bloodhound(
                {
                    datumTokenizer: function(d) {
                        return Bloodhound.tokenizers.whitespace(d.word);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: data.remote,
                        filter: function(response) {
                            return response.items;
                        }
                    }
                });

            datum.initialize();

            $('.typeahead-' + counter).typeahead(null, {displayKey: 'word', source: datum.ttAdapter()});
        }
    };

    $(function() {
        $('div.qa-vote').on('click', Questions.voteSelector, Questions.handleResponse);
        $('div.qa-favorite').on('click', Questions.favoriteSelector, Questions.handleResponse);
    });

    window.yii.qa = Questions;

})(jQuery, window);