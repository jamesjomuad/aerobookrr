// $.request('onRefresh', {
//     success: function(){
//         console.log('successsss')
//     }
// })

$(document).on('.table.data tbody tr','click',function(){
    console.log($(this).data())
});