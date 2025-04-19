<?php
session_start();
require_once '../../config/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Query to find the user by email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: ../../public/dashboard.php");
        exit();
    }
}

header("Location: ../../public/login.php?error=Invalid credentials");
exit();
