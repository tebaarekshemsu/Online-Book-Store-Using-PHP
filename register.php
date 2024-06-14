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

function sanitize_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $name = sanitize_input($_POST['name']);
   if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
      $error_message = "Name can only contain letters and white spaces";
   }

   $email = filter_var(sanitize_input($_POST['email']), FILTER_SANITIZE_EMAIL);
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format";
   }

   $photo =  mysqli_real_escape_string($conn, sanitize_input($_POST['photo']));

   $password = sanitize_input($_POST['password']);
   $cpassword = sanitize_input($_POST['cpassword']);
   if (strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[\W_]/", $password)) {
      $error_message = "Password must be at least 8 characters long and contain at least one letter, one number, and one special character";
   }
   if ($password !== $cpassword) {
      $error_message = "Passwords do not match";
   }

   if (empty($error_message)) {
      $pass = mysqli_real_escape_string($conn, md5($password));
      $user_type = sanitize_input($_POST['user_type']);

      $admin_password = '';
      if ($user_type === "admin") {
         $admin_password = sanitize_input($_POST['admin_password']);
         if ($admin_password !== "12345678") {
            $error_message = "Invalid admin password";
         }
      }

      if (empty($error_message)) {
         $verificationCode = generateVerificationCode();
         $_SESSION['verification_code'] = $verificationCode;
         $_SESSION['name'] = $name;
         $_SESSION['email'] = $email;
         $_SESSION['photo'] = $photo;
         $_SESSION['password'] = $pass;
         $_SESSION['user_type'] = $user_type;
         $_SESSION['admin_password'] = $admin_password;

         $message = "Your verification code is: $verificationCode";
         if (send_mail($email, "Verification Code", $message)) {
            echo "Verification code sent successfully";
            header("Location: registration_verify.php");
            exit;
         } else {
            $error_message = "Error occurred while sending verification code";
         }
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
   <title>Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
   <style>
      body {
         background-image: url('./images/new\ back.jpg'); 
         background-size: cover;
         background-repeat: no-repeat;
         background-attachment: fixed;
      }
      .form-box{
         background-color: antiquewhite;
      }
      .error-message {
         color: red;
         font-weight: bold;
      }
   </style>
</head>
<body>
   <div class="wrapper">
      <span class="icon-close"><i class='bx bx-x'></i></span>
      <div class="form-box login">
         <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
         <?php endif; ?>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-box">
            <h2>Register</h2>
            <div class="input-box">
               <input class="input-text" type="text" name="name" required>
               <label for="input-text">Name</label>
               <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
               <input class="input-text" type="email" name="email" required>
               <label for="input-text">Email</label>
               <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-box">
               <input class="input-text" type="text" name="photo">
               <label for="input-text">Upload Photo URL</label>
               <i class='bx bxs-envelope'></i>
            </div>
            <div class="input-box">
               <input class="input-text" type="password" name="password" required>
               <label for="input-text">Password</label>
               <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
               <input class="input-text" type="password" name="cpassword" required>
               <label for="input-text">Confirm Password</label>
               <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
               <label for="user-type">User Type</label>
               <select name="user_type" class="input-text" id="user_type" required>
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