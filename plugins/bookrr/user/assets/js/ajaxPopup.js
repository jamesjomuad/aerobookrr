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
            var $el = $(e.target)
            var handler = $el.data('handler');

            $.oc.stripeLoadIndicator.show();

            $(e.currentTarget).request(handler, {
                data: {},
                success: function(data) {
                    $(data.popup).modal({
                        backdrop: 'static',
                        keyboard: true
                    });
                    if($el.data('requestSuccess')){
                        new Function($el.data('requestSuccess'))()
                    }
                },
                error: function(){
                    if($el.data('requestError')){
                        new Function($el.data('requestSuccess'))()
                    }
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