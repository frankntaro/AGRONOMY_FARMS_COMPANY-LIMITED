<?php
include 'auth_check.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <style>
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
            background-color: white;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<div style="margin-left:220px; padding:20px; text-align: center;">
    <h2>All Orders</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="margin: 0 auto;">
        <tr>
            <th>Order ID</th><th>Customer</th><th>Total</th><th>Date</th><th>Status</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM orders");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['user_id']) ?></td>
            <td><?= htmlspecialchars($row['total_amount']) ?> TSh</td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><a href="order_details.php?id=<?= htmlspecialchars($row['id']) ?>">View</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>