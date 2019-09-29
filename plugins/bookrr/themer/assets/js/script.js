if (window.location.protocol !== 'https:') {
    window.location = 'https://' + window.location.hostname + window.location.pathname + window.location.hash;
}

(function($){
    function cnt(el)
    {
        if(!el.length) return;
        var $el = el;
        var target = Number($el.text());
        $({countNum: 0})
        .animate({ countNum: target }, {
            duration: 1000,
            easing: 'linear',
            step: function () {
                $el.html(Math.floor(this.countNum));
            },
            complete: function () {
                $el.html(this.countNum);
            }
        });
    }

    $.fn.countUp = function(){
        this.each(function(){
            new cnt($(this));
        });
    };
})($)


$(document).on('ready',function(){
    if($.fn.countUp ? true : false)
    $('.count-up').countUp();
}); 

$(function(){
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
});