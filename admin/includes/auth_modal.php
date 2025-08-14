<!-- Auth Modal -->
  <link rel="stylesheet" href="assets/css/style.css">
<div class="auth-modal" id="auth-modal">
    <div class="auth-container">
        <div class="auth-tabs">
            <div class="auth-tab active" data-tab="login">Login</div>
            <div class="auth-tab" data-tab="register">Register</div>
        </div>
        <div class="auth-content">
            <!-- Login Form -->
            <div class="auth-form" id="login-form">
                <form method="POST">
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                    <?php if(isset($login_error)): ?>
                        <div class="error-message"><?= $login_error ?></div>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Register Form -->
            <div class="auth-form" id="register-form" style="display: none;">
                <form method="POST">
                    <div class="form-group">
                        <label for="register-name">Full Name</label>
                        <input type="text" id="register-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="register-email">Email</label>
                        <input type="email" id="register-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Password</label>
                        <input type="password" id="register-password" name="password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                    <?php if(isset($register_error)): ?>
                        <div class="error-message"><?= $register_error ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <button class="close-modal">&times;</button>
</div>