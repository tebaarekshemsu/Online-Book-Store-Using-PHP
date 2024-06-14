<?php
include 'config.php'; // Include your database connection file

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit; // Ensure no further execution after redirection
}

if (isset($_POST['send'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);
   $type_of_message = mysqli_real_escape_string($conn, $_POST['type_of_message']); // New variable for type_of_message

   // Check if the message with the same details already exists
   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('Query failed');

   if (mysqli_num_rows($select_message) > 0) {
      $message[] = 'Message already sent!';
   } else {
      // Insert message into the database with user_id and type_of_message
      $insert_query = "INSERT INTO `message` (user_id, name, email, number, message, type_of_message) VALUES ('$user_id', '$name', '$email', '$number', '$msg', '$type_of_message')";
      
      if (mysqli_query($conn, $insert_query)) {
         $message[] = 'Message sent successfully!';
      } else {
         $message[] = 'Error sending message: ' . mysqli_error($conn);
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file links -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
   <style>
   .contact2 form{
   margin:0 auto;
   background-color: var(--light-bg);
  border-radius: 20px;
   padding:2rem;
   max-width: 50rem;
   margin:0 auto;
   text-align: center;
}

.contact2 form h3{
   font-size: 2.5rem;
   text-transform: uppercase;
   margin-bottom: 1rem;
   color:orange;
}

.contact2 form .box{
   margin:1rem 0;
   width: 100%;
   
   background-color: var(--white);
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   border-radius: .5rem;
   box-shadow: 0 0 10px 5px silver;
}

.contact2 form textarea{
   height: 20rem;
   resize: none;
}
   
   


   </style>

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading-contact">
      <h3>Contact us</h3>
      <p><a href="home.php">Home</a> / Contact</p>
   </div>

   <section class=" contact2">
      <form action="" method="post">
         <h3>Write Here!</h3>
         <input type="text" name="name" required placeholder="Enter your name" class="box">
         <input type="email" name="email" required placeholder="Enter your email" class="box">
         <input type="number" name="number" required placeholder="Enter your number" class="box">
         <select name="type_of_message" class="box" required>
            <option value="">Select type of message</option>
            <option value="Feedback">Feedback</option>
            <option value="Order Problem">Order Problem</option>
            <option value="Help">Help</option>
            <!-- Add more options as needed -->
         </select>
         <textarea name="message" class="box" placeholder="Enter your message" id="" cols="30" rows="10"></textarea>
         <input type="submit" value="Send Message" name="send" class="readbtn">
      </form>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Custom JS file link -->
   <script src="js/script.js"></script>

</body>

</html>
