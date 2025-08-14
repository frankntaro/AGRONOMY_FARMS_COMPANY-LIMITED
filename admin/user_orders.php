<?php
include 'auth_check.php';
include 'includes/db.php';

// Validate and sanitize the user ID from the URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($user_id <= 0) {
    echo "Invalid user ID!";
    exit;
}

// Use a prepared statement to fetch user data securely
$user_stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_res = $user_stmt->get_result();
$user = $user_res->fetch_assoc();
$user_stmt->close();

if (!$user) {
    echo "User not found!";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>User Orders</title></head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div style="margin-left:220px; padding:20px;">
    <h2>Orders for <?= htmlspecialchars($user['full_name']) ?></h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Order ID</th><th>Total</th><th>Status</th><th>Date</th>
        </tr>
        <?php
        // Use a prepared statement to fetch orders securely
        $orders_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
        $orders_stmt->bind_param("i", $user_id);
        $orders_stmt->execute();
        $orders_res = $orders_stmt->get_result();

        while ($row = $orders_res->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['total_amount']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
        <?php endwhile;
        $orders_stmt->close(); ?>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>