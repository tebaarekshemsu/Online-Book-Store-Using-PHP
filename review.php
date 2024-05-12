<?php
include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
  exit(); // Stop further execution
}

$user_id = $_SESSION['user_id']; // Get user id from session

if (isset($_POST['add_to_cart'])) {
  $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
  $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
  $review = mysqli_real_escape_string($conn, $_POST['review']); // Get review from form input

  // Fetch product id using product name
  $select_product_id = mysqli_query($conn, "SELECT id FROM products WHERE name = '$product_name'");
  $product_row = mysqli_fetch_assoc($select_product_id);
  $product_id = $product_row['id'];

  // Insert review into View table
  $insert_review_query = "INSERT INTO Views (view, product_id, user_id) VALUES ('$review', '$product_id', '$user_id')";
  if (mysqli_query($conn, $insert_review_query)) {
    $message[] = 'Review added successfully!';
    header("Location: {$_SERVER['REQUEST_URI']}"); // Redirect to the same page to avoid form resubmission
    exit();
  } else {
    $message[] = 'Failed to add review!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>home</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/styles.css">
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <style>
    .reviews {
      height: 100px;
      width: 100%;
      overflow: scroll;

    }

    .review {
      background-color: white;

      font-weight: lighter;
    }
  </style>
</head>

<body>

  <?php include 'header.php'; ?>

  <section class="products">
    <h1 class="titlelatest">Latest Products</h1>
    <div class="box-container">
      <?php
      $select_products = mysqli_query($conn, "SELECT p.*, u.name AS user_name FROM products p LEFT JOIN Views v ON p.id = v.product_id LEFT JOIN users u ON v.user_id = u.id GROUP BY p.id") or die('query failed');
      if (mysqli_num_rows($select_products) > 0) {
        while ($fetch_products = mysqli_fetch_assoc($select_products)) {
          // Fetch reviews for each product
          $product_id = $fetch_products['id'];
          $select_reviews = mysqli_query($conn, "SELECT v.*, u.name FROM Views v INNER JOIN users u ON v.user_id = u.id WHERE product_id = '$product_id'");
      ?>
          <div class="box">
            <img class="image" src="<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>

            <!-- Display reviews for the product -->

            <form action="" method="post">
              <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
              <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
              <p style="color: green;">add your review here 👇</p>
              <textarea style="border: 2px solid black ; border-radius: 5px;" name="review" rows="4" cols="30" required></textarea><br><br>

              <input type="submit" name="add_to_cart" value=" click to Add Review" style="border-radius: 5px; background-color:#17a2b8">
              <br>
              <div class="reviews">
                <br>
                <?php
                while ($fetch_reviews = mysqli_fetch_assoc($select_reviews)) {
                  echo "<div class='review'><strong>" . $fetch_reviews['name'] . "</strong> 💬 : <span style='font-size: 12px;'>" . $fetch_reviews['view'] . "</span></div>";
                }
                ?>

              </div>
            </form>
          </div>
      <?php
        }
      } else {
        echo '<p class="empty">no products added yet!</p>';
      }
      ?>
    </div>
  </section>

</body>

</html>
