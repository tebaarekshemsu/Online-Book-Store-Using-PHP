<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

$message = [];

// Add Product
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $pieces = $_POST['pieces'];
    $image = $_POST['image'];

    $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'Product Name Already Added';
    } else {
        $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, author, genre, description, price, pieces, image) VALUES('$name', '$author', '$genre', '$description', '$price', '$pieces', '$image')") or die('query failed');

        if ($add_product_query) {
            $message[] = 'Product Added Successfully!';
        } else {
            $message[] = 'Product Could Not Be Added!';
        }
    }
}

// Update Product
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_author = mysqli_real_escape_string($conn, $_POST['update_author']);
    $update_genre = mysqli_real_escape_string($conn, $_POST['update_genre']);
    $update_description = mysqli_real_escape_string($conn, $_POST['update_description']);
    $update_price = $_POST['update_price'];
    $update_pieces = $_POST['update_pieces'];
    $update_image = $_POST['update_image'];

    mysqli_query($conn, "UPDATE `products` SET name = '$update_name', author = '$update_author', genre = '$update_genre', description = '$update_description', price = '$update_price', pieces = '$update_pieces', image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');

    header('location:admin_products.php');
    exit();
}

// Delete Product
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_products.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="add-products">
        <h1 class="titleshop">Shop Products</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Add Product</h3>
            <input type="text" name="name" class="box" placeholder="Enter product name" required>
            <input type="text" name="author" class="box" placeholder="Enter author name" required>
            <input type="text" name="genre" class="box" placeholder="Enter genre" required>
            <input type="text" name="description" class="box" placeholder="Enter description" required>
            <input type="number" min="0" name="price" class="box" placeholder="Enter product price" required>
            <input type="number" min="0" name="pieces" class="box" placeholder="Enter available pieces" required>
            <input type="text" name="image" class="box" placeholder="Enter image link" required>
            <input type="submit" value="Add Product" name="add_product" class="btne">
        </form>

        <h1 style="font-size: 2.5rem;
   text-transform: uppercase;
   color:#0909FF;
   margin-bottom: 1.5rem;
    margin-top: 1.5rem;
    text-align: center;">Upload Products from CSV</h1>
        <form action="import_books.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" accept=".csv" required>
            <input type="submit" name="import" value="Upload CSV" class="btne">
        </form>

        <h3 style="font-size: 2.5rem;
   text-transform: uppercase;
   color:#0909FF;
   margin-bottom: 1.5rem;
   margin-top: 1.5rem;
    text-align: center;">Download CSV Template</h3>
        <h3 style="text-align: center;"><a href="download_template.php" class="btne" style="text-align: center;">Download CSV Template</a></h3>
    </section>

    <section class="show-products">
        <div class="box-container">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <div class="box">
                        <img class="image" src="<?php echo $fetch_products['image']; ?>" alt="">
                        <h1 class="name"><strong>Name:</strong> <?php echo $fetch_products['name']; ?></h1>
                        <h2 class="author"><strong>Author:</strong> <?php echo $fetch_products['author']; ?></h2>
                        <h3 class="genre"><strong>Genre:</strong> <?php echo $fetch_products['genre']; ?></h3>
                        <h4 class="description"><strong>Description:</strong> <?php echo $fetch_products['description']; ?></h4>
                        <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
                        <div class="pieces">Available Pieces: <?php echo $fetch_products['pieces']; ?></div>
                        <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="update-btn">Update</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn"
                            onclick="return confirm('Delete this product?');">Delete</a>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No Products Added Yet!</p>';
            }
            ?>
        </div>
    </section>

    <section class="edit-product-form">
        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                        <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required
                            placeholder="Enter product name">
                        <input type="text" name="update_author" value="<?php echo $fetch_update['author']; ?>" class="box" required
                            placeholder="Enter author name">
                        <input type="text" name="update_genre" value="<?php echo $fetch_update['genre']; ?>" class="box" required
                            placeholder="Enter genre">
                        <input type="text" name="update_description" value="<?php echo $fetch_update['description']; ?>" class="box"
                            required placeholder="Enter description">
                        <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box"
                            required placeholder="Enter product price">
                        <input type="number" name="update_pieces" value="<?php echo $fetch_update['pieces']; ?>" min="0" class="box"
                            required placeholder="Enter available pieces">
                        <input type="text" name="update_image" value="<?php echo $fetch_update['image']; ?>" class="box" required
                            placeholder="Enter image link">
                        <input type="submit" value="Update" name="update_product" class="btne">
                        <input type="reset" value="Cancel" id="close-update" class="close-btn">
                    </form>
                    <?php
                }
            }
        } else {
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
        }
        ?>
    </section>

    <script src="js/admin_script.js"></script>

</body>

</html>