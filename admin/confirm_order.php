<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $region = $_POST['region'];
    $total_amount = 0;

    // Fetch product prices securely
    $cart_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $stmt_prices = $conn->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
    $types = str_repeat('i', count($cart_ids));
    $stmt_prices->bind_param($types, ...$cart_ids);
    $stmt_prices->execute();
    $result_prices = $stmt_prices->get_result();

    $prices = [];
    while ($row = $result_prices->fetch_assoc()) {
        $prices[$row['id']] = $row['price'];
        $total_amount += $row['price'] * $_SESSION['cart'][$row['id']];
    }
    $stmt_prices->close();

    // Insert into orders (This part was already secure)
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, fullname, phone, address, region, total_amount, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt_order->bind_param("issssd", $user_id, $fullname, $phone, $address, $region, $total_amount);
    $stmt_order->execute();
    $order_id = $stmt_order->insert_id;
    $stmt_order->close();

    // Insert order items securely
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $id => $qty) {
        $price = $prices[$id];
        $stmt_items->bind_param("iiid", $order_id, $id, $qty, $price);
        $stmt_items->execute();
    }
    $stmt_items->close();

    // Clear cart
    unset($_SESSION['cart']);

    header("Location: order_success.php?order_id=" . $order_id);
    exit;
}
?>