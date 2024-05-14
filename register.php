<?php

include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

session_start();

function send_mail($recipient, $subject, $message)
{
   $mail = new PHPMailer(true);
   try {
      $mail->isSMTP();
      $mail->SMTPDebug = 0;
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'tls';
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = 587;
      $mail->Username = 'tamedemse83@gmail.com';
      $mail->Password = 'ebgo oevx esdj tffo';
      $mail->setFrom('your-email@gmail.com', 'Online Book Store');
      $mail->addAddress($recipient);
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $message;
      $mail->send();
      return true;
   } catch (Exception $e) {
      return false;
   }
}

function generateVerificationCode($length = 6)
{
   return substr(str_shuffle(str_repeat($x = '0123456789', ceil($length / strlen($x)))), 1, $length);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $user_type = $_POST['user_type'];

   // Check if user registered as admin
   $admin_password = '';
   if ($user_type === "admin") {
      $admin_password = $_POST['admin_password'];
      if ($admin_password !== "12345678") { // Corrected admin password comparison
         echo "Invalid admin password";
         exit;
      }
   }

   // Generate verification code
   $verificationCode = generateVerificationCode();
   $_SESSION['verification_code'] = $verificationCode;
   $_SESSION['name'] = $name;
   $_SESSION['email'] = $email;
   $_SESSION['password'] = $pass;
   $_SESSION['user_type'] = $user_type;
   $_SESSION['admin_password'] = $admin_password;

   // Send verification code via email
   $message = "Your verification code is: $verificationCode";
   if (send_mail($email, "Verification Code", $message)) {
      echo "Verification code sent successfully";
      header("Location: registration_verify.php");
   } else {
      echo "Error occurred while sending verification code";
   }
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
     <style>
      body {
         background-image: url('./images/background_3.jpg'); 
         background-size: cover;
         background-repeat: no-repeat;
         background-attachment: fixed;
      }
   </style>

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
</head>

<body>

   <div class="wrapper">
      <span class="icon-close"><i class='bx bx-x'></i></span>
      <div class="form-box login">
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-box">
            <h2>Register</h2>
            <div class="input-box">
               <input class="input-text" type="text" name="name">
               <label for="input-text">Name</label>
               <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
               <input class="input-text" type="email" name="email">
               <label for="input-text">Email</label>
               <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-box">
               <input class="input-text" type="password" name="password">
               <label for="input-text">Password</label>
               <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
               <input class="input-text" type="password" name="cpassword">
               <label for="input-text">Confirm Password</label>
               <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
               <label for="user-type">User Type</label>
               <select name="user_type" class="input-text" id="user_type">
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
               </select>
               <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box admin-password" style="display: none;">
               <input class="input-text" type="password" name="admin_password">
               <label for="input-text">Admin Password</label>
               <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="reg">
               <input type="submit" name="submit" value="Register" class="btn">
            </div>
            <div class="login-register">
               <p>Already have an account?<a href="login.php" class="register-link">Login</a></p>
            </div>
         </form>
      </div>
   </div>

   <script>
      document.getElementById("user_type").addEventListener("change", function() {
         var userType = this.value;
         var adminPasswordInput = document.querySelector(".admin-password");
         if (userType === "admin") {
            adminPasswordInput.style.display = "block";
         } else {
            adminPasswordInput.style.display = "none";
         }
      });
   </script>
</body>

</html>
