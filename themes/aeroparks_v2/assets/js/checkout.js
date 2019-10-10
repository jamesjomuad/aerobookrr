(function($){
	'use strict';
	$.wizard = function(strAction,param1,param2){

		if(typeof this._event == 'undefined')
		this._event = {
            "beforeShow" : {},
            "onShow" : {}
		};

		if(typeof this.on == 'undefined')
		this.on = function(eventName,key, fn){
			this._event[eventName][key] = [];
			this._event[eventName][key].push(fn);
            return this;
        };

		if(typeof this.trigger == 'undefined')
        this.trigger = function(e,key,param){
            var x;
            $(this._event[e][key]).each(function(i,fn){
                x = fn($('[href="'+key+'"]'),param);
			});
            return x;
		};
	
		if(typeof this.goto == 'undefined')
		this.goto = function(hash){
			var $target = $(hash);
			var $anchors = $('ul.setup-panel li a');
			var $anchor = $('[href="'+hash+'"]');
			var toHide = $target.prev().is(":visible") ? $target.prev() : $target.next();
			toHide.fadeOut('fast').promise().done(function() {
				$target.fadeIn();
			});
			$anchors.removeClass('active');
			$anchor.removeClass('disabled').addClass('active');
			window.location.hash = hash;
		};
	
		if(strAction && strAction.indexOf('#')==0){
			var hash = (strAction.indexOf('#')==0) ? strAction : false;
			if(hash && this.trigger('beforeShow',strAction,$(hash)) != false){
				this.goto(hash);
				this.trigger('onShow',strAction,$(hash));
			}
		}else if(typeof this._event[strAction] != "undefined" && param1 && typeof param2 == 'function'){
			this.on(strAction,param1,param2);
		}

		return this;
	};

	$.casher = function(target){
		this.wrapper = {
			main: $(target),
			itemParent: $(target).find('.item').parent(),
			item: $(target).find('.item').remove().clone(),
			total: $(target).find('.total')
		};
		this.currencySymbol = '$';
		this._total = 0;
		this._products = {};
		this._event = {
            "add" : [],
            "added" : [],
            "remove" : [],
            "total" : [],
            "changed" : []
		};
		
		this.on = function(eventName, fn){
            this._event[eventName].push(fn);
            return this;
        };

        this.trigger = function(e,param){
            var x;
            $(this._event[e]).each(function(i,fn){
                x = fn(param);
			});
			$(this._event['changed']).each(function(i,fn){
                fn(param);
			});
            return x;
		};
		
		this.add = function(key){
			var product;
			if(typeof key == 'string'){
				product = this._products[key];
			}else if(typeof key=='object'){
				product = key;
			}
			this.trigger('add',product);
			this.total();
			return this;
		};

		this.roundUp = function(num, precision) {
			var newnumber = Math.round(num * Math.pow(10, precision)) / Math.pow(10, precision);
    		return newnumber;
		};

		this.total = function(){
			var self = this;
			var item = self.wrapper.itemParent.find('.item');
			self._total = 0;
			item.each(function(){
				self._total += parseFloat($(this).data('price'));
			});
			var sum = this.roundUp(this._total,3);
			this.wrapper.total.text(this.currencySymbol+sum);
			return this;
		};

		this.addProducts = function(item){
			if(typeof item == 'object'){
				$.extend(this._products,item);
			}else if(typeof item == 'string'){

			}

			return this;
		};

		this.events = function(){
			var self = this;
			this.on('add',function(item){
				var $li = self.wrapper.item;
				var x = $('<i class="fa fa-times-circle ml-3 float-right"/>');
				var li = $li.clone();
				li.prepend(x);
				li.find('.name').text(item.name);
				li.find('.price').text(self.currencySymbol+item.price);
				li.find('.description').text(item.description);
				li.data('price',item.price)
				li.hide()
				self.wrapper.itemParent.append(li);
				li.slideDown()
				x.on('click',function(){
					li.slideUp('fast',function(){
						li.remove();
						self.total();
						self.trigger('remove',self.wrapper.itemParent);
					});
				});
				self.trigger('added',li);
				self.wrapper.itemParent.find('.msg').slideUp('fast',function(){
					$(this).remove();
				});
			});
			this.on('remove',function(wrapper){
				if(self.wrapper.itemParent.find('li').length==0)
				self.wrapper.itemParent.html('<b class="msg text-center">Empty</b>');
			});
			this.trigger('remove');
		};

		this.init = function(){
			this.wrapper.total.text('');
			this.events();
			return this;
		};

		this.init();
			
		return this;
	}
})(jQuery);

