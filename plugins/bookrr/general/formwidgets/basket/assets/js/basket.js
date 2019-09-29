(function(){
    $.oc.basket = function(method,el){
        if(method=='remove'){
            var $item = $(el).parents('.item');
            $item.find('.card').fadeOut(800);
            $item.animate({width:0},800,function(){
                $item.remove();
            })
        }
    }
})()