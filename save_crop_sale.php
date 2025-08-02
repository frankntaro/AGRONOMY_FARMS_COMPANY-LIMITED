<?php
header('Content-Type: application/json');

// Database credentials - replace with your own
$host = "localhost";
$dbname = "AGRONOMY_FARMS";
$username = "root";
$password = "";

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received."]);
    exit;
}

// Validate required fields
$requiredFields = ['fullName', 'phone', 'region', 'district', 'cropType', 'quantity', 'price', 'consent'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || $data[$field] === "") {
        echo json_encode(["success" => false, "message" => "Missing field: $field"]);
        exit;
    }
}

// Sanitize and assign variables
$fullName = htmlspecialchars(strip_tags($data['fullName']));
$phone = htmlspecialchars(strip_tags($data['phone']));
$region = htmlspecialchars(strip_tags($data['region']));
$district = htmlspecialchars(strip_tags($data['district']));
$cropType = htmlspecialchars(strip_tags($data['cropType']));
$quantity = (int)$data['quantity'];
$price = (float)$data['price'];
$consent = (int)$data['consent'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO crop_sales (full_name, phone, region, district, crop_type, quantity, price, consent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fullName, $phone, $region, $district, $cropType, $quantity, $price, $consent]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
