<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fullName = $_POST['fullName'];
    $phoneNumber = $_POST['phoneNumber'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    
    if ($stmt->rowCount() > 0) {
        echo "Username or Email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, full_name, phone_number, password, role) VALUES (:username, :email, :fullName, :phoneNumber, :password, :role)");
        $stmt->execute(['username' => $username, 'email' => $email, 'fullName' => $fullName, 'phoneNumber' => $phoneNumber, 'password' => $password, 'role' => $role]);
        echo "Registration successful!";
    }
}
?>