<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if (!isset($user_id)) {
    header('Location: login.php');
    exit(); // Stop further execution
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_review'])) {
    $message = $_POST['message'];
    $rating = $_POST['rating'];

    // Prepare SQL statement
    $sql = "INSERT INTO feedbacks (message, rating, user_id) VALUES (?, ?, ?)";

    // Prepare and bind parameters
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sid", $message, $rating, $user_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Feedback submitted successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Fetch reviews from the database
$reviews_sql = "SELECT message, rating, user_id FROM feedbacks";
$reviews_result = mysqli_query($conn, $reviews_sql);
$reviews = mysqli_fetch_all($reviews_result, MYSQLI_ASSOC);

// Fetch user details for each review
foreach ($reviews as $key => $review) {
    $user_id = $review['user_id'];
    $user_sql = "SELECT name, photo FROM users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $user_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name, $photo);
        mysqli_stmt_fetch($stmt);
        $reviews[$key]['name'] = $name;
        $reviews[$key]['photo'] = $photo;
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <!-- Font Awesome CDN link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS files -->
   <link rel="stylesheet" href="css/style2.css">
   <link rel="stylesheet" href="css/styles.css">
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
   <style>
      .message {
         padding-left: 25%;
      }
      
      .comments-container {
         margin-top: 20px;
         padding: 20px;
         background-color: #f9f9f9;
         border: 1px solid #ccc;
         border-radius: 8px;
         margin-left: 350px;
         margin-right: 350px;
         max-height: 500px;
         overflow-y: scroll;
      }

      .comment {
         border-bottom: 1px solid #ddd;
         padding-bottom: 15px;
         margin-bottom: 15px;
      }

      .comment-header {
         display: flex;
         align-items: center;
         margin-bottom: 10px;
      }

      .comment-header img {
         width: 50px;
         height: 50px;
         border-radius: 50%;
         margin-right: 10px;
      }

      .comment-header h3 {
         margin: 0;
         font-size: 18px;
      }

      .comment-text {
         font-size: 16px;
         line-height: 1.6;
         color: #333;
      }

      .stars {
         color: #f39c12;
         margin-top: 5px;
      }

      .stars .fas,
      .stars .far {
         font-size: 18px;
         margin-right: 2px;
      }
     
   </style>
</head>

<body>
   <?php include 'header.php'; ?>

   <section style="margin-top: 150px;" class="about">
      <div class="flex">
         <div class="image">
            <img src="images/aboutbook1.jpg" alt="">
         </div>
         <div class="content">
            <h3>Why choose Us?</h3>
            <p>At our online bookstore, we offer a vast collection of books across various genres to cater to all kinds of readers. Whether you're looking for the latest bestsellers, classic literature, or specialized academic texts, we have something for everyone.</p>
            <p>Our user-friendly platform ensures a seamless shopping experience, from browsing to checkout. With competitive pricing, regular discounts, and a dedicated customer support team, we strive to make your book-buying experience enjoyable and convenient.</p>
            <a href="contact.php" class="readbtn">Contact Us</a>
         </div>
      </div>
   </section>

   <!-- Reviews section starts  -->
   <section class="reviews" >
      <h1 class="titlelatest" style="color: black;">Reviews</h1>
      <div class="comments-container" style="  box-shadow: 0 0 10px 5px silver">
         <?php if (!empty($reviews)) { ?>
             <?php foreach ($reviews as $review) { ?>
                 <div class="comment">
                    <div class="comment-header">
                       <img src="<?php echo htmlspecialchars($review['photo']); ?>" alt="<?php echo htmlspecialchars($review['name']); ?>">
                       <h3><?php echo htmlspecialchars($review['name']); ?></h3>
                       <div class="stars">
                          <?php for ($i = 1; $i <= 5; $i++) {
                             echo $i <= $review['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                          } ?>
                       </div>
                    </div>
                    <p class="comment-text"><?php echo htmlspecialchars($review['message']); ?></p>
                 </div>
             <?php } ?>
         <?php } else { ?>
             <p>No reviews available.</p>
         <?php } ?>
      </div>
   </section>
   <!-- Reviews section ends -->

   <div class="message">
      <form action="" method="post">
         <p style="color: black;">Add your review here 👇</p>
         <textarea style="box-shadow: 0 0 10px 5px silver; margin:20px ; padding:10px;" name="message" rows="7" cols="70" required></textarea>
         <br>
         Rate:
         <input type="number" name="rating" min="0" max="5" required style="border-radius: 5px; padding: 5px; box-shadow: 0 0 10px 5px silver">
         <br><br>
         <input class="readbtn" type="submit" name="submit_review" value="Click to Send">
         <br>
      </form>
   </div>
   
   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>
</body>

</html>
