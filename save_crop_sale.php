<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once 'config.php';

if (!$conn || $conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Database connection failed: {$conn->connect_error}"]);
    exit;
}

$fullName = trim($_POST['fullName'] ?? '');
$phoneNumber = trim($_POST['phone_number'] ?? '');
$region = trim($_POST['region'] ?? '');
$district = trim($_POST['district'] ?? '');
$cropType = trim($_POST['crop_type'] ?? '');
$quantityKg = trim($_POST['quantity_kg'] ?? '');
$expectedPrice = trim($_POST['price'] ?? '');

// Validate required fields
$requiredFields = ['fullName', 'phone_number', 'region', 'district', 'crop_type', 'quantity_kg', 'price'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Please fill in all required fields: $field is missing."]);
        exit;
    }
}

// Validate consent
if (!isset($_POST['consent']) || $_POST['consent'] !== '1') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'You must agree to the terms and conditions.']);
    exit;
}
$consentGiven = 1;

$quantityKg_int = (int)$quantityKg;
$expectedPrice_dec = (float)$expectedPrice;

try {
    $sql = "INSERT INTO applications (full_name, phone_number, region, district, crop_type, quantity_kg, expected_price, consent_given) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => "Database statement preparation failed: {$conn->error}"]);
        exit;
    }

    $stmt->bind_param("sssssids", $fullName, $phoneNumber, $region, $district, $cropType, $quantityKg_int, $expectedPrice_dec, $consentGiven);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => "Error executing statement: {$stmt->error}"]);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error saving application: ' . $e->getMessage()]);
} finally {
    if ($conn) $conn->close();
}
