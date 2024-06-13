<?php

function connectToDatabase($servername, $username, $password, $database)
{
    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conn->query($sql) !== TRUE) {
        echo "Error creating database: " . $conn->error;
    }

    // Select database
    $conn->select_db($database);

    return $conn;
}

function createTables($conn)
{
    // Create 'users' table first
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(255),
        password VARCHAR(255),
        user_type VARCHAR(255),
        photo VARCHAR(500)
    )";
    if ($conn->query($sql_users) !== TRUE) {
        echo "Error creating table 'users': " . $conn->error;
    }

    // Create 'products' table
    $sql_products = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        author VARCHAR(255),
        genre VARCHAR(255),
        description TEXT,
        price DECIMAL(10,2),
        pieces INT,
        image VARCHAR(255)
    )";
    if ($conn->query($sql_products) !== TRUE) {
        echo "Error creating table 'products': " . $conn->error;
    }

    // Create 'cart' table
    $sql_cart = "CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        price DECIMAL(10,2),
        quantity INT,
        image VARCHAR(255),
        user_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    if ($conn->query($sql_cart) !== TRUE) {
        echo "Error creating table 'cart': " . $conn->error;
    }

    // Create 'views' table
    $sql_views = "CREATE TABLE IF NOT EXISTS views (
        id INT AUTO_INCREMENT PRIMARY KEY,
        view INT,
        product_id INT,
        user_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    if ($conn->query($sql_views) !== TRUE) {
        echo "Error creating table 'views': " . $conn->error;
    }

    // Create 'message' table
    $sql_message = "CREATE TABLE IF NOT EXISTS message (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(255),
        number VARCHAR(255),
        message TEXT,
        user_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    if ($conn->query($sql_message) !== TRUE) {
        echo "Error creating table 'message': " . $conn->error;
    }

    // Create 'orders' table
    $sql_orders = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        number VARCHAR(255),
        email VARCHAR(255),
        method VARCHAR(255),
        address VARCHAR(255),
        total_products VARCHAR(255),
        total_price DECIMAL(10,2),
        placed_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        payment_status VARCHAR(255),
        latitude FLOAT(10, 6),
        longitude FLOAT(10, 6),
        user_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    if ($conn->query($sql_orders) !== TRUE) {
        echo "Error creating table 'orders': " . $conn->error;
    }

    // Create 'feedbacks' table
    $sql_feedbacks = "CREATE TABLE IF NOT EXISTS feedbacks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        rating INT,
        user_id INT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    if ($conn->query($sql_feedbacks) !== TRUE) {
        echo "Error creating table 'feedbacks': " . $conn->error;
    }

    // Create 'books' table
    $sql_books = "CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        photo VARCHAR(255) NOT NULL,
        pdf VARCHAR(255) NOT NULL
    )";
    if ($conn->query($sql_books) !== TRUE) {
        echo "Error creating table 'books': " . $conn->error;
    }
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "shop_db";

// Connect to database and create tables
$conn = connectToDatabase($servername, $username, $password, $database);
createTables($conn);

?>
