<?php
include 'auth_check.php';
include './includes/db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request'];

if ($conn->connect_error) {
    $response['message'] = "Database connection failed: {$conn->connect_error}";
    echo json_encode($response);
    exit();
}

// Get notification type from request (GET or POST)
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

try {
    if ($type === 'messages') {
        // Clear only messages (unread → read)
        $sql = "UPDATE `messages` SET `status` = 'read' WHERE `status` = 'unread'";
        $conn->query($sql);
        $response = ['success' => true, 'message' => 'Message notifications cleared.'];

    } elseif ($type === 'applications') {
        // Clear only applications (pending → reviewed)
        $sql = "UPDATE `applications` SET `status` = 'reviewed' WHERE `status` = 'pending'";
        $conn->query($sql);
        $response = ['success' => true, 'message' => 'Application notifications cleared.'];

    } elseif ($type === 'orders') {
        // Clear only orders (Paid → seen)
        $sql = "UPDATE `orders` SET `status` = 'seen' WHERE `status` = 'Paid'";
        $conn->query($sql);
        $response = ['success' => true, 'message' => 'Order notifications cleared.'];

    } else {
        $response['message'] = 'Unknown notification type.';
    }

} catch (Exception $e) {
    error_log('Error clearing notifications: ' . $e->getMessage());
    $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
}

$conn->close();

echo json_encode($response);
?>
