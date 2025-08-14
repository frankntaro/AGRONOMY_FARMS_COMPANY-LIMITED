<?php
include 'auth_check.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Business Trends</title>
    <style>
      
        
        /* The container for the main content */
        .container {
            margin-left: 220px; /* Adjust this to match your sidebar width */
            padding: 20px;
            text-align: center; /* Centers all inline content like headings */
        }
        
        /* The tables */
        table {
            width: 80%; /* Sets a width for better readability */
            margin: 20px auto; /* Centers the block-level table horizontally */
            border-collapse: collapse;
        }

        /* Table cells */
        th, td {
            padding: 10px;
            text-align: left;
        }

        /* Table headers */
        th {
            background-color: white;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="container">
    <h2>Business Trends</h2>

    <h3>Top Categories by Product Count</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>Category</th><th>Number of Products</th></tr>
        <?php
        $result = $conn->query("
            SELECT category, COUNT(*) AS count
            FROM products
            GROUP BY category
            ORDER BY count DESC
        ");
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['count']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3 style="margin-top:30px;">Orders per Day (last 7 days)</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>Date</th><th>Orders</th></tr>
        <?php
        $result = $conn->query("
            SELECT DATE(created_at) as day, COUNT(*) as total
            FROM orders
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY day
            ORDER BY day DESC
        ");
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['day']) ?></td>
                <td><?= htmlspecialchars($row['total']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>