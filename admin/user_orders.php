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
<head>
    <title>User Orders</title>
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
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c5d2c;
            margin-bottom: 25px;
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

        .no-orders {
            text-align: center;
            font-style: italic;
            color: #666;
            padding: 20px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container">
        <h2>Orders for <?= htmlspecialchars($user['full_name']) ?></h2>
        <?php
        $orders_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $orders_stmt->bind_param("i", $user_id);
        $orders_stmt->execute();
        $orders_res = $orders_stmt->get_result();

        if ($orders_res->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders_res->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['total_amount']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-orders">No orders found for this user.</p>
        <?php endif;
        $orders_stmt->close(); ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>