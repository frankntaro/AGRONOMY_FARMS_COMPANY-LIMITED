<?php
include 'auth_check.php';
include __DIR__ . '/includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input

    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: messages.php?msg=deleted");
    exit();
} else {
    echo "No ID provided!";
}
?>