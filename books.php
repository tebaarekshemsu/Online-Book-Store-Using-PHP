<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

// Fetch books from database
$sql = "SELECT id, title, photo, pdf FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Collections</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
            padding-top: 120px; /* Ensure content starts below the header */
            max-width: 800px;
            margin: auto;
        }

        .book-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .book-item {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            width: 200px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

       
        

        .book-item img {
            width: 100px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .book-item h2 {
            font-size: 18px;
            margin: 10px 0;
        }

        .book-item button {
            background-color: orange;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .book-item button a {
            color: white;
            text-decoration: none;
        }

        .book-item button:hover {
            background-color: orange;
        }

        .centre {
            text-align: center;
            padding-bottom: 20px;
        }
        .book-list{
             box-shadow: 0 0 10px 5px silver;
             border-radius: 2px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="content" style="min-height:50vh;">
        <h2 class="centre">Book List</h2>
        <div class="book-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='book-item'>";
                    echo "<h2>" . $row['title'] . "</h2>";
                    echo "<img src='" . $row['photo'] . "' alt='" . $row['title'] . "'>";
                    echo "<button><a href='" . $row['pdf'] . "' download>Download PDF</a></button>";
                    echo "</div>";
                }
            } else {
                echo "<h2>No books available</h2>";
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>

<?php
$conn->close();
?>
