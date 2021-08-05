(function($) {
    $.lantraBrowser = function(element, options) {
        var defaults = {
            url: '/sp/assets/browse-evidence',
            loadAssets: function() {}
        }
        var plugin = this;
        plugin.settings = {}
        var $element = $(element),
            element = element;

        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);

        }
        plugin.loadAssets = function() {
            data[window.csrfTokenName] = window.csrfTokenValue;
            $('body').addClass('loading');
            $.post(this.url, data, function(response) {
                $('body').removeClass('loading');
                if (!response.success) {
                    alert(response.message ? response.message : 'Server error, check the console.');
                    return;
                }
                console.log(response);

            }).fail(function(error) {
                $('body').removeClass('loading');
                console.log(error);
                alert('Server error, check the console.');
            });
        }
        plugin.init();
    }

    $.fn.lantraBrowser = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('lantraBrowser')) {
                var plugin = new $.lantraBrowser(this, options);
                $(this).data('lantraBrowser', plugin);
            }
        });
    }
})(jQuery);

(function($) {
    $('.lantra-browser').lantraBrowser();
});

