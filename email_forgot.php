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
  $email = $_POST['email'];
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    exit;
  }

  $sql = "SELECT id FROM users WHERE email = '$email'";
  $result = $conn->query($sql);
  if ($result->num_rows == 0) {
    echo "Email not found in database";
    exit;
  }

  $verificationCode = generateVerificationCode();
  $_SESSION['verification_code'] = $verificationCode;
  $_SESSION['email'] = $email;

  if (send_mail($email, "VERIFICATION CODE", $verificationCode)) {
    echo "Verification code sent successfully";
    header("Location: verification.php");
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
      .form-box{
         background-color: antiquewhite;
      }
   </style>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/styles.css">
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
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-box">
        <h2>Restore</h2>
        <div class="input-box">
          <input class="input-text" type="email" name="email">
          <label for="input-text">Email</label>
          <i class='bx bxs-envelope'></i>
        </div>
        <div class="reg">
          <input type="submit" name="submit" value="Send Verification" class="btn">
        </div>
      </form>
    </div>
  </div>
</body>

</html>
