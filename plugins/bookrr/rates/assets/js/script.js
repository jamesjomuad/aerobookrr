$(function () {
    var $Calendar = $('#calendar');
    var Options = {
        plugins: ['interaction', 'dayGrid', 'timeGrid'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventOverlap: false,
        events: function(start, end, timezone, callback){
            var timestamp = this.el.fullCalendar('getDate').unix();
            $.ajax({
                url: 'rates/events',
                dataType: 'json',
                data: {
                    time: timestamp
                },
                success: function(json) {
                    callback(json);
                }
            });
        },
        selectable: true,
        select: function (start, end, jsEvent, view) {
            var start = start.unix();
            var end = end.unix();
            $('#create').data('start',start);
            $('#create').data('end',end);
        }
    };

    $Calendar.fullCalendar(Options);
    $Calendar.fullCalendar( 'today' );
    
    $('[href="#calendar"]').on('shown.bs.tab', function(event){
        $Calendar.fullCalendar('render');
    });
});


// $('#calendar').fullCalendar('clientEvents')