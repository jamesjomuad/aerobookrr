Register = {
    version: "1.0.0",
};

Register.autoComplete = function(){
    var $input = $('#RegisterModal [name="plate"]');
    var $spin = $input.next();

    $input.autoComplete({
        resolver: 'custom',
        events: {
            search: function (qry, callback) {
                $spin.show()
                $.ajax('https://test.carjam.co.nz/a/vehicle:abcd?key=CE8130C5D3C82C035B493852F37BD96E6EA1E4EA&plate='+qry)
                .done(function (res) {
                    var data;
                    
                    if(res.plate){
                        data = [
                            { 
                                "value": 1, 
                                "text": res.plate + ' - ' + res.make,
                                "plate": res.plate,
                                "make": res.make,
                                "model": res.model
                            },
                        ] 
                    }else{
                        data = [{
                            "value": 1,
                            "text": "Not Found!"
                        }]
                    }
                    
                    callback(data)
                })
                .fail(function (jqXHR, status, err) {
                    console.log(status)
                })
                .always(function (jqXHR, status) {
                    $spin.hide()
                });
            }
        }
    });

    $input.on('autocomplete.select', function(evt, item) {
        $(this).val(item.plate)
        $('#RegisterModal [name="make"]').val(item.make)
        $('#RegisterModal [name="model"]').val(item.model)
    });
}

Register.init = function(){
    Register.autoComplete();
    return this;
};

Register.back = function(){
    var prev = $('#RegisterModal .nav .nav-item.active').prev();
    prev.removeClass('disabled');
    prev.removeClass('disabled');
    prev.tab('show');
    prev.addClass('disabled');
};

Register.onStepOne = function(){
    $('#RegisterModal #step1').request('onStepOne',{
        success: function(data) {
            console.log(data);
            return data;
        },
        complete: function(res){
            if(res.status==200){
                var tab = $('#RegisterModal [href="#step2"]');
                tab.removeClass('disabled');
                tab.tab('show');
                tab.addClass('disabled');
            }
        }
    });
}

Register.onStepTwo = function(){
    $('#RegisterModal #step2').request('onStepTwo',{
        success: function(data) {
            console.log(data);
        },
        complete: function(res){
            if(res.status==200){
                var tab = $('#RegisterModal [href="#step3"]');
                tab.removeClass('disabled');
                tab.tab('show');
                tab.addClass('disabled');
            }
        }
    });
}


