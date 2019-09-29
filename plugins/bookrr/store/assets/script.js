$(function(){

    function printInvoice() {
        var content = document.getElementById('invoice').innerHTML;
        var mywindow = window.open('', 'Print', 'height=600,width=800');
    
        mywindow.document.write('<html><head><title>Print</title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
    
        mywindow.document.close();
        mywindow.focus()
        mywindow.print();
        mywindow.close();
        return true;
    }

    function total(){
        var subTotal = 0;
        var tax = 0;
        var total = 0;

        $('.line_item').each(function(){
           var quantity = Number($(this).find('.quantity').val());
           var price    = Number($(this).find('.price').val());
           var tax      = Number($(this).find('.tax').val());
           var total    = $(this).find('.total');

           total.val((quantity*price)+tax);
        });

        $('.line_item .price').each(function(){
            subTotal += Number($(this).val());
        });

        $('.line_item .tax').each(function(){
            tax += Number($(this).val());
        });

        $('.line_item .total').each(function(){
            total += Number($(this).val());
        });


        $('#sub_total').val(subTotal);
        $('#tax').val(tax);
        $('#total').val(total);
    }


    var $lineItem = $('.line_item').clone();
    var $table = $('#invoice-table');
    var $tbody = $('#invoice-table').find('tbody');

    $('#add_item').on('click',function(e){
        e.preventDefault();
        $lineItem.clone().insertAfter( "#invoice-table tbody tr:last-child" );
    });
    
    $(document).on('click','.line_item .del',function(e){
        e.preventDefault();
        $(this).parents('.line_item').remove();
        total();
    });

    $(document).on('change','.line_item .quantity,.line_item .price,.line_item .tax,.line_item .total',function(e){
        e.preventDefault();
        total();
    });

    $(document).on('click','#print_invoice',function(e){
        e.preventDefault();
        printInvoice();
    });
});

$(function(){
    $('a[href="' + window.location.hash + '"]').click();
});

/*
*   Cart
*/
$(document).on('change','#cart .qty',function(){
    console.log($(this))
});