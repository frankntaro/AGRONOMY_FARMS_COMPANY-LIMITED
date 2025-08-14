<?php
// Always return JSON
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$dbname = "AGRONOMY_FARMS";
$username = "root";
$password = "";

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Load DB config
require_once __DIR__ . '/admin/config.php';


// Check DB connection
if (!$conn || $conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Extract & sanitize data
$fullName        = trim($_POST['fullName'] ?? '');
$phoneNumber     = trim($_POST['phone_number'] ?? '');
$region          = trim($_POST['region'] ?? '');
$district        = trim($_POST['district'] ?? '');
$cropType       = trim($_POST['crop_type'] ?? '');
$quantityKg      = trim($_POST['quantity_kg'] ?? '');
$expectedPrice           = trim($_POST['expected_price'] ?? '');
$submissionDate  = $_POST['submission_date'] ?? date('Y-m-d H:i:s');

// Validate required fields
if ($fullName === '' || $phoneNumber === '' || $region === '' || $district === '' || $cropType === '' || $quantityKg === '' || $expectedPrice === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

try {
    // Insert into DB securely
    $sql = "INSERT INTO applications (full_name, phone_number, region, district, crop_type, quantity_kg, expectedPrice, submission_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssssssds", $fullName, $phoneNumber, $region, $district, $cropType, $quantityKg, $expectedPrice, $submissionDate);

    // Execute & return response
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
    $stmt->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error saving application: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
?>
