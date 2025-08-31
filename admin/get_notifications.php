<?php
// Include your database connection file and configuration
include './includes/db.php';
require_once 'config.php';

// Set the header to indicate a JSON response
header('Content-Type: application/json');

// Initialize
$new_applications = 0;
$new_orders = 0;
$new_messages = 0;

$applications_status = [];
$orders_status = [];
$messages_status = [];

/**
 * Get count by status
 */
function getCount($conn, $table, $column, $status) {
    $sql = "SELECT COUNT(*) AS count FROM `$table` WHERE `$column` = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("SQL prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'];
}

/**
 * Get counts for all statuses in a table
 */
function getAllStatusCounts($conn, $table, $column) {
    $sql = "SELECT `$column`, COUNT(*) as count FROM `$table` GROUP BY `$column`";
    $result = $conn->query($sql);
    $statusCounts = [];
    while ($row = $result->fetch_assoc()) {
        $statusCounts[$row[$column]] = (int)$row['count'];
    }
    return $statusCounts;
}

try {
    // --- Applications ---
    $new_applications = getCount($conn, 'applications', 'status', 'pending');
    $applications_status = getAllStatusCounts($conn, 'applications', 'status');

    // --- Orders ---
    $new_orders = getCount($conn, 'orders', 'status', 'Paid');
    $orders_status = getAllStatusCounts($conn, 'orders', 'status');

    // --- Messages ---
    $new_messages = getCount($conn, 'messages', 'status', 'unread');
    $messages_status = getAllStatusCounts($conn, 'messages', 'status');

} catch (Exception $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['error' => 'Database error']);
    exit();
}

$total_notifications = $new_applications + $new_orders + $new_messages;

// Final response
$response = [
    'new_applications' => $new_applications,
    'applications_status' => $applications_status,

    'new_orders' => $new_orders,
    'orders_status' => $orders_status,

    'new_messages' => $new_messages,
    'messages_status' => $messages_status,

    'total_notifications' => $total_notifications
];

echo json_encode($response);

$conn->close();
?>
