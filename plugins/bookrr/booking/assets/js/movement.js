$(function(){
    var barcode = '';
    $(document).on('keypress',function(e){
        if((e.keyCode || e.which) == 13){
            $('.flash-message.error').hide();
            $.popup({
                handler: 'onActionForm', 
                extraData: { barcode: barcode }
            });
            barcode = '';
        }else{
            barcode += e.originalEvent.key;
        }
    });

    $( document ).ajaxComplete(function(e) {
        setTimeout(function(){
            if($('.flash-message.error').length){
                $('.popup-backdrop, .control-popup').remove();
            }
        },1000);
    });
});

/*
*   Bay field custom input
*/
$(function(){
    $('#Form-field-Movement-_bay').select2({tags:true});
});