// Validation
$(function(){
	jQuery.validator.addMethod('phoneNum',function(value,element){
        return this.optional(element) || /^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/.test(value);
	}, 'Invalid phone number');

	jQuery.validator.addMethod("lesserDate",function(value,element,params){
		if(!$(params).val())
		return true;

		var dateIn = $(element).data('DateTimePicker').date();
		var dateOut = $(params).data('DateTimePicker').date();

		return dateOut.isAfter(dateIn);
	},'Date must less than Exit Date.');

	jQuery.validator.addMethod("greaterDate",function(value,element,params){
		var dateIn = $(params).data('DateTimePicker').date();
		var dateOut = $(element).data('DateTimePicker').date();

		return dateOut.isAfter(dateIn);
	},'Date must greater than Enter Date.');
	
	$.validator.init = $('#signUp').validate({
		onsubmit: false,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {                    
				validator.errorList[0].element.focus();
			}
		},
		rules: {
			"email": {
				required: true,
				email: true,
				remote: {
					url: "check-email",
					type: "get"
				}
			},
			"login":{
				required: true,
			},
			"guest":{
				required: true,
			},
			"password":{
				required: true,
				minlength: 5
			},
			"password_confirmation":{
				required: true,
				equalTo: "#password"
			},
			"first_name": "required",
			"last_name": "required",
			"plate": {
				required: true
			},
			"dateIn": {
				required:'#tran_yes:checked',
				lesserDate: '[name="dateOut"]'
			},
			"dateOut": {
				required:'#exit_tran_yes:checked',
				greaterDate: "[name=dateIn]"
			},
			"phone":{
				required: true,
				// phoneNum: true,
			}
		},
		messages: {
			'email': {
				required: "Please enter your email address.",
				email: "Please enter a valid email address.",
				remote: "Email already in use!"
			},
			"password": {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			"password_confirmation": {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Password did not match!"
			}
		}
	});
	$('[name="transpoTo"], [name="transpoFrom"]').on('change',function(){
		if($('#tran_yes').is(':checked') && $('#exit_tran_yes').is(':checked'))
		{
			$('[name="dateOut"]').rules('add',{greaterDate: "[name=dateIn]"});
		}
		else if($('#tran_yes').is(':checked') && $('#exit_tran_no').is(':checked'))
		{
			$('[name="dateOutOnly"]').rules('add',{greaterDate: "[name=dateIn]"});
		}
		else if($('#tran_no').is(':checked') && $('#exit_tran_yes').is(':checked'))
		{
			$('[name="dateOut"]').rules('add',{greaterDate: "[name=dateInOnly]"});
		}
		else if($('#tran_no').is(':checked') && $('#exit_tran_no').is(':checked'))
		{
			$('[name="dateOutOnly"]').rules('add',{greaterDate: "[name=dateInOnly]"});
		}
	});
});


