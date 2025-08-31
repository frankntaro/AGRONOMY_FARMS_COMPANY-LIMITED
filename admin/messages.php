<?php
include 'auth_check.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Messages</title>
    <!-- Add Font Awesome for the delete icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .container {
            width: 90%;
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        h2 {
            text-align: center;
            color: #2c5d2c;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        thead th {
            background-color: #38761d;
            color: #fff;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #e0f2b8;
            transition: background-color 0.3s ease;
        }
        a {
            color: #38761d;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }

        /* New CSS for the delete icon */
        .delete-icon {
            color: #ff4d4d; /* A strong red color */
            font-size: 1.2em; /* Make the icon a bit bigger */
            transition: color 0.3s ease;
        }

        .delete-icon:hover {
            color: #cc0000; /* Darker red on hover */
        }
        
        /* New CSS to ensure icon and text are aligned */
        .action-link {
            display: flex;
            align-items: center;
            gap: 5px; /* Adds space between the icon and text */
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<div class="container">
    <h2>Contact Messages</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM messages ORDER BY received_at DESC");
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td>
                            <a href="delete_message.php?id=<?= htmlspecialchars($row['id']) ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class="fas fa-trash-alt delete-icon"></i>
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="7">No messages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>