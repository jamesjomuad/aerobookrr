$.fn.countUp = function(){
    console.log(this.length)
    if(!this.length) return;
    var $el = this;
    var target = this.text();
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
};

$(document).on('ready',function(){
    $('.count-up').countUp();
});