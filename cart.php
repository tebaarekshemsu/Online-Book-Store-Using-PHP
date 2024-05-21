<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['update_cart'])) {
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'Cart Quantity Updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   // Fetch product details to increase available pieces
   $fetch_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   $product_data = mysqli_fetch_assoc($fetch_cart);
   $product_name = $product_data['name'];
   $product_quantity = $product_data['quantity'];
   // Increase available pieces in the products table
   mysqli_query($conn, "UPDATE `products` SET pieces = pieces + '$product_quantity' WHERE name = '$product_name'") or die('query failed');
   // Delete item from cart
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
   // Fetch cart details to increase available pieces
   $fetch_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   while ($product_data = mysqli_fetch_assoc($fetch_cart)) {
      $product_name = $product_data['name'];
      $product_quantity = $product_data['quantity'];
      // Increase available pieces in the products table
      mysqli_query($conn, "UPDATE `products` SET pieces = pieces + '$product_quantity' WHERE name = '$product_name'") or die('query failed');
   }
   // Delete all items from cart
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="headingcatr">
      <h3>Shopping Cart</h3>
      <p> <a href="home.php">Home</a> / Cart </p>
   </div>

   <section class="shopping-cart">

      <h1 class="titleproducts">Products Added</h1>

      <div class="box-container">
         <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
         ?>
               <div class="box">
                  <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Delete this from cart?');"></a>
                  <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_cart['name']; ?></div>
                  <div class="price">$<?php echo $fetch_cart['price']; ?>/-</div>
                  <form action="" method="post">
                     <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                     <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                     <input type="submit" name="update_cart" value="Update" class="up-btn">
                  </form>
                  <div class="sub-total">Sub Total : <span>$<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span> </div>
               </div>
         <?php
               $grand_total += $sub_total;
            }
         } else {
            echo '<p class="empty">Your cart is empty</p>';
         }
         ?>
      </div>

      <div style="margin-top: 2rem; text-align:center;">
         <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
      </div>

      <div class="cart-total">
         <p>Grand Total : <span>$<?php echo $grand_total; ?>/-</span></p>
         <div class="flex">
            <a href="shop.php" class="btne">Continue Shopping</a>
            <a href="checkout.php" class="proceed-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
         </div>
      </div>

   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>