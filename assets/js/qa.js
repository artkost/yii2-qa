(function($, window) {
    'use strict';

    var Questions = {

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
                    remote: data.remote
                });

            datum.initialize();

            $('.typeahead-' + counter).typeahead(null, {displayKey: 'word', source: datum.ttAdapter()});
        }
    };

    $(function() {
        $('div.qa-vote').on('click', 'a.qa-vote-up, a.qa-vote-down', Questions.handleResponse);
        $('div.qa-favorite').on('click', 'a.qa-favorite-link', Questions.handleResponse);
    });

    window.yii.qa = Questions;

})(jQuery, window);