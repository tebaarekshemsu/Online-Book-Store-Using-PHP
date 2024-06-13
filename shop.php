<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Fetch available piece from the database
   $fetch_product = mysqli_query($conn, "SELECT pieces FROM `products` WHERE name = '$product_name'") or die('query failed');
   $product_data = mysqli_fetch_assoc($fetch_product);
   $pieces = $product_data['pieces'];

   if ($product_quantity > $pieces) {
      $message[] = 'Sorry, only ' . $pieces . ' piece(s) available!';
   } else {
      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_cart_numbers) > 0) {
         $message[] = 'Already added to cart!';
      } else {
         // Decrease the available piece in the products table
         $new_pieces = $pieces - $product_quantity;
         mysqli_query($conn, "UPDATE `products` SET pieces = '$new_pieces' WHERE name = '$product_name'") or die('query failed');

         mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
         $message[] = 'Product added to cart!';
      }
   }
}

$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
$new_sort_order = $sort_order === 'ASC' ? 'DESC' : 'ASC';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>

   <?php include 'header.php'; ?>


   <div class="headingshop">
      <h3>Our Shop</h3>
      <p> <a href="home.php">Home</a> / Shop </p>
   </div>

   <section class="products">

      <h1 class="titleProducts">Latest Products</h1>
      
      <!-- Sorting button -->
      <div class="sort-container">
         <button class="sort-btn"><a href="shop.php?sort=<?php echo $new_sort_order; ?>">Sort by Title: <?php echo $sort_order === 'ASC' ? 'Ascending' : 'Descending'; ?></a></button>
      </div>

      <div class="box-container">

         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY name $sort_order") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <form action="" method="post" class="box">
                  <img class="image" src="<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
                  <!-- Fetch maximum piece value from the database -->
                  <input type="number" min="1" max="<?php echo $fetch_products['pieces']; ?>" name="product_quantity" value="1" class="qty">
                  <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <input type="submit" value="Add to Cart" name="add_to_cart" class="btne">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
         ?>
      </div>

   </section>


   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>