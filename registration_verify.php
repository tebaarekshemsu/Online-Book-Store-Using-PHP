<?php

session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $verificationCode = $_POST['verification_code'];
  $name = $_SESSION['name'];
  $email = $_SESSION['email'];
  $password = $_SESSION['password'];
  $user_type = $_SESSION['user_type'];
  $admin_password = $_SESSION['admin_password'];

  // Check if verification code matches
  if ($verificationCode === $_SESSION['verification_code']) {
    // Insert user into database
    $sql = "INSERT INTO users (name, email, password, user_type) VALUES ('$name', '$email', '$password', '$user_type')";
    if ($conn->query($sql) === TRUE) {
      echo "User registered successfully";
      // Redirect user to login page or any other page as needed
      header("Location: login.php");
      session_unset();
      session_destroy();
      exit;
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    echo "Invalid verification code";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/styles.css">

  <style>
      body {
         background-image: url('./images/background_3.jpg'); 
         background-size: cover;
         background-repeat: no-repeat;
         background-attachment: fixed;
      }
   </style>
</head>
<body>
  <div class="wrapper">
    <span class="icon-close"><i class='bx bx-x'></i></span>
    <div class="form-box login">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-box">
        <h2>Verify</h2>
        <div class="input-box">
          <input class="input-text" type="text" name="verification_code" required>
          <label for="input-text">Verification Code</label>
          <i class='bx bx-key'></i>
        </div>
        <div class="reg">
          <input type="submit" name="submit" value="Verify" class="btn">
        </div>
      </form>
    </div>
  </div>
</body>
</html>