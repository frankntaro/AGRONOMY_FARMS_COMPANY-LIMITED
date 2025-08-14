<?php
include 'auth_check.php';
include 'includes/db.php';

$id = $_GET['id'];

// First, delete related records from the order_items table
$stmt_items = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
$stmt_items->bind_param("i", $id);
$stmt_items->execute();
$stmt_items->close();

// Now, delete the product from the products table
$stmt_product = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt_product->bind_param("i", $id);
$stmt_product->execute();
$stmt_product->close();

header("Location: products.php");
exit();
?>
