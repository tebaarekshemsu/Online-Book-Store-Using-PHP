<?php
include 'config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $photo = $_FILES['photo']['name'];
    $pdf = $_FILES['pdf']['name'];

    // Directory where files will be uploaded
    $target_dir = "uploads/";

    // File paths
    $target_file_photo = $target_dir . basename($photo);
    $target_file_pdf = $target_dir . basename($pdf);

    // Upload files
    if (
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file_photo) &&
        move_uploaded_file($_FILES['pdf']['tmp_name'], $target_file_pdf)
    ) {

        // Insert data into database
        $sql = "INSERT INTO books (title, photo, pdf) VALUES ('$title', '$target_file_photo', '$target_file_pdf')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your files.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Upload Book</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
       body {
           font-family: Arial, sans-serif;
           background-color: #f0f2f5;
           margin: 0;
           padding: 0;
       }

       .content {
           padding-top: 100px; /* Ensure content starts below the header */
           max-width: 800px;
           margin: auto;
           background-color: #fff;
           padding: 20px;
           box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
           border-radius: 8px;
           margin-top: 20px;
       }

       h2 {
           text-align: center;
           color: #333;
       }

       form {
           display: flex;
           flex-direction: column;
           gap: 10px;
       }

       form input[type="text"], 
       form input[type="file"] {
           padding: 10px;
           border: 1px solid #ccc;
           border-radius: 4px;
       }

       form input[type="submit"] {
           background-color: #007bff;
           border: none;
           color: white;
           padding: 10px 15px;
           border-radius: 5px;
           cursor: pointer;
           transition: background-color 0.2s;
       }

       form input[type="submit"]:hover {
           background-color: #0056b3;
       }
   </style>
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <div class="content">
        <h2>Upload PDF Book</h2>
        <form action="admin_upload.php" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>
            
            <label for="pdf">PDF:</label>
            <input type="file" id="pdf" name="pdf" accept="application/pdf" required>
            
            <input type="submit" value="Upload" class="btne">
        </form>
        <script src="js/admin_script.js"></script>

    </div>
</body>

</html>
