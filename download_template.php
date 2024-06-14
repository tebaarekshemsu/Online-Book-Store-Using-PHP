<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="books_template.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['name', 'author', 'genre', 'description', 'price', 'pieces', 'image']);
fclose($output);
exit();