// Step Wizard
$(function(){
	var $anchors = $('ul.setup-panel li a'),
		$tabs = $('.setup-content');

	$tabs.hide().eq(0).show();

	$anchors.click(function(e){
		e.preventDefault();
		var $target = $($(this).attr('href')),
			$anchor = $(this).closest('li a');
			
		if (!$anchor.hasClass('disabled')){
			$anchors.removeClass('active');
			$anchor.addClass('active');
			$tabs.hide();
			$target.show();
		}
	});

	var depDate = getUrlVars()["departDateTime"];
	var arrDate = getUrlVars()["arriveDateTime"];

	// Step 2
	$.wizard('onShow','#step-2',function(a,el){

		if(depDate != null && arrDate != null){
			window.location = baseurl + "/book-now#step-2?departDateTime=" + depDate + "&arriveDateTime=" + arrDate;
			var decodedDatestr1 = decodeURIComponent(depDate); 
			var decodedDatestr2 = decodeURIComponent(arrDate); 
			$('#in-if-yes input[name="dateIn"]').val(decodedDatestr1);
			$('#out-if-yes input[name="dateOut"]').val(decodedDatestr2);
		}else{
			window.location = baseurl + "/book-now#step-2";
		}
		$anchors.eq(0).addClass('done');
		$('html, body').animate({scrollTop: 0}, 300);
	});
	
	// Step 3
	$.wizard('onShow','#step-3',function(a,el){
		$anchors.eq(1).addClass('done');
		$('html, body').animate({scrollTop: 0}, 300);
		$anchors.eq(3).css('width','0 !important');
	});
	$.wizard('beforeShow','#step-3',function(a,el){
		if (!$('#signUp').valid()){
			return false;
		}
	});

	// Step 4
	$.wizard('onShow','#step-4',function(){
		$anchors.eq(2).addClass('done');
		$('html, body').animate({scrollTop: 0}, 300);
		var data = $('#signUp').serializeJSON();

		$('#review-fname, #final-fname').val(data.fname+' '+data.lname);
		$('#review-email,#final-email').val(data.email);
		$('#review-phone, #final-phone').val(data.phone);
		if(data.txtNotification){
			$('[name="review-txtNotification"], [name="final-txtNotification"]').prop('checked',true);
		}
		$('#review-plate, #final-plate').val(data.vehicle[0].plate);
		$('#review-make, #final-make').val(data.vehicle[0].brand);
		$('#review-model, #final-model').val(data.vehicle[0].model);
		$('#review-vsize, #final-vsize').val(data.vehicle[0].size);
		$('#review-color, #final-color').val(data.vehicle[0].color);

		$('#review-otherplate, #final-otherplate').val(data.vehicle[1].plate);
		$('#review-othermake, #final-othermake').val(data.vehicle[1].brand);
		$('#review-othermodel, #final-othermodel').val(data.vehicle[1].model);
		$('#review-othervsize, #final-othervsize').val(data.vehicle[1].size);
		$('#review-othercolor, #final-othercolor').val(data.vehicle[1].color);

	 	if ($('#car_yes').attr( 'aria-expanded') === 'true') {
            $('#othercardetails, #final_othercardetails').show(); 
        } else {
             $('#othercardetails, #final_othercardetails').hide();   
        }

		$('#review-num-to-port, #final-num-to-port').val(Number(data.adultsGoing)+Number(data.childrenGoing));
		$('#review-adults-to, #final-adults-to').val(data.adultsGoing);
		$('#review-children, #final-children').val(data.childrenGoing);
		$('#review-num-from-port, #final-num-from-port').val(Number(data.adultsReturning)+Number(data.childrenReturning));
		$('#review-adults-from, #final-adults-from').val(data.adultsReturning);
		$('#review-child-from, #final-child-from').val(data.childrenReturning);
		if(data.disabledPerson){
			$('[name="review-disabledAccess"], [name="final-disabledAccess"]').prop('checked',true);
		}
		$('#review-entry-details, #finish-entry-details').val($("#tran_yes").attr('aria-expanded')=='true'?data.dateIn:data.dateInOnly);
		$('#review-exit-details, #finish-exit-details').val($("#exit_tran_yes").attr('aria-expanded')=='true'?data.dateOut:data.dateOutOnly);
		$('#review-purpose, #final-purpose').val(data.purpose);
		var cart = $('#cart-items').clone();
		cart.find('.fa-times-circle').remove();
		$('#review-order, #finish-order').html(cart);
	});

	// Step 5
	$.wizard('onShow','#step-5',function(a,el){
		$anchors.eq(3).addClass('done');
		$anchors.eq(4).addClass('done');
		$('html, body').animate({scrollTop: 0}, 300);
	});

	$.wizard('beforeShow','#travel_extra',function(a,el){
		if (!$('#step_1 [name]').valid()){
			return false;
		}
	});
});


