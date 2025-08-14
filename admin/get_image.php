<?php
include 'includes/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($imageData);

if ($stmt->fetch()) {
    header("Content-Type: image/jpeg");
    echo $imageData;
} else {
    http_response_code(404);
    echo "Image not found.";
}
?>
