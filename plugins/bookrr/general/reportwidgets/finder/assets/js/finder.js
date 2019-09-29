$(document).on('keypress','#finder-barcode',function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        var code = $(this).val()
        $(this).popup({ handler: 'onBarcodeForm', size:'giant', extraData: { id: code } });
    }
});

$(document).on('keypress','#finder-book-no',function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        var bookingNo = $(this).val()
        $(this).popup({ handler: 'onBookingForm', size:'giant', extraData: { id: bookingNo } });
    }
});

$(document).on('keypress','#finder-plate-no',function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        var plateNo = $(this).val()
        $(this).popup({ handler: 'onPlateNoForm', size:'giant', extraData: { plate: plateNo } });
    }
});

$(document).on('submit','#tagger',function(event){
    event.preventDefault();
    var barcode = $('#barcode-tag').val();
    if(barcode){
        $(this).request('onSaveTag');
    }
})

$(document).on('hidden.bs.modal','.modal', function(){
    $('#finder-barcode').val('').focus();
    $('#finder-book-no').val('');
    $('#finder-plate-no').val('');
});