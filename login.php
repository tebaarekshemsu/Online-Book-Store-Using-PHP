<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if (mysqli_num_rows($select_users) > 0) {

      $row = mysqli_fetch_assoc($select_users);

      if (isset($_POST['remember_me'])) {
         setcookie('email', $email, time() + (86400 * 30), "/"); // 30 days
         setcookie('password', $_POST['password'], time() + (86400 * 30), "/"); // 30 days
      } else {
         setcookie('email', '', time() - 3600, "/");
         setcookie('password', '', time() - 3600, "/");
      }

      if ($row['user_type'] == 'admin') {
         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');
      } elseif ($row['user_type'] == 'user') {
         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');
      }
   } else {
      $message[] = 'incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
   <style>
      .form-box{
         background-color: antiquewhite;
      }
   </style>

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">

   <style>
      body {
         background-image: url('./images/new\ back.jpg'); 
         background-size: cover;
         background-repeat: no-repeat;
         background-attachment: fixed;
      }
   </style>
   
</head>

<body>

   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
   }
   ?>
   <div class="wrapper">
      <span class="icon-close"><i class='bx bx-x'></i></span>
      <div class="form-box login">
         <form action="" method="post" class="form-box">
            <h2>Login</h2>
            <div class="input-box">
               <input class="input-text" type="email" name="email" value="<?php if(isset($_COOKIE['email'])) { echo $_COOKIE['email']; } ?>">
               <label for="input-text">Email</label>
               <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-box">
               <input class="input-text" type="password" name="password" value="<?php if(isset($_COOKIE['password'])) { echo $_COOKIE['password']; } ?>">
               <label for="input-text">password</label>
               <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="remember-forgot">
               <label for="remember-me"><input type="checkbox" name="remember_me" <?php if(isset($_COOKIE['email'])) { echo 'checked'; } ?>> Remember me</label>
               <a href="email_forgot.php" style="color: orange; margin-left: 10px;"> Forgot password</a>

            </div>
            <div class="reg">
               <input type="submit" name="submit" value="login now" class="btn">
            </div>
            <div class="login-register">
               <p>Don't have an account ?<a href="register.php" class="register-link">Register</a></p>
            </div>
         </form>
      </div>
   </div>
</body>

</html>
