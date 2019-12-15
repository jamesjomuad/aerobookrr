$(function () {

    $('#calendar').fullCalendar({
        header: {
            left: 'today',
            center: 'prev,prevYear title nextYear,next',
            right: 'year,month,agendaWeek,agendaDay'
        },
        height:650,
        selectable: true,
        droppable: false,
        editable: true,
        eventStartEditable: true,
        eventDurationEditable: true,
        eventLimit: true,
        dayDoubleClick:function(){
            alert('double click!');
        },
        dayClick: function(date, jsEvent, view) {
            if(view.name == 'month') {
                $('#calendar').fullCalendar('changeView', 'agendaDay');
                $('#calendar').fullCalendar('gotoDate', date);      
            }
			$(this).on("dblclick",function(){
				console.log(this)
			});  
        },
        events: function(start, end, timezone, callback){
            var timestamp = this.el.fullCalendar('getDate').unix();
            $.ajax({
                url: 'calendar/bookings',
                dataType: 'json',
                data:{
                    time: timestamp
                },
                success: function(json){
                    callback(json);
                }
            });
        },
        eventClick:function( event, jsEvent, view ) {
            location.href = event.url;
            return false;
        },
        eventMouseover: function (event, jsEvent) {
        },
        eventRender:function (event, element){
            // element.click(function(e){ e.preventDefault(); })
			// element.on("dblclick",function(){
				// console.log(event.start);
			// });
        },
        forceEventDuration: true,
        loading: function(){
            $('.loading-indicator-container').hide();
        },
    }).fullCalendar( 'today' );

});