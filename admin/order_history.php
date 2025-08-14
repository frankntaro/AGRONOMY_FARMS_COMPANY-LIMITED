<?php
session_start();
include 'config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Use a prepared statement to safely query the database
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id); // "i" for integer
$stmt->execute();
$res = $stmt->get_result();
?>

<div class="container">
    <h2>My Orders</h2>

    <?php while ($order = $res->fetch_assoc()): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <strong>Order ID:</strong> <?= htmlspecialchars($order['id']) ?><br>
            <strong>Total:</strong> TSh <?= htmlspecialchars(number_format($order['total_amount'], 2)) ?><br>
            <strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?><br>
            <a href="invoice.php?order_id=<?= htmlspecialchars($order['id']) ?>">View Invoice</a> |
            <a href="track_order.php?order_id=<?= htmlspecialchars($order['id']) ?>">Track Order</a>
        </div>
    <?php endwhile; ?>
</div>

<?php 
$stmt->close(); // It's good practice to close the statement
include 'includes/footer.php'; 
?>