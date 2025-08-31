<?php
// Start the session at the very beginning
session_start();

// Include the database connection file first
include 'db.php';

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get input data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute a safe, parameterized SQL query
    $stmt = $conn->prepare("SELECT id, full_name, password, user_type FROM users WHERE email = ? AND user_type = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify if a user was found and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables upon successful login
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['full_name'];
        
        // Redirect to the dashboard page
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Handle failed login attempt
        $error = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your CSS is correct and does not need changes */
        body {
            font-family: Arial, sans-serif;
            background-color: #F0F5E6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #2F4F2F;
            margin-bottom: 20px;
        }
        p {
            color: #B22222;
            font-weight: bold;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            align-self: flex-start;
            font-weight: bold;
            color: #556B2F;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #C0D6BA;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button[type="submit"] {
            background-color: #556B2F;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #2F4F2F;
        }
        .forgot-password-link {
            display: block;
            margin-top: 15px;
            color: #556B2F;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-password-link:hover {
            text-decoration: underline;
        }

        /* --- New Styles for Show/Hide Password --- */
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required>
                <span class="toggle-password">
                    <i class="fa-solid fa-eye" id="toggleIcon"></i>
                </span>
            </div>

            <button type="submit">Login</button>
        </form>
        <a href="forgot_password.php" class="forgot-password-link">Forgot Password?</a>
    </div>

    <script>
        const togglePassword = document.querySelector('#toggleIcon');
        const password = document.querySelector('#password');
    
        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>