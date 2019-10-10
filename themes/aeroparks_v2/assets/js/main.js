// if (window.location.protocol !== 'https:') {
//     window.location = 'https://' + window.location.hostname + window.location.pathname + window.location.hash;
// }

$(document).on('ajaxSetup', function (event, context) {
	context.options.flash = true;

	context.options.loading = $.oc.stripeLoadIndicator;

	context.options.handleErrorMessage = function (message) {
		$.oc.flashMsg({
			text: message,
			class: 'error'
		});
	};

	context.options.handleFlashMessage = function (message, type) {
		$.oc.flashMsg({
			text: message,
			class: type
		});
	};
});


$(function () {

	var today = new Date();
	// $(".datepicker").datepicker({
	//     changeMonth: false,
	//     changeYear: false,
	//     minDate: today 
	// });

	$('.datepicker').datetimepicker({
		minDate: today,
		format:'DD/MM/YYYY'
	});

	$('.timepicker').datetimepicker({
		minDate: today,
		format:'hh:mm A'
	});

	$('.third-button').on('click', function () {
		$('.animated-icon3').toggleClass('open');
	});


	$("input#fname").blur(function () {
		$("input#mainFname").val($("input#fname").val());
	});

	$("input#lname").blur(function () {
		$("input#mainLname").val($("input#lname").val());
	});

	$("input#phone").blur(function () {
		$("input#mainPhone").val($("input#phone").val());
	});


	$('input#plate').on('change blur', function () {
		var plateval = $(this).val();
		var url = "https://test.carjam.co.nz/a/vehicle:abcd?key=CE8130C5D3C82C035B493852F37BD96E6EA1E4EA&plate=" + plateval;

		$.ajax({
			url: url,
			dataType: "json",
			beforeSend: function () {
				$('.loader').show();
			},
			success: function (data) {
				$(".mypanel_error").fadeOut();
				$('#make_data').val('');
				$('#model_data').val('');
				$('#color_data').val('');

				if (data == null) {
					$('.vehicle_panel').fadeOut();
					$(".mypanel_error").fadeIn().html('<label class="error">Plate Number does not Exist!</label>');
				} else if (data) {

					$('#make_data').val(data.make);
					$('#model_data').val(data.model);
					$('#color_data').val(data.main_colour);
					$('.vehicle_panel').fadeIn();
				}
			},
			complete: function () {
				$('.loader').hide();
			},
			error: function () {
				if (plateval.length == 0) {
					$('.vehicle_panel').fadeOut();
					$(".mypanel_error").html('<label class="error">Plate Number is Empty!</label>');
				} else {
					$(".mypanel_error").html('<label class="error">Plate Number does not Exist!</label>');
				}
			}
		});

	});

	$("#signup_quotebtn").click(function(e) {
		e.preventDefault();
		$('.forcelogin_wrap').fadeOut();
		

		$('html,body').addClass('visible');

		
		// window.location = "/aeroparks/#flight_depnum";
	});

	$(".forcelogin_wrap #login_back").click(function(e) {
		e.preventDefault();
		$('.forcelogin_wrap').fadeOut();

		$("#quote_mainwrap").hide();
		$("#content_wrap").show();
		$('#fname')[0].focus();

		
		// window.location = "/aeroparks/#flight_depnum";
	});



	// $('input#othercarplate').on('change blur',function(){
	// 	var plateval = $(this).val();
	// 	var url = "https://test.carjam.co.nz/a/vehicle:abcd?key=CE8130C5D3C82C035B493852F37BD96E6EA1E4EA&plate=" + plateval;

	// 	$.ajax({
	// 		url: url,
	// 		dataType: "json",
	// 		beforeSend: function() {
	// 		    $('.loader2').show();
	// 		},
	// 		success: function(data) {
	// 		    var text = '<label>Make:</label><input id="othermake_data" name="vehicle[1][brand]" type="text" class="form-control mb-3" placeholder="" value="' + data.make + '"><label>Model:</label><input id="othermodel_data" name="vehicle[1][model]" type="text" class="form-control mb-3" placeholder="" value="' + data.model +'"><label>Color:</label><input id="othercolor_data" name="vehicle[1][color]" type="text" class="form-control mb-0" placeholder="" value="' + data.main_colour +'">'

	//         	$(".othermypanel").html(text);

	// 		},
	// 		complete: function() {
	// 		    $('.loader2').hide();
	// 		},
	// 		error: function(){
	// 			if(plateval.length == 0) {
	// 				$(".othermypanel").html('<label class="error">Plate Number is Empty!</label>');
	// 			}else{
	// 				$(".othermypanel").html('<label class="error">Plate Number does not Exist!</label>');
	// 			}
	// 		}
	// 	});

	// });


	// $('input[name="transpoTo"]').on('change', function () {
	// 	if ($('#tran_yes').hasClass('collapsed')) {
	// 		$('#enter_port_trans input[name="dateTimeInOnly"]').val('');
	// 	} else {
	// 		$('#enter_port_trans input[name="dateTimeIn"]').val('');
	// 	}
	// });

	// $('input[name="transpoFrom"]').on('change', function () {
	// 	if ($('#tran_yes').hasClass('collapsed')) {
	// 		$('#exit_port_trans input[name="dateTimeInOnly"]').val('');
	// 	} else {
	// 		$('#exit_port_trans input[name="dateTimeOut"]').val('');
	// 	}
	// });

	//Instagram Feed

	var feed = new Instafeed({
		get: 'user',
		userId: '10665736023',
		accessToken: '10665736023.598e3fc.ff4e6c12d9bb466e9c6978a796e8cc4f',
		resolution: 'thumbnail',
		template: '<a class="grid-item" href="{{link}}" target="_blank"><img src="{{image}}" /></a>',
		sortBy: 'most-recent',
		limit: 4,
		links: false
	});
	feed.run();



});

