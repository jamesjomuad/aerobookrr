$(function(){
    setInterval(function() {
        $.getPosition(function(gps){
            var latlon = gps.latitude+','+gps.longitude;
            map.panTo(new google.maps.LatLng(gps.latitude, gps.longitude));
            $.request('onSetPosition',{
                data: gps
            });
        });
    }, 4000);
});

$(window).load(function(){
    initMap();
});