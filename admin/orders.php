<?php
include 'auth_check.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            max-width: 1000px;
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

        .actions-cell {
            white-space: nowrap;
        }
        
        .action-link {
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 15px;
        }

        .view-link {
            color: #38761d;
        }

        .view-link:hover {
            color: #2c5d2c;
        }

        .delete-link {
            color: #ff4d4d;
        }

        .delete-link:hover {
            color: #cc0000;
        }
        
        .view-icon, .delete-icon {
            font-size: 1.2em;
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
        <h2>All Orders</h2>
        <?php
        $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
        if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer ID</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['user_id']) ?></td>
                            <td><?= htmlspecialchars($row['total_amount']) ?> TSh</td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td class="actions-cell">
                                <a href="order_details.php?id=<?= htmlspecialchars($row['id']) ?>" class="action-link view-link">
                                    <i class="fas fa-eye view-icon"></i>
                                    View
                                </a>
                                <a href="delete_order.php?id=<?= htmlspecialchars($row['id']) ?>" class="action-link delete-link" onclick="return confirm('Are you sure you want to delete this order?')">
                                    <i class="fas fa-trash-alt delete-icon"></i>
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-orders">No orders found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>