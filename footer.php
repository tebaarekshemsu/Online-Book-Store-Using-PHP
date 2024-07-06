<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body {
            background-color: whitesmoke;
            color: black; /* Set all text to black */
        }
        a {
            color: black; /* Set all links to black */
        }
        .footer {
            background-color: rgba(0, 0, 0, 0.1);
            color: black;
        }
        .container {
            color: black;
        }
        .footer-links{
            color: black;
        }
        .footer-links li a{
            color:  var(--text-color-light);
        }
    </style>
</head>
<body>
    <footer class="section footer">
        <div class="footer-container container">
            <div class="footer-content">
                <a href="#" class="logo-content flex">
                    <i class='bx bx-book logo-icon'></i>
                    <span class="logo-text">Bookstore.</span>
                </a>
                <p class="content-description">Connecting readers with captivating stories. Find your next favorite book with us.</p>
                <div class="footer-location flex">
                    <i class='bx bx-map map-icon'></i>
                    <div class="location-text">
                        Addis Ababa, Ethiopia
                    </div>
                </div>
            </div>
            <div class="footer-linkContent" style="color: black;">
                <ul class="footer-links">
                    <h4 class="footerLinks-title">Facility</h4>
                    <li><a href="#" class="footer-link">Reading Room</a></li>
                    <li><a href="#" class="footer-link">Book Club</a></li>
                    <li><a href="#" class="footer-link">Literary Events</a></li>
                    <li><a href="#" class="footer-link">Online Ordering</a></li>
                    <li><a href="#" class="footer-link">Author Talks</a></li>
                </ul>
                <ul class="footer-links">
                    <h4 class="footerLinks-title">Genres</h4>
                    <li><a href="#" class="footer-link">Fiction</a></li>
                    <li><a href="#" class="footer-link">Non-Fiction</a></li>
                    <!-- more genre links as needed -->
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
