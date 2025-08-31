<?php
session_start();
// db.php is in the same folder
include 'db.php';

$token = $_GET['token'] ?? null;
$error = '';
$success = '';
$valid_token = false;
$user_id = null;

if ($token) {
    $stmt = $conn->prepare("SELECT id, reset_token_expiry FROM users WHERE reset_token = ? AND user_type = 'admin'");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && strtotime($user['reset_token_expiry']) > time()) {
        $valid_token = true;
        $user_id = $user['id'];
    } else {
        $error = "Invalid or expired password reset link.";
    }
} else {
    $error = "Password reset link is missing.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt_update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $stmt_update->bind_param("si", $hashed_password, $user_id);
        
        if ($stmt_update->execute()) {
            $success = "Your password has been reset successfully. You can now log in.";
        } else {
            $error = "An error occurred while resetting your password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        /* ... (Your CSS) */
        .login-link {
            display: block;
            margin-top: 15px;
            color: #556B2F;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if ($success): ?>
            <p class="message-success"><?php echo $success; ?></p>
            <a href="login.php" class="login-link">Go to Login</a>
        <?php elseif ($valid_token): ?>
            <p>Enter your new password below.</p>
            <?php if ($error) echo "<p class='message-error'>$error</p>"; ?>
            <form method="POST">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="submit">Reset Password</button>
            </form>
        <?php else: ?>
            <p class="message-error"><?php echo $error; ?></p>
            <a href="login.php" class="login-link">Back to Login</a>
        <?php endif; ?>
    </div>
</body>
</html>