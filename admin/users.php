<?php
include 'auth_check.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 700px;
            margin: 40px auto;
            padding: 40px;
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
            white-space: nowrap; /* Prevents actions from wrapping to a new line */
        }

        .action-link {
            text-decoration: none;
            color: #38761d;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 15px;
            transition: color 0.3s ease;
        }

        .action-link:hover {
            color: #2c5d2c;
        }

        .view-icon {
            color: #2c5d2c;
        }

        .delete-icon {
            color: #ff4d4d;
        }

        .view-icon, .delete-icon {
            font-size: 1.2em;
            transition: color 0.3s ease;
        }

        .action-link:hover .view-icon {
            color: #1e451e;
        }

        .action-link:hover .delete-icon {
            color: #cc0000;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="container">
    <h2>All Registered Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM users");
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['user_type']) ?></td>
                        <td class="actions-cell">
                            <a href="user_orders.php?id=<?= htmlspecialchars($row['id']) ?>" class="action-link">
                                <i class="fas fa-eye view-icon"></i>
                                View
                            </a>
                            <a href="delete_user.php?id=<?= htmlspecialchars($row['id']) ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash-alt delete-icon"></i>
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>