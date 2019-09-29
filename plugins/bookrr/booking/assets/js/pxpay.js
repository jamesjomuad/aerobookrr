$(window).on('load','.modal-dialog.pxpay iframe#pxpayFrame',function(){
    console.log('loaded')
});

$(window).on('shown.bs.modal', function() { 
    $('.modal-dialog.pxpay').parents('.modal').removeAttr('tabindex');
});