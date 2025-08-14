<?php
include 'auth_check.php';

include __DIR__ . '/includes/db.php';

// Sanitize and validate input
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id <= 0) {
    // Handle invalid ID, redirect or show error
    header("Location: orders.php");
    exit();
}

// Fetch order details securely
$order_query = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order = $order_result->fetch_assoc();
$order_query->close();

if (!$order) {
    // Handle case where order is not found
    header("Location: orders.php");
    exit();
}

// Fetch order items securely
$items_query = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$items_query->bind_param("i", $order_id);
$items_query->execute();
$items = $items_query->get_result();
$items_query->close();

?>
<!DOCTYPE html>
<html>
<head><title>Order Details</title></head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<div style="margin-left:220px; padding:20px;">
    <h2>Order #<?= htmlspecialchars($order_id) ?></h2>
    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
    <p><strong>Customer:</strong> <?= htmlspecialchars($order['user_id']) ?></p>
    

    <h3>Items</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>
        <?php while ($item = $items->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['quantity']) ?></td>
            <td><?= htmlspecialchars($item['price']) ?></td>
            <td><?= htmlspecialchars($item['quantity'] * $item['price']) ?> TSh</td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>