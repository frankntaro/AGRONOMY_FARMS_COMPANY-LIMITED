<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header('Location: index.php');
    exit;
}

// Get the payment method from the checkout form
$paymentMethod = $_POST['payment_method'] ?? 'Unknown';

// Start a transaction for data integrity
$conn->autocommit(false);

try {
    // Dynamically create placeholders for the IN clause
    $productIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));

    // Prepare a secure query to get product details
    $stmt = $conn->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
    // Dynamically bind parameters
    $types = str_repeat('i', count($productIds));
    $stmt->bind_param($types, ...$productIds);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the query returned any products
    if ($result->num_rows === 0) {
        throw new Exception('No products found in cart.');
    }

    $cartTotal = 0;
    $productDetails = [];
    while ($product = $result->fetch_assoc()) {
        $productDetails[$product['id']] = $product;
        $cartTotal += $product['price'] * $cart[$product['id']];
    }
    $stmt->close();

    // Insert the new order into the 'orders' table
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, status) VALUES (?, ?, ?, 'Paid')");
    $stmt->bind_param("ids", $userId, $cartTotal, $paymentMethod);
    $stmt->execute();
    $orderId = $conn->insert_id;
    $stmt->close();

    // Insert each cart item into the 'order_items' table
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $productId => $quantity) {
        // Ensure product details exist before trying to get the price
        if (!isset($productDetails[$productId])) {
            throw new Exception("Product ID $productId not found.");
        }
        $price = $productDetails[$productId]['price'];
        $stmt->bind_param("iiid", $orderId, $productId, $quantity, $price);
        $stmt->execute();
    }
    $stmt->close();

    // Commit the transaction only if all queries were successful
    $conn->commit();
    
    // Clear the cart session
    unset($_SESSION['cart']);

    // Redirect the user to the order tracking page
    header("Location: track_order.php?order_id=$orderId");
    exit;

} catch (Exception $e) {
    // Rollback the transaction on any error
    $conn->rollback();
    // Log the error and display a user-friendly message
    error_log("Payment processing error: " . $e->getMessage());
    echo "An error occurred during payment processing. Please try again. " . $e->getMessage();
}

// Restore default autocommit behavior
$conn->autocommit(true);
?>