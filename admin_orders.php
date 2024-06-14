<?php
// Include config file
include 'config.php';

// Start session
session_start();

// Check if admin is logged in
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
}

// Handle order update form submission
if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
    $message[] = 'Payment status has been updated!';
}

// Handle order deletion
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="css/admin_style.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .orders {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-height: 80vh; /* Set maximum height */
            overflow-y: auto; /* Allow vertical overflow */
        }

        .box-container {
            flex: 1 1 300px; /* Flex properties for each box */
        }

        .box {
            /* Your existing styles */
        }

        .map-container {
            height: 200px; /* Adjust map height as needed */
        }
    </style>
</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="orders">

        <h1 class="titlepla">Placed Orders</h1>

        <?php
        // Fetch orders from database
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
        ?>
                <div class="box-container">
                    <div class="box">
                        <div>
                            <h2>Order ID: <?php echo $fetch_orders['id']; ?></h2>
                            <div id="map_<?php echo $fetch_orders['id']; ?>" class="map-container"></div>
                            <!-- Deliver Order Button -->
                        </div>
                        <p> User ID: <span><?php echo $fetch_orders['user_id']; ?></span> </p>
                        <p> Placed on: <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                        <p> Name: <span><?php echo $fetch_orders['name']; ?></span> </p>
                        <p> Number: <span><?php echo $fetch_orders['number']; ?></span> </p>
                        <p> Email: <span><?php echo $fetch_orders['email']; ?></span> </p>
                        <p> Address: <span><?php echo $fetch_orders['address']; ?></span> </p>
                        <p> Total Products: <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                        <p> Total Price: <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
                        <p> Payment Method: <span><?php echo $fetch_orders['method']; ?></span> </p>
                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <select name="update_payment">
                                <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                            <input type="submit" value="Update" name="update_order" class="option-btn">
                            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this order?');" class="delete-btn">Delete</a>
                        </form>
                        <button><a href="live_map.php?lat=<?php echo $fetch_orders['latitude']; ?>&lng=<?php echo $fetch_orders['longitude']; ?>" class="deliver-btn">Deliver Order</a></button>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">No Orders Yet!</p>';
        }
        ?>

    </section>

    <!-- Include Leaflet and Map Scripts -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        <?php
        // Generate script for each order map
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
        ?>
                var map_<?php echo $fetch_orders['id']; ?> = L.map('map_<?php echo $fetch_orders['id']; ?>').setView([<?php echo $fetch_orders['latitude']; ?>, <?php echo $fetch_orders['longitude']; ?>], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map_<?php echo $fetch_orders['id']; ?>);
                L.marker([<?php echo $fetch_orders['latitude']; ?>, <?php echo $fetch_orders['longitude']; ?>]).addTo(map_<?php echo $fetch_orders['id']; ?>)
                    .bindPopup('Order ID: <?php echo $fetch_orders['id']; ?>')
                    .openPopup();
        <?php
            }
        }
        ?>
    </script>
    <script src="js/admin_script.js"></script>

</body>

</html>