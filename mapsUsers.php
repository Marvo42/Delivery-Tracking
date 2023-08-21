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
        var socket;

        function myMap() {
            map = new google.maps.Map(document.getElementById('googleMap'), {
                zoom: 8
            });

            // Try HTML5 geolocation.

            pos = {
                lat: -1.2744,
                lng: 36.9057
            };

            map.setCenter(pos);

            marker = new google.maps.Marker({
                position: pos,
                map: map,
                title: 'Your Location'
            });

            socket = new WebSocket('wss://frequent-evergreen-governor.glitch.me:8000');

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

        function sendMessage() {
            var message = document.getElementById("message").value;
            fetch(`http://localhost:8080/parcels/${message}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    marker.setPosition({ lat: latitude, lng: longitude });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }






    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeN7CMsoLBITxj5U3Aaq5JsmgqMMQGcfw&callback=myMap"></script>

</body>

</html>