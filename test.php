<!DOCTYPE html>
<html>
<head>
    <title>Add Marker to Map</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
        html, body, #map-canvas {
            height: 90%;
            margin: 0px;
            padding: 0px
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIsFelbGq5aPrRaFovVqapI7ZeCjxtFYI&v=3.exp"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script>
        var map;
        function initialize() {
            var mapOptions = {
                zoom: 8,
                center: new google.maps.LatLng(-34.397, 150.644)
            };
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        }
        google.maps.event.addDomListener(window, 'load', initialize);

        jQuery(document).ready(function(){

            jQuery("#addmarker").bind("click", function(){
                console.log("Click");
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng( -34.397,150.644),
                    map: map,
                    title: 'Hello World!'
                });

                var contentString = '<div id="content" style="width: 200px; height: 200px;"><h1>Overlay</h1></div>';
                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });

                // To add the marker to the map, call setMap();
                marker.setMap(map);
            });
        });
    </script>
</head>
<body>
<div id="map-canvas"></div>
<a href="javascript:void(0);" id="addmarker">Add marker</a>
</body>
</html>