<?php
// Check if a session is not already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: includes/login1.php");
    exit();
}
?>