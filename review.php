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

  // Insert review into Views table
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
  <title>Home</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style2.css">
  <link rel="stylesheet" href="css/styles.css">
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <style>
    .reviews-container {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      border-radius: 5px;
      padding: 20px;
      border: 1px solid #ddd;
      z-index: 1000; /* Ensure the pop-up is above other content */
      width: 80%;
      max-width: 600px;
      max-height: 80%;
      overflow-y: auto;
      box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.1); /* Add shadow for better visibility */
      background-color: silver;
    }

    .review {
      margin-bottom: 10px;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
    }

    .products {
      min-height: 50vh;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
    }

    .box {
      width: 300px;
      margin: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
      position: relative;
    }

    .box img {
      width: 100%;
      height: auto;
      margin-bottom: 10px;
    }

    .name {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .see-review-btn {
      margin-top: 10px;
      padding: 5px 10px;
      background-color: orange;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    .see-review-btn:hover {
      background-color: #0056b3;
    }

    textarea {
      width: 100%;
      box-shadow: 0 0 10px 5px silver;
      margin-top: 5px;
    }

    .readbtn {
      padding: 10px;
      margin-top: 10px;
      background-color: orange;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    .readbtn:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>

  <?php include 'header.php'; ?>

  <section class="products" style="margin-top: 150px;">
    
    <div class="box-container">
      <?php
      $select_products = mysqli_query($conn, "SELECT p.*, u.name AS user_name FROM products p LEFT JOIN Views v ON p.id = v.product_id LEFT JOIN users u ON v.user_id = u.id GROUP BY p.id") or die('query failed');
      if (mysqli_num_rows($select_products) > 0) {
        while ($fetch_products = mysqli_fetch_assoc($select_products)) {
          // Fetch reviews for each product
          $product_id = $fetch_products['id'];
      ?>
          <div class="box">
            <img class="image" src="<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>

            <!-- Button to toggle reviews -->
            <button class="see-review-btn" data-product-id="<?php echo $product_id; ?>">See Reviews</button>

            <!-- Hidden reviews container -->
            <div class="reviews-container" id="reviews-<?php echo $product_id; ?>">
              <div class="reviews">
                <?php
                $select_reviews = mysqli_query($conn, "SELECT v.*, u.name FROM Views v INNER JOIN users u ON v.user_id = u.id WHERE product_id = '$product_id'");
                while ($fetch_reviews = mysqli_fetch_assoc($select_reviews)) {
                  echo "<div class='review'><strong>" . $fetch_reviews['name'] . "</strong> 💬 : <span style='font-size: 25px;'>" . $fetch_reviews['view'] . "</span></div>";
                }
                ?>
              </div>
              <button class="close-btn" data-product-id="<?php echo $product_id; ?>">X</button>
            </div>

            <!-- Form to add review -->
            <form action="" method="post">
              <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
              <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
              <p style="color: green;">Add your review here 👇</p>
              <textarea name="review" rows="4" cols="30" required></textarea><br><br>
              <input type="submit" name="add_to_cart" value="Click to Add Review" class="readbtn">
            </form>
          </div>
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
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const seeReviewButtons = document.querySelectorAll('.see-review-btn');
      seeReviewButtons.forEach(button => {
        button.addEventListener('click', function () {
          const productId = this.getAttribute('data-product-id');
          const reviewsContainer = document.getElementById(`reviews-${productId}`);
          reviewsContainer.style.display = 'block';
        });
      });

      const closeButtons = document.querySelectorAll('.close-btn');
      closeButtons.forEach(button => {
        button.addEventListener('click', function () {
          const productId = this.getAttribute('data-product-id');
          const reviewsContainer = document.getElementById(`reviews-${productId}`);
          reviewsContainer.style.display = 'none';
        });
      });
    });
  </script>

</body>

</html>
