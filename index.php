<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration Form</title>
    <link rel="stylesheet" href="auth.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.js"></script>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-content">
                <!-- Form Type Selector -->
                <div class="toggle-container">
                    <button id="loginToggle" class="toggle-button active">
                        <div class="toggle-button-content">
                            <i data-lucide="log-in"></i>
                            Login
                        </div>
                    </button>
                    <button id="registerToggle" class="toggle-button">
                        <div class="toggle-button-content">
                            <i data-lucide="user-plus"></i>
                            Register
                        </div>
                    </button>
                </div>

                <!-- Login Form -->
                <div id="loginForm" class="form-section active">
                    <h2 class="form-title">Login to Your Account</h2>
                    <form class="form-container" action="login.php" method="POST">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select name="role" required class="form-input">
                                <option value="customer">Customer</option>
                                <option value="technical">Technical</option>
                            </select>
                        </div>

                        <button type="submit" class="submit-button">Login</button>
                    </form>
                </div>

                <!-- Registration Form -->
                <div id="registerForm" class="form-section">
                    <h2 class="form-title">Create Your Account</h2>
                    <form class="form-container" action="register.php" method="POST">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="fullName" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phoneNumber" required class="form-input" pattern="[0-9]{10,13}" placeholder="1234567890">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirmPassword" required class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select name="role" required class="form-input">
                                <option value="customer">Customer</option>
                                <option value="technical">Technical</option>
                            </select>
                        </div>

                        <button type="submit" class="submit-button">Create Account</button>
                    </form>
                </div>

                <div id="alertMessage" class="alert" style="display: none;">
                    <i data-lucide="alert-circle"></i>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
    <script src="auth.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Cek apakah ada pesan error dari login.php
            const errorMessage = '<?php echo isset($_SESSION['error_message']) ? $_SESSION['error_message'] : ''; ?>';
            if (errorMessage) {
                alert(errorMessage);
                // Hapus pesan error dari session
                <?php unset($_SESSION['error_message']); ?>
            }
        });
    </script>
</body>
</html>