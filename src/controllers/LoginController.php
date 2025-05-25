<?php
session_start();
require_once '../../config/db.php';

$username = $_POST['name'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: ../../public/dashboard.php");
        exit();
    }
}

header("Location: ../../public/login.php?error=Invalid credentials");
exit();
