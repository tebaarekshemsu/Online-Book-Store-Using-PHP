<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['import'])) {
    $file = $_FILES['file']['tmp_name'];
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        // Skip the first row (header)
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $name = mysqli_real_escape_string($conn, $data[0]);
            $author = mysqli_real_escape_string($conn, $data[1]);
            $genre = mysqli_real_escape_string($conn, $data[2]);
            $description = mysqli_real_escape_string($conn, $data[3]);
            $price = $data[4];
            $pieces = $data[5];
            $image = mysqli_real_escape_string($conn, $data[6]);

            $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

            if (mysqli_num_rows($select_product_name) > 0) {
                $message[] = "Product $name Already Added";
            } else {
                $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, author, genre, description, price, pieces, image) VALUES('$name', '$author', '$genre', '$description', '$price', '$pieces', '$image')") or die('query failed');

                if ($add_product_query) {
                    $message[] = "Product $name Added Successfully!";
                } else {
                    $message[] = "Product $name Could Not Be Added!";
                }
            }
        }

        fclose($handle);
        header('location:admin_products.php');
    }
}
