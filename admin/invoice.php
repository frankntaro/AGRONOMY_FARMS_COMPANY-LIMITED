<?php
session_start();
include 'config.php';

if (!isset($_GET['order_id'])) {
    echo "Order ID missing!";
    exit;
}

// Sanitize and validate the order_id before use
$order_id = intval($_GET['order_id']);
if ($order_id <= 0) {
    echo "Invalid Order ID!";
    exit;
}

// Use a prepared statement for the first query
$stmt_order = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();
$order = $result_order->fetch_assoc();
$stmt_order->close();

// Use a prepared statement for the second query
$stmt_items = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $order_id ?></title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
    </style>
</head>
<body>
    <h2>Invoice - Order #<?= $order_id ?></h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['region']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?></p>

    <h3>Order Details:</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th><th>Qty</th><th>Price</th><th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $grandTotal = 0;
        // Use an array to store product names to avoid repeated queries
        $productNames = [];
        // Loop through items and fetch product names securely
        while ($item = $items->fetch_assoc()):
            $product_id = $item['product_id'];

            // Use a prepared statement to fetch product name
            $stmt_prod_name = $conn->prepare("SELECT name FROM products WHERE id = ?");
            $stmt_prod_name->bind_param("i", $product_id);
            $stmt_prod_name->execute();
            $res_prod_name = $stmt_prod_name->get_result();
            $prodName = $res_prod_name->fetch_assoc()['name'];
            $stmt_prod_name->close();

            $subtotal = $item['price'] * $item['quantity'];
            $grandTotal += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($prodName) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td><?= number_format($subtotal, 2) ?></td>
            </tr>
        <?php endwhile; ?>
            <tr>
                <td colspan="3"><strong>Grand Total</strong></td>
                <td><strong>TSh <?= number_format($grandTotal, 2) ?></strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>