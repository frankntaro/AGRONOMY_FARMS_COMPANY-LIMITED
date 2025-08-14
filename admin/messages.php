<?php
include 'auth_check.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head><title>Contact Messages</title></head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<div style="margin-left:220px; padding:20px;">
    <h2>Contact Messages</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Message</th><th>Actions</th></tr>
        <?php
        $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><a href="delete_message.php?id=<?= htmlspecialchars($row['id']) ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>