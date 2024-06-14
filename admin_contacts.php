<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
   exit; // Ensure no further execution after redirection
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('Query failed');
   header('location:admin_contacts.php');
   exit; // Ensure no further execution after redirection
}

// Initialize filter variable
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Filter conditions
$filter_condition = '';
if ($filter == 'feedback') {
   $filter_condition = "WHERE type_of_message = 'Feedback'";
} elseif ($filter == 'order_problem') {
   $filter_condition = "WHERE type_of_message = 'Order Problem'";
} elseif ($filter == 'help') {
   $filter_condition = "WHERE type_of_message = 'Help'";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom admin CSS file link -->
   <link rel="stylesheet" href="css/admin_style.css">
   <style>
      .filter-buttons {
         width: 100%;
         display: flex;
         font-size: 2rem;
         justify-content: space-between;
         padding-bottom: 10px;
      }
   </style>

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="messages">

      <h1 class="titleshop">Messages</h1>

      <!-- Filter buttons -->
      <div class="filter-buttons">
         <a href="admin_contacts.php" class="filter-button">All Messages</a>
         <a href="admin_contacts.php?filter=feedback" class="filter-button feedback">Feedback</a>
         <a href="admin_contacts.php?filter=order_problem" class="filter-button order-problem">Order Problems</a>
         <a href="admin_contacts.php?filter=help" class="filter-button help">Help</a>
      </div>


      <div class="box-container">
         <?php
         // Select messages based on filter condition
         $select_message = mysqli_query($conn, "SELECT * FROM `message` $filter_condition") or die('Query failed');
         if (mysqli_num_rows($select_message) > 0) {
            while ($fetch_message = mysqli_fetch_assoc($select_message)) {
               ?>
               <div class="box">
                  <p> User ID: <span><?php echo $fetch_message['user_id']; ?></span> </p>
                  <p> Name: <span><?php echo $fetch_message['name']; ?></span> </p>
                  <p> Number: <span><?php echo $fetch_message['number']; ?></span> </p>
                  <p> Email: <span><?php echo $fetch_message['email']; ?></span> </p>
                  <p> Message: <span><?php echo $fetch_message['message']; ?></span> </p>
                  <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>"
                     onclick="return confirm('Delete this message?');" class="delete-btn">Delete Message</a>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">No messages found!</p>';
         }
         ?>
      </div>

   </section>

   <!-- Custom admin JS file link -->
   <script src="js/admin_script.js"></script>

</body>

</html>