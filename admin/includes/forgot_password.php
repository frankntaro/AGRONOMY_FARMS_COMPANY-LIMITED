<?php
session_start();
include 'db.php';

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Correct the path: go up two levels to get to the main AGRO directory
require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND user_type = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt_update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $token, $expiry, $user['id']);
        $stmt_update->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'mail.yourdomain.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'admin@agro.com';
            $mail->Password   = 'your_email_password';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('admin@agro.com', 'Your Farm App Admin');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/admin/includes/reset_password.php?token=" . $token;
            $mail->Body    = "Click this link to reset your password: <a href='{$reset_link}'>{$reset_link}</a>";

            $mail->send();
            $message = "A password reset link has been sent to your email address.";
        } catch (Exception $e) {
            $error = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "No admin account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F0F5E6; /* Light green-yellow, like a field */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 380px;
            box-sizing: border-box;
        }
        h2 {
            color: #2F4F2F; /* Dark green, like leaves */
            margin-bottom: 25px;
            font-size: 1.8em;
        }
        .message-success {
            color: #006400; /* Dark Green */
            font-weight: bold;
            background-color: #e6f7e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #006400;
        }
        .message-error {
            color: #B22222; /* Red */
            font-weight: bold;
            background-color: #ffe6e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #B22222;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        label {
            align-self: flex-start;
            font-weight: bold;
            color: #556B2F; /* Olive green */
            font-size: 1em;
        }
        input[type="email"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #C0D6BA;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type="email"]:focus {
            border-color: #556B2F;
            box-shadow: 0 0 5px rgba(85, 107, 47, 0.5);
            outline: none;
        }
        button[type="submit"] {
            background-color: #556B2F; /* Olive green */
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button[type="submit"]:hover {
            background-color: #2F4F2F; /* Dark green */
            transform: translateY(-2px);
        }
        .back-link {
            display: block;
            margin-top: 25px;
            color: #556B2F;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #2F4F2F;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <?php if (isset($message)) { ?>
            <p class="message-success"> <?php echo $message; ?></p>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <p class="message-error"> <?php echo $error; ?></p>
        <?php } ?>
        <form method="POST">
            <label for="email">Enter your admin email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <a href="login1.php" class="back-link">Back to Login</a>
    </div>
</body>
</html>