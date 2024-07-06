<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit(); // Ensure the script stops executing if the user is not logged in
}

if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'Already Added To Cart!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'Product Added To Cart!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style2.css">
   <link rel="stylesheet" href="css/styles.css">
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

   <?php include 'header.php'; ?>

   <section class="home" >
      <div class="content">
         <h3>Hand Picked Books to your door.</h3>
          <a href="about.php" class="white-btn">discover more</a>
      </div>
   </section>

   <section class="products">
      <h1 class="titlelatest" style="color:black;">Latest Products</h1>
      <div class="box-container">
         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <div class="box">
                  <img class="image" src="<?php echo $fetch_products['image']; ?>" alt="">
                  <h3 class="name" style="font-family: Arial, sans-serif;"><strong>Name:</strong> <span style="font-weight: lighter;"><?php echo $fetch_products['name']; ?></span></h3>
                  <h4 class="genre" style="font-family: Arial, sans-serif;"><strong>Genre:</strong> <span style="font-weight: lighter;"><?php echo $fetch_products['genre']; ?></span></h4>
                  <h5 class="author" style="font-family: Arial, sans-serif;"><strong>Author:</strong> <span style="font-weight: lighter;"><?php echo $fetch_products['author']; ?></span></h5>
                  <h6 class="description" style="font-family: Arial, sans-serif; font-weight: lighter;"><strong>Description:</strong> <span style="font-weight: lighter;"><?php echo $fetch_products['description']; ?></span></h6>
                  <br>
                  <a href="shop.php" class="shop-now-btn" style="background-color: #ff9900; color: #fff; padding: 10px 20px; text-decoration: none; font-family: Arial, sans-serif; border-radius: 5px;">Shop Now</a>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
         ?>
      </div>

      <div class="load-more" style="margin-top: 2rem; text-align:center">
         <a href="shop.php" class="readbtn">Load more</a>
      </div>
   </section>

   <section class="about">
      <div class="flex">
         <div class="image">
            <img src="images/shop1.jpg" alt="">
         </div>
         
      </div>
   </section>

   <section class="home-contact">
      <div class="content">
         <h3>Have Any Questions?</h3>
         <p>Don't hesitate to ask or contact us for any feedback, order problems, or help.</p>
         <a href="contact.php" class="readbtn">Contact us</a>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
