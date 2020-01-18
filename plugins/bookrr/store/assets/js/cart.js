$(document).on('change','[name*="Product[pivot]"]',function(){
    setTimeout(function(){ $.request('form::onRefresh') }, 1000);
});