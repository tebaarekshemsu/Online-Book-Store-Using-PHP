<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
  $emailpass = mysqli_real_escape_string($conn, $_POST['emailpass']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  // Check if the verification code matches the one stored in the session
  if (isset($_SESSION['verification_code']) && $_SESSION['verification_code'] === $emailpass) {
    // Verification code is correct
    // Now update the user's password
    $hashedPassword = md5($password);
    $email = $_SESSION['email'];

    $updatePasswordQuery = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";
    $result = mysqli_query($conn, $updatePasswordQuery);

    if ($result) {
      // Password updated successfully
      // Redirect the user to a success page or login page
      session_unset(); // Unset all session variables
      session_destroy(); // Destroy the session
      header('Location: login.php');
      exit();
    } else {
      // Failed to update password
      $message[] = 'Failed to update password. Please try again later.';
    }
  } else {
    // Verification code is incorrect
    $message[] = 'Incorrect verification code!';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

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
      <form action="" method="post" class="form-box">
        <h2>Login</h2>
        <div class="input-box">
          <input class="input-text" type="text" name="emailpass">
          <label for="input-text">Verification Code</label>
          <i class='bx bxs-envelope'></i>
        </div>
        <div class="input-box">
          <input class="input-text" type="password" name="password">
          <label for="input-text">New Password</label>
          <i class='bx bxs-lock-alt'></i>
        </div>
        <div class="reg">
          <input type="submit" name="submit" value="Change Password" class="btn">
        </div>
      </form>
    </div>
  </div>
</body>

</html>