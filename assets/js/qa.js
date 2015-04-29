(function($, window) {
    'use strict';

    var Questions = {

        voteSelector: 'a.js-vote-up, a.js-vote-down',
        favoriteSelector: 'a.js-favorite',
        answerCorrectSelector: 'a.js-answer-correct-link',

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
        $('.js-vote').on('click', Questions.voteSelector, Questions.handleResponse);
        $('.js-favorite').on('click', Questions.favoriteSelector, Questions.handleResponse);
        $('.js-answer-correct').on('click', Questions.answerCorrectSelector, Questions.handleResponse);
    });

    window.yii.qa = Questions;

})(jQuery, window);
