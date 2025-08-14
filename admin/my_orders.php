<?php
session_start();
require_once 'config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<div class="container" style="padding-top: 20px;">
    <h2>My Orders</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; text-align:left;">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT id, created_at, total_amount, status FROM orders WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0):
                while ($order = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                    <td>TSh <?= number_format($order['total_amount']) ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><a href="track_order.php?order_id=<?= htmlspecialchars($order['id']) ?>">Track Order</a></td>
                </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="5">You have no previous orders.</td>
                </tr>
            <?php endif;
            $stmt->close();
            ?>
        </tbody>
    </table>
</div>
