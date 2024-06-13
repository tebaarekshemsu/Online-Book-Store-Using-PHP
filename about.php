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
   header('location:login.php');
   exit(); // Stop further execution
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_to_cart'])) {
   $message = $_POST['message'];
   $rating = $_POST['rating'];

   // Prepare SQL statement
   $sql = "INSERT INTO feedbacks (name, message, rating, user_id) VALUES (?, ?, ?, ?)";

   // Prepare and bind parameters
   $stmt = mysqli_prepare($conn, $sql);
   mysqli_stmt_bind_param($stmt, "ssii", $user_name, $message, $rating, $user_id);

   // Execute the statement
   if (mysqli_stmt_execute($stmt)) {
      echo "Feedback submitted successfully.";
   } else {
      echo "Error: " . mysqli_error($conn);
   }

   // Close statement and connection
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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
   <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
   <style>
      .message {
         padding-left: 25%;
      }
   </style>
</head>

<body>
   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>About Us</h3>
      <p> <a href="home.php">Home</a> / About </p>
   </div>

Tamrat, [6/14/2024 1:58 AM]
<section class="about">
      <div class="flex">
         <div class="image">
            <img src="images/aboutbook1.jpg" alt="">
         </div>
         <div class="content">
            <h3>Why choose Us?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet voluptatibus aut hic molestias, reiciendis natus fuga, cumque excepturi veniam ratione iure. Excepturi fugiat placeat iusto facere id officia assumenda temporibus?</p>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia corporis ratione saepe sed adipisci?</p>
            <a href="contact.php" class="readbtn">contact us</a>
         </div>
      </div>
   </section>

   <!-- review section starts  -->
   <section class="reviews">
      <h1 class="titlelatest">Reviews</h1>
      <div class="box-container">
         <?php if (!empty($reviews)) { ?>
             <?php foreach ($reviews as $review) { ?>
                 <div class="box">
                    <img src="<?php echo htmlspecialchars($review['photo']); ?>" alt="">
                    <p><?php echo htmlspecialchars($review['message']); ?></p>
                    <div class="stars">
                       <?php for ($i = 1; $i <= 5; $i++) {
                          echo $i <= $review['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                       } ?>
                    </div>
                    <h3><?php echo htmlspecialchars($review['name']); ?></h3>
                 </div>
             <?php } ?>
         <?php } else { ?>
             <p>No reviews available.</p>
         <?php } ?>
      </div>
   </section>
   <!-- review section ends -->

   <div class="message">
      <form action="" method="post">
         <p style="color: #f19256;">Add your review here 👇</p>
         <textarea style="border: 2px solid black; border-radius: 5px;" name="message" rows="7" cols="70" required></textarea>
         <br>
         Rate:
         <input type="number" name="rating" min="0" max="5" required style="background-color: #f19256; border: 2px solid black; border-radius: 5px; padding: 5px;">
         <br><br>
         <input type="submit" name="submit_review" value="Click to send" style="border-radius: 5px; background-color: #f19256;">
         <br>
      </form>
   </div>
   
   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>
</body>

</html>