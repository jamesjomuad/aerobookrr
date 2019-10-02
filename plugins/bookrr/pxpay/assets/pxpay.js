$('.modal-dialog.pxpay iframe#pxpayFrame').on('load',function(){
    console.log('loaded')
});

$(window).on('shown.bs.modal', function() { 
    $('.modal-dialog.pxpay').parents('.modal').removeAttr('tabindex');
});