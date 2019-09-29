Parking = {};

Parking.getId = function(el){
    return el.parents('.dropdown').data('id');
};

$(document).on('click','.check-in',function(event){
    event.preventDefault()
    var Bid = Parking.getId($(this));
    $(this).request('onCheckIn',{ data: { id: Bid } });
});


/*
*   Dropdown custom input
*/
$(function(){
    $('.custom-select').select2({tags:true});
});


/*
*   Barcode Listener
*/
$(function(){
    if($('[name*=listToolbarSearch]').length){
        var barcode = '';
        var $searchInput = $('[name*=listToolbarSearch]');

        $(document).on('keypress',function(e){
            if((e.keyCode || e.which) == 13){
                $('.flash-message.error').hide();
                $searchInput.val(barcode).submit();
                barcode = '';
            }else{
                barcode += e.originalEvent.key;
            }
        });
    }
});


$(document).keyup(function(e) {
    //  Escape Listener
    if (e.key === "Escape"){
        $('[name*=listToolbarSearch]').val('').submit();
    }
});