var baseurl = $('base').attr("href");

$(function () {

	$('#login').on('submit', function () {
		$(this).request('onLogin', {
			url: baseurl + '/login',
			success: function (json) {
				if (json.isLogin) {
					$.oc.flashMsg({
						text: 'Welcome ' + json.user.login,
						class: 'info'
					});
					$('#SignIN').delay("slow").fadeOut('slow', function () {
						window.location.href = baseurl + '/backend';
					});
				}
			}
		});
		return false;
	});

	$('#btnPromoCode').on('click', function () {
		promocode();
	});

	document.getElementById("promocode").onkeypress = function (event) {
		if (event.keyCode == 13 || event.which == 13) {
			event.preventDefault();
			promocode();
		}
	};


});



function promocode() {
	$.get(baseurl + '/api/v1/coupons/' + $('#promocode').val())
		.done(function (res) {
			var content = $('#checkPromo .content');
			if (res.id) {
				content.html([
					'<h4>Promo code is valid until: </h4>' + res.promo_end
				].join());
			} else {
				content.html([
					'<h4>Promo code is invalid.</h4>'
				].join());
			}
			$('#checkPromo').modal('show');
		});
}




// $('input#dateTimeIn, input#dateTimeOut').on('change blur',function(){
// 	quoteresult();
// });


// $('input#dateTimeIn, input#dateTimeOut').on('change blur',function(){
// 	quoteresult();
// });



function signup_here() {
	$("#quote_mainwrap").hide();
	$("#content_wrap").show();
	$('#fname')[0].focus();
}


$('#quotebtn').click(function() {
	quoteresult();
	
});


$(".dateOut_wrapper label.error, .dateIn_wrapper label.error").hide();

function quoteresult() {    
    var dateFormat = "DD/MM/YYYY";    
    var firstDate = moment($("#dateIn").val(),dateFormat);
	var secondDate = moment($("#dateOut").val(),dateFormat);
	
	// console.log(firstDate.inspect(), secondDate.inspect());
  
	// if (firstDate.isValid() && secondDate.isValid()) {
	//   var diff = secondDate.diff(firstDate, 'days', 'minutes', 'seconds');
	
	// 	var price = diff * 5;
	// 	var pricefilter = price.toFixed(2);
	// 	$("#quickquoteresult").html('Your Stay: <span class="txtorange">$' + pricefilter + '</span>'); 
	// }
	// console.log(firstDate.format('LT'));

	if (firstDate.isValid() == '') {
		$(".dateIn_wrapper label.error").show();
		$(".dateIn_wrapper label.error").html('Departure Date is Empty!');
	} else{
		$(".dateIn_wrapper label.error").hide();
	}

	if (secondDate.isValid() == '') {
		$(".dateOut_wrapper label.error").show();
		$(".dateOut_wrapper label.error").html('Arrival Date is Empty!');
	} else{
		$(".dateOut_wrapper label.error").hide();
	}

		// console.log(firstDate.format('L'));
		if (firstDate.isValid() && secondDate.isValid()) {
				var diff = secondDate.diff(firstDate, 'days', 'minutes');

				var price = diff * 5;
				var pricefilter = price.toFixed(2);
				

			if (firstDate.format('L') <= secondDate.format('L')) {
				$(".dateOut_wrapper label.error").hide();
				$("#quickquoteresult").show();
				$("#quickquoteresult").html('Your Stay: <span class="txtorange">$' + pricefilter + '</span><div class="tooltip1"><i class="icon-info"></i><span class="tooltiptext">pricing is per calendar day</span></div>'); 
			}else {
				$("#quickquoteresult").hide();
				$(".dateOut_wrapper label.error").show();
				$(".dateOut_wrapper label.error").html('Arrival Date should be higher than Departure Date!');
			}
		}

}

