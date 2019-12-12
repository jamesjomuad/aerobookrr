$(function() {
    var PopupBuilder = function() {

        function init() {
            bindEventHandlers()
        }

        function bindEventHandlers() {
            $(document).on('click', '[data-show-popup][data-handler]', function(e) {
                showAjaxPopup(e)
            });
        }

        function showAjaxPopup(e) {
            var handler = $(e.target).data('handler');

            $.oc.stripeLoadIndicator.show();

            $(e.currentTarget).request(handler, {
                data: {},
                success: function(data) {
                    $(data.popup).modal({
                        backdrop: 'static',
                        keyboard: true
                    });
                }
            }).always(function() {
                $.oc.stripeLoadIndicator.hide();
            });

            e.preventDefault();

            return false
        }

        init()
    };

    new PopupBuilder()
});