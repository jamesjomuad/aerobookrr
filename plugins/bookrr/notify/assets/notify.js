// Plugin
$(function(){
    $.notify = {};

    $.notify.shake = function(state){
        $bell = $(".mainmenu-nav a[href*='aeroparks/notify'] .nav-icon");

        $bell.addClass('shaker');

        if(state===false)
        $bell.removeClass('shaker');

        return this;
    }

    $.notify.count = function(){
        var count = $('#ntfy .alert').length;
        $(".mainmenu-nav a[href*='aeroparks/notify'] .nav-label").text(count);
    }

});


//Initiator
$(function(){
    $label = $(".mainmenu-nav a[href*='aeroparks/notify'] .nav-label");
    $label.text('5');
    $.notify.shake();


    $('#ntfy .close').on('click',function(){
        setTimeout(function() { $.notify.count(); }, 600);
    });
});