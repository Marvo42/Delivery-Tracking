<!DOCTYPE html>
<html>
<style>
    #map {
        padding: 0%;
        margin: 0;
    }

    .main-page {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100vh;
        height: 100vh;
    }

    input#message {
        padding: 0.5em;
        border-radius: 0.25em;
        border: 1px solid gray;
        margin-bottom: 20px;

    }

    button {
        background-color: blue;
        color: white;
        padding: 0.5em 1em;
        border-radius: 0.25em;
        border: none;
        margin-left: 10px;
    }
</style>

<body class="main-page">

    <input id="message" type="text">
    <button onclick="sendMessage()">Send</button>

    <div id="googleMap" style="width:100%;height:600px;"></div>

    <script src="/path/to/ws.js"></script>
    <script>
        var map;
        var marker;
        var pos;
        var socket;

        function myMap() {
            map = new google.maps.Map(document.getElementById('googleMap'), {
                zoom: 8
            });

            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    map.setCenter(pos);

                    marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        title: 'Your Location'
                    });

                    new google.maps.Marker({
                        position: {
                            lat: -1.283390,
                            lng: 36.970810
                        },
                        map: map,
                        icon: {
                            url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                        },
                        title: 'PARCEL-1'
                    });

                    new google.maps.Marker({
                        position: {
                            lat: -1.457540,
                            lng: 36.979198
                        },
                        map: map,
                        icon: {
                            url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                        },
                        title: 'PARCEL-2'
                    });

                    navigator.geolocation.watchPosition(onPositionUpdate);

                }, function () {
                    handleLocationError(true, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, map.getCenter());
            }

        }

        function onPositionUpdate(position) {
            pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setCenter(pos);
            marker.setPosition(pos);

            socket = new WebSocket('ws://localhost:3000');

            // socket.onopen = function (event) {
            //     // Send a message to the server when the socket is opened
            //     socket.send();
            // }

            socket.onmessage = function (event) {
                console.log(event);
                const data = JSON.parse(event.data);
                const lat = data.latitude;
                const lng = data.longitude;

                // Update the marker position
                marker.setPosition({ lat: lat, lng: lng });
            }
        }

        function handleLocationError(browserHasGeolocation, pos) {
            // handle error here
            alert("Geolocation service failed.")
        }

        function sendMessage() {
            var message = document.getElementById("message").value;
            socket.send(JSON.stringify({ latitude: pos.lat, longitude: pos.lng, reference_number: message, }));
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeN7CMsoLBITxj5U3Aaq5JsmgqMMQGcfw&callback=myMap"></script>

</body>

</html>