<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];

    // Validate fields
    if (empty($full_name) || empty($email) || empty($password) || empty($user_type)) {
        die("Please fill in all fields.");
    }

    // Check passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Save to database
    $sql = "INSERT INTO users (full_name, email, password, user_type) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$full_name, $email, $hashed_password, $user_type]);

    header("Location: ../frontend/pages/login.html");
    exit();
}
?>