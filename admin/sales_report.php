<?php
include 'auth_check.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        .container {
            margin-left: 220px;
            padding: 20px;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="container">
    <h2>Sales Report</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Order ID</th><th>Customer</th><th>Total Amount</th><th>Status</th><th>Date</th>
        </tr>
        <?php
        $result = $conn->query("
            SELECT o.id, u.full_name AS customer, o.total_amount, o.status, o.created_at
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['customer']) ?></td>
                <td><?= htmlspecialchars(number_format($row['total_amount'])) ?> TSh</td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>