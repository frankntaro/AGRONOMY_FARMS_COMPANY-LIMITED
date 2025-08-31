<?php
include 'auth_check.php';
include 'includes/db.php';

// Check if a valid ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        // Deletion successful, redirect back to the orders page
        header("Location: orders.php?deleted=true");
    } else {
        // Deletion failed, show an error message
        die("Error deleting record: " . $conn->error);
    }

    $stmt->close();
} else {
    // No ID provided, or it was invalid
    header("Location: orders.php?deleted=false");
}

$conn->close();
exit();
?>