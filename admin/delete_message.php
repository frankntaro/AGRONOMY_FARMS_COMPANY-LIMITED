<?php
include 'auth_check.php';
include '../includes/db.php';
$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: messages.php");
exit();
?>