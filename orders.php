<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

// Function to fetch address from Nominatim API
function getAddressFromCoordinates($latitude, $longitude) {
    // Construct API URL
    $apiUrl = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$latitude&lon=$longitude&zoom=18&addressdetails=1";

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Your-App-Name');

    // Execute cURL session
    $jsonResponse = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $data = json_decode($jsonResponse, true);

    // Check if 'display_name' field exists in the response
    if (isset($data['display_name'])) {
        return $data['display_name']; // Return the address
    } else {
        return 'Address not found'; // Return a default message if address not found
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="headingorders">
      <h3>your orders</h3>
      <p> <a href="home.php">Home</a> / Orders </p>
   </div>

   <section class="placed-orders">

      <h1 class="titlePlaced">Placed Orders</h1>

      <div class="box-container">

         <?php
         $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
                // Fetch address using latitude and longitude
                $latitude = $fetch_orders['latitude'];
                $longitude = $fetch_orders['longitude'];
                $address = getAddressFromCoordinates($latitude, $longitude);
         ?>
               <div class="box boxi">
                  
                  <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                  <p> number : <span><?php echo $fetch_orders['number']; ?></span> </p>
                  <p> placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                  <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                  <p> address : <span><?php echo $address; ?></span> </p> <!-- Display fetched address -->
                  <p> payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>
                  <p> your orders : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                  <p> total price : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
                  <p> payment status : <span style="color:<?php
                                                            if ($fetch_orders['payment_status'] == '') {
                                                               $fetch_orders['payment_status'] = 'pending';
                                                            }

                                                            if ($fetch_orders['payment_status'] == 'pending') {
                                                               echo 'red';
                                                            } else {
                                                               echo 'green';
                                                            } ?>;"><?php echo $fetch_orders['payment_status']; ?></span> </p>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">No Orders Placed Yet!</p>';
         }
         ?>
      </div>

   </section>


   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