// Casher: Products or Services
$(function(){
	window.Casher = new $.casher('#casher');
	Casher
	.addProducts({
		'swl': {
			name: 'Standard Car Valet (Large)',
			price: '79.95',
			description: 'Wash and dry exterior, vacuum, clean windows and paint tyres'
		},
		'swm': {
			name: 'Standard Car Valet (Medium)',
			price: '69.95',
			description: 'Wash and dry exterior, vacuum, clean windows and paint tyres'
		},
		'sws': {
			name: 'Standard Car Valet (Small)',
			price: '59.95',
			description: 'Wash and dry exterior, vacuum, clean windows and paint tyres'
		},
		'dwl': {
			name: 'Deluxe Car Valet (Large)',
			price: '139.95',
			description: 'Deluxe: Wash and dry exterior, vacuum, clean and paint tyres, hand polish exterior.'
		},
		'dwm': {
			name: 'Deluxe Car Valet (Medium)',
			price: '119.95',
			description: 'Deluxe: Wash and dry exterior, vacuum, clean and paint tyres, hand polish exterior.'
		},
		'dws': {
			name: 'Deluxe Car Valet (Small)',
			price: '99.95',
			description: 'Deluxe: Wash and dry exterior, vacuum, clean and paint tyres, hand polish exterior.'
		},
		'ewl': {
			name: 'Exterior Wash (Large)',
			price: '34.95',
			description: 'Deluxe: Wash and dry exterior, vacuum, clean and paint tyres, hand polish exterior.'
		},
		'ewm': {
			name: 'Exterior Wash (Medium)',
			price: '29.95',
			description: 'Deluxe: Wash and dry exterior, vacuum, clean and paint tyres, hand polish exterior.'
		},
		'ews': {
			name: 'Exterior Wash (Small)',
			price: '24.95',
			description: 'Deluxe: Wash and dry exterior, vacuum, clean and paint tyres, hand polish exterior.'
		},
		'minorCS': {
			name: 'Minor Car Service',
			price: '129',
			description: "All vehicle repairs and servicing by A Grade mechanics, and workmanship guaranteed. Members of the 'Motor Trade Association' (MTA)."
		},
		'majorCS': {
			name: 'Major Car Service',
			price: '249',
			description: "All vehicle repairs and servicing by A Grade mechanics, and workmanship guaranteed. Members of the 'Motor Trade Association' (MTA)."
		},
		'hiCareCS': {
			name: 'Hi-Care Car Service',
			price: '199',
			description: "All vehicle repairs and servicing by A Grade mechanics, and workmanship guaranteed. Members of the 'Motor Trade Association' (MTA)."
		},
		'dieselCS': {
			name: 'Diesel / 4X4 Servicing',
			price: '299',
			description: "All vehicle repairs and servicing by A Grade mechanics, and workmanship guaranteed. Members of the 'Motor Trade Association' (MTA)."
		},
	});

	$('#addOnService').on('click',function(){
		Casher.add({
			name: 'Add-on',
			price: $('#addOnPrice').val(),
			description: $('#addOnDesc').val()
		});
	});
});


// Prevent script cache
// Remove after dev
$(function(){
	var time = (new Date()).getTime()
	$('script').each(function(){
		var src = $(this).attr('src') + '?' + time;
		$(this).attr('src',src);
	});

	// $("#checkoutbtn").submit(function( event ) {
	//   event.preventDefault();
	//   register();
	// });

});


function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}


$('#signUp').on('submit',function(){
	$('#signUp').request('onRegister',{
        complete: function(data) {
			var Book = data.responseJSON.booking;
			if(data.statusText=="OK" || data.statusText=="success")
			{
				$.wizard('#register-finish');
				$('#booking-number').text(Book.number);
				$('#booking-email').text(Book.email);
				$('#pticket').attr('href',Book.ticket.url);
			}
        }
    });
	return false;
});
