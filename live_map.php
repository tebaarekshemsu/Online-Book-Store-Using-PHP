<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Map</title>
  <!-- Add Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <!-- Add Leaflet Routing Machine CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
  <style>
    #map {
      height: 400px;
    }
  </style>
</head>

<body>
  <h1>Live Map</h1>
  <div id="map"></div>

  <!-- Add Leaflet JavaScript -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <!-- Add Leaflet Routing Machine JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
  <script>
    var map = L.map('map').setView([0, 0], 13); // Default to (0, 0) with zoom level 13

    // Try to get user's current location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        map.setView([lat, lng], 13); // Set map center to user's current location initially
        // Add a marker at user's current location with description
        var currentLocationMarker = L.marker([lat, lng]).addTo(map)
          .bindPopup("Current Location").openPopup();

        // Get delivery location from PHP variables
        var deliveryLat = <?php echo isset($_GET['lat']) ? $_GET['lat'] : '0'; ?>;
        var deliveryLng = <?php echo isset($_GET['lng']) ? $_GET['lng'] : '0'; ?>;

        // Add marker at delivery location
        var deliveryLocationMarker = L.marker([deliveryLat, deliveryLng]).addTo(map)
          .bindPopup("Delivery Location").openPopup();

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Routing from current location to delivery location
        L.Routing.control({
          waypoints: [
            L.latLng(lat, lng), // Current location
            L.latLng(deliveryLat, deliveryLng) // Delivery location
          ],
          routeWhileDragging: true,
          lineOptions: {
            styles: [{color: '#00f', opacity: 0.8, weight: 7}]
          }
        }).addTo(map);

      }, function(error) {
        console.error('Error getting current location:', error);
      });
    } else {
      console.error('Geolocation is not supported by this browser.');
    }
  </script>
</body>

</html>