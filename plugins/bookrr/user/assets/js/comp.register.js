Register = {
    version: "1.0.0",
    id: '#RegisterModal',
    el: function(target){
        return $(Register.id).find(target);
    },
    getArray: function () {
        return $(Register.id).find('[name]').not('[name="password"],[name="confirmpassword"]').serializeArray();
    },
    getJSON: function () {
        var o = {};
        var a = this.getArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
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
                var data;

                $.ajax('https://test.carjam.co.nz/a/vehicle:abcd?key=CE8130C5D3C82C035B493852F37BD96E6EA1E4EA&plate='+qry)
                .done(function (res) {
                    if(res){
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
                    callback([{
                        "value": 1,
                        "text": "Not Found!"
                    }])
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
    return this;
};

Register.onStepOne = function(){
    Register.el('#step1').request('onStepOne',{
        success: function(data) {
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
    Register.el('#step2').request('onStepTwo',{
        success: function(data) {
        },
        complete: function(res){
            if(res.status==200){
                Register.initStepThree();
            }
        }
    });
}

Register.onStepThree = function(){
    this.next().initStepFour();
}

Register.book = function(response){
    var self = this.book;
    var dpSelected = {in:false,out:false};

    self.isOpen = false;
    
    self.compute = function(){
        var hours = moment.duration($dtp_out.data('DateTimePicker').date().diff($dtp_in.data('DateTimePicker').date())).asHours();
        if(dpSelected.in && dpSelected.out)
        Register.app3.table.hours = hours;
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
        dpSelected.in = true;
        Register.el('[name="datein"]').val(date.format('MM/DD/YYYY h:mm'));
        dpOut.minDate(event.date);
        self.compute();
    });

    $dtp_out.on('dp.change', function(event){
        var date = event.date;
        var dpIn = $dtp_in.data('DateTimePicker');
        dpSelected.out = true;
        Register.el('[name="dateout"]').val(date.format('MM/DD/YYYY h:mm'));
        dpIn.maxDate(event.date);
        self.compute();
    });

    $dtp_in.data('DateTimePicker').maxDate($dtp_out.data('DateTimePicker').date());

    // Step3 Vue App
    Register.app3.table = new Vue({
        el: '#booking-table',
        delimiters: ["{[","]}"],
        data: {
            isOpen: false,
            currency: response.currency,
            rateTxt: response.currency+response.rate,
            rate: response.rate,
            hours: 0
        },
        computed: {
            hourTxt: function(){
                return (this.hours==1) ? this.hours.toFixed(2)+" hr" : this.hours.toFixed(2)+"/hrs";
            },
            total: function(){
                return this.currency+(this.rate*this.hours).toFixed(2);
            },
            cost: function(){
                return this.currency+(this.rate*this.hours).toFixed(2);
            }
        }
    });
};

Register.initStepThree = function(){
    var tab = $('#RegisterModal [href="#step3"]');
    tab.removeClass('disabled');
    tab.tab('show');
    tab.addClass('disabled');

    Register.app3 = new Vue({
        el: '#bookrrApp',
        delimiters: ["{[","]}"],
        data: {
            isOpen: false
        },
        methods:{
            destroyTable: function(){
                this.isOpen = false;
                Register.el('#bookrr').html('');
            }
        }
    });
    
    return this;
};

Register.initStepFour = function(){
    if(!Register.app4)
    {
        Register.app4 = new Vue({
            el: "#app4",
            delimiters: ["{[","]}"],
            data:{
                details: Register.getArray(),
                headerTxt: "",
                subText: "Hello",
                labels: {
                    login: "Login",
                    email: "Email",
                    phone: "Phone Number",
                    firstname: "First Name",
                    lastname: "Last Name",
                    plate: "Plate",
                    make: "Make",
                    model: "Model",
                    date_in	: "Date In",
                    date_out: "Date Out" ,
                    totalCost: "Parking Cost"
                }
            },
            methods: {
                label: function(k){
                    return this.labels[k] ? this.labels[k] : k;
                },
                onBack: function(){
                    Register.back();
                },
                onNext: function(){
                    Register.el('#RegisterForm').request('Register::onRegister',{
                        success: function(data){
                            console.log(data)
                        },
                        complete: function(){

                        },
                        error: function(){

                        }
                    });
                }
            }
        });
    }else{
        Register.app4.details = Register.getArray();
    }

    Register.app4.headerTxt = (Register.app3.isOpen) ? "Checkout" : "Review Details";
}

Register.onStepFour = function(){

}

Register.init = function(){
    Register.autoComplete();
    return this;
};