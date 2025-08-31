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
$order_query = $conn->prepare("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
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
<head>
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 220px;
            padding: 40px;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            text-align: center;
            color: #2c5d2c;
            margin-bottom: 25px;
        }

        .order-info {
            background-color: #e8f5e9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 5px solid #38761d;
        }

        .order-info p {
            margin: 5px 0;
            font-size: 1.1em;
        }

        .order-info strong {
            color: #38761d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        thead th {
            background-color: #38761d;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e0f2b8;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <div class="container">
        <h2>Order Details for Order #<?= htmlspecialchars($order_id) ?></h2>
        <div class="order-info">
            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
            <p><strong>Customer:</strong> <?= htmlspecialchars($order['full_name']) ?></p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
        </div>

        <h3>Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $items->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td><?= htmlspecialchars(number_format($item['price'])) ?> TSh</td>
                        <td><?= htmlspecialchars(number_format($item['quantity'] * $item['price'])) ?> TSh</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>