<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <!--CSS-->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>

  <?php

  if (isset($_POST['redirect'])) {
    header("Location: login.php");
    exit();
  }

  ?>

  <section class="home" id="home">
    <div class="home-content">
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <!-- <img src="images/homeImg1.jpg" alt="" class="home-img"> -->
            <div class="home-details">
              <div class="home-text">
                <h4 class="homeSubtitle">Discover a World of Books.</h4>
                <h2 class="homeTitle">Explore a Vast Collection <br> of Literature</h2>
              </div>
              <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="submit" value="Explore" name="redirect" class="button">
              </form>
            </div>
          </div>
          <!-- We will add more slides for different book promotions -->
        </div>
        <div class="swiper-button-next swiper-navBtn"></div>
        <div class="swiper-button-prev swiper-navBtn"></div>
        <div class="swiper-pagination"></div>
      </div>
    </div>

  </section>

</body>

</html>