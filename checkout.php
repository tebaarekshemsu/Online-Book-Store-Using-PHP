<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

if (!isset($user_id)) {
   header('location:login.php');
   exit; // Exit after redirect
}

$message = '';

if (isset($_POST['order_btn'])) {
   // Get the submitted latitude and longitude
   $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
   $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);

   // Other form submission handling logic goes here
   // For example, you can insert the order details into the database
   $name = mysqli_real_escape_string($conn, $user_name);
   $number = mysqli_real_escape_string($conn, $_POST['number']);
   $email = mysqli_real_escape_string($conn, $user_email);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $cart_total = 0;
   $cart_quantity[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($cart_query) > 0) {
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
         $cart_quantity[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ', $cart_quantity);

   $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if ($cart_total == 0) {
      $message = 'Your cart is empty';
   } else {
      if (mysqli_num_rows($order_query) > 0) {
         $message = 'Order already placed!';
      } else {
         mysqli_query($conn, "INSERT INTO orders(user_id, name, number, email, method, total_products, total_price,payment_status, latitude, longitude) VALUES('$user_id', '$name', '$number', '$email', '$method', '$total_products', '$cart_total', 'pending', '$latitude', '$longitude')") or die('query failed');
         $message = 'Order placed successfully!';
         mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'") or die('query failed');
         header('location: shop.php');
         exit;
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <!-- Add Leaflet CSS -->
   <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="headingcheckout">
      <h3>Checkout</h3>
      <p><a href="home.php">Home</a> / Checkout </p>
   </div>

<section class="checkout">
      <form method="post" action="">
         <div class="inputBox">
            <span>Your Number :</span>
            <input type="text" name="number" required placeholder="Enter Your Number">
         </div>
         <div class="inputBox">
            <span>Payment Method :</span>
            <select name="method">
               <option value="cash on delivery">Cash On Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="paypal">Paypal</option>
               <option value="paytm">Paytm</option>
            </select>
         </div>
         <!-- Submit Button -->
         <input type="submit" value="Order Now" class="btne" name="order_btn">
         <h3>Choose Location To be Delivered</h3>
         <!-- Map Container -->
         <div id="map" style="height: 400px;"></div>
         <!-- Latitude and Longitude Input Fields -->
         <input type="hidden" name="latitude" id="latitude">
         <input type="hidden" name="longitude" id="longitude">
      </form>
      <!-- Display Message -->
      <?php if (!empty($message)) : ?>
         <p><?php echo $message; ?></p>
      <?php endif; ?>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Add Leaflet JavaScript -->
   <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
   <script>
      // Initialize the map
      var map = L.map('map').setView([0, 0], 13); // Default to (0, 0) with zoom level 13

      // Declare marker variable
      var chosenLocationMarker;

      // Try to get user's current location
      if (navigator.geolocation) {
         navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            // Set map center to user's current location initially
            map.setView([lat, lng], 13);
            // Add marker at user's current location
            chosenLocationMarker = L.marker([lat, lng]).addTo(map);
            // Set the latitude and longitude input fields
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
         }, function(error) {
            console.error('Error getting current location:', error);
         });
      } else {
         console.error('Geolocation is not supported by this browser.');
      }

      // Add OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
         attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      // Add event listener for map click
      map.on('click', function(e) {
         // Remove existing chosen location marker
         if (chosenLocationMarker) {
            map.removeLayer(chosenLocationMarker);
         }
         // Add marker at clicked location
         chosenLocationMarker = L.marker(e.latlng).addTo(map);
         // Update latitude and longitude input fields
         document.getElementById('latitude').value = e.latlng.lat;
         document.getElementById('longitude').value = e.latlng.lng;
      });
   </script>

</body>

</html>