<?php
// Start a session to store messages
session_start();

include 'auth_check.php';
include '../includes/db.php';

// Get the user ID from the URL and sanitize it
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if the ID is valid
if ($id <= 0) {
    $_SESSION['message'] = "Error: Invalid user ID provided.";
    header("Location: users.php");
    exit();
}

// Prepare the DELETE statement to prevent SQL injection
if ($stmt = $conn->prepare("DELETE FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Check if a row was actually deleted
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "User deleted successfully!";
        } else {
            $_SESSION['message'] = "Error: User not found or could not be deleted.";
        }
    } else {
        // Handle execution error
        $_SESSION['message'] = "Error deleting user: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle prepare error
    $_SESSION['message'] = "Database error: " . $conn->error;
}

// Redirect back to the users page
header("Location: users.php");
exit();
?>