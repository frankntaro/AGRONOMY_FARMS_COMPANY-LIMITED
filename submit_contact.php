<?php


// Database credentials
$host = 'localhost';      
$db   = 'AGRONOMY_FARMS';   
$user = 'root';   
$pass = '';   

// Company email address
$companyEmail = 'info@agronomyfarms.co.tz'; 
$fullName = $_POST['fullName'] ?? '';
$email    = $_POST['email'] ?? '';
$phone    = $_POST['phone'] ?? '';
$subject  = $_POST['subject'] ?? '';
$message  = $_POST['message'] ?? '';
$submittedAt = date('Y-m-d H:i:s');

// Validate basic input
if (empty($fullName) || empty($email) || empty($message)) {
    http_response_code(400);
    echo "Please fill in all required fields.";
    exit;
}
try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("
        INSERT INTO contacts (full_name, email, phone, subject, message, submitted_at)
        VALUES (:fullName, :email, :phone, :subject, :message, :submittedAt)
    ");
    $stmt->execute([
        ':fullName' => $fullName,
        ':email' => $email,
        ':phone' => $phone,
        ':subject' => $subject,
        ':message' => $message,
        ':submittedAt' => $submittedAt
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
    exit;
}

// SEND EMAIL TO COMPANY
$companySubject = "New Contact Message from $fullName";
$companyBody = "
You have received a new message from the Agronomy Farms contact form:

Name: $fullName
Email: $email
Phone: $phone
Subject: $subject

Message:
$message
";
$companyHeaders = "From: no-reply@agronomyfarms.co.tz";

//  SEND EMAIL TO USER
$userSubject = "Thank You for Contacting Agronomy Farms";
$userBody = "
Dear $fullName,

Thank you for reaching out to us. We've received your message and will get back to you shortly.

Here’s a copy of your message:

Subject: $subject
Message:
$message

Best regards,
Agronomy Farms Company Limited
";
$userHeaders = "From: no-reply@agronomyfarms.co.tz";

// Send emails that should be configured in the server
@mail($companyEmail, $companySubject, $companyBody, $companyHeaders);
@mail($email, $userSubject, $userBody, $userHeaders);

// 6. RETURN SUCCESS RESPONSE
http_response_code(200);
echo "your message have been sent succussfully";
?>
