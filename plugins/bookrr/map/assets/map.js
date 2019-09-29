var map, marker, infoWindow;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: -34.397,
            lng: 150.644
        },
        zoom: 17
    });

    marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(59.327383, 18.06747),
        icon : 'https://cdn1.iconfinder.com/data/icons/Webtoys/64/Pin.png'
    });

      
    // infoWindow = new google.maps.InfoWindow;

    // Try HTML5 geolocation.
    // if (navigator.geolocation) {
    //     navigator.geolocation.getCurrentPosition(function (position) {
    //         var pos = {
    //             lat: position.coords.latitude,
    //             lng: position.coords.longitude
    //         };

    //         // infoWindow.setPosition(pos);
    //         // infoWindow.setContent('You are here');
    //         // infoWindow.open(map);
    //         map.setCenter(pos);
    //     }, function () {
    //         handleLocationError(true, infoWindow, map.getCenter());
    //     });
    // } else {
    //     // Browser doesn't support Geolocation
    //     handleLocationError(false, infoWindow, map.getCenter());
    // }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}


$.getPosition = function (fn){
    if (navigator.geolocation) {
        var data = null;
        navigator.geolocation.getCurrentPosition(function(position) {
            data = {
                'latitude': position.coords.latitude,
                'longitude': position.coords.longitude,
                'accuracy': position.coords.accuracy
            };
            if(fn){
               fn(data); 
            }
        },function(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    x.innerHTML = "User denied the request for Geolocation."
                    break;
                case error.POSITION_UNAVAILABLE:
                    x.innerHTML = "Location information is unavailable."
                    break;
                case error.TIMEOUT:
                    x.innerHTML = "The request to get user location timed out."
                    break;
                case error.UNKNOWN_ERROR:
                    x.innerHTML = "An unknown error occurred."
                    break;
            }
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
    return this;
};