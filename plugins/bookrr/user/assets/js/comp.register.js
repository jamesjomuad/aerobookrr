Register = {
    version: "1.0.0",
    id: '#RegisterModal',
    el: function(target){
        return $(Register.id).find(target);
    }
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
                    
                    if(typeof res == "object"){
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

Register.back = function(){
    var prev = $('#RegisterModal .nav .nav-item.active').prev();
    prev.removeClass('disabled');
    prev.tab('show');
    prev.addClass('disabled');
};

Register.next = function(){
    var next = $('#RegisterModal .nav .nav-item.active').next();
    next.removeClass('disabled');
    next.tab('show');
    next.addClass('disabled');
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

Register.onStepThree = function(){
    Register.next()
}

Register.book = function(res){

    // Vue table
    Register.book.table = new Vue({
        el: '#bookrr',
        delimiters: ["{[","]}"],
        data: {
            rate: {
                rate: res.rate,
                hours: '0/Hrs',
                cost: '0',
                total: 1000,
            }
        },
        methods: {
            getTotal: function(){
                // return this.items.reduce(function(price, item){ return price + item.price; },0).toFixed(2);
                
            }
        }
    });

    Register.book.getHours = function(){
        return moment.duration($dtp_out.data('DateTimePicker').date().diff($dtp_in.data('DateTimePicker').date())).asHours();
    }

    var $dtp_in = Register.el('#datetimepicker_in').datetimepicker({
        minDate: new Date(),
        inline: false,
        sideBySide: false,
        defaultDate: moment().add('days', 1)
    });

    var $dtp_out = Register.el('#datetimepicker_out').datetimepicker({
        minDate: new Date(),
        inline: false,
        sideBySide: false,
        defaultDate: moment().add('days', 7)
    });

    $dtp_in.on('dp.change', function(event){
        var date = event.date;
        var dpOut = $dtp_out.data('DateTimePicker');
        Register.el('[name="datein"]').val(date.format('MM/DD/YYYY h:mm'));
        dpOut.minDate(event.date);
    });

    $dtp_out.on('dp.change', function(event){
        var date = event.date;
        var dpIn = $dtp_in.data('DateTimePicker');
        Register.el('[name="dateout"]').val(date.format('MM/DD/YYYY h:mm'));
        dpIn.maxDate(event.date);
    });

    $dtp_in.data('DateTimePicker').maxDate($dtp_out.data('DateTimePicker').date());
};

Register.showStepThree = function(){
    var tab = $('#RegisterModal [href="#step3"]');
    tab.removeClass('disabled');
    tab.tab('show');
    tab.addClass('disabled');
};

Register.init = function(){
    Register.showStepThree(); // remove after
    Register.autoComplete();
    return this;
};