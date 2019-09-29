$.request('onGetPosition',{
    success: function(gps){
        if(gps!=false){
            initMap();

            $(function(){
                setInterval(function() {
                    $.request('onGetPosition',{
                        success: function(gps){
                            map.panTo(new google.maps.LatLng(gps.latitude, gps.longitude));
                        }
                    });
                }, 4000);
            });
        }
    }
});