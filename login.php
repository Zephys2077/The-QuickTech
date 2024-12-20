<?php
include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND role = :role");
    $stmt->execute(['username' => $username, 'role' => $role]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'customer') {
            header("Location: homepage.php");  // Redirect to homepage for customer
        } elseif ($user['role'] == 'technical') {
            header("Location: homepage_teknisi.php");  // Redirect to homepage for technician
        }
        exit();
    } else {
        // Login failed, show error message
        $_SESSION['error_message'] = "Invalid credentials or role.";
        header("Location: index.php");
        exit();
    }
}
?>
