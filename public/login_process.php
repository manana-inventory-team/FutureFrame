<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

/* ---------- DB CONNECTION ---------- */
$connection = new mysqli('localhost', 'root', '', 'manana_inventory');
if ($connection->connect_error) {
    die('DB connection failed: ' . $connection->connect_error);
}

/* ---------- COLLECT FORM INPUT ---------- */
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

/* ---------- LOOK UP USER ---------- */
$stmt = $connection->prepare(
    "SELECT * FROM users WHERE email = ? LIMIT 1"
);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

/* ---------- VALIDATE PASSWORD ---------- */
if ($result && $result->num_rows === 1) {
    $user   = $result->fetch_assoc();
    $stored = $user['password'];

    $ok = (strlen($stored) > 20)           // hashed if length >20
        ? password_verify($password, $stored)
        : ($password === $stored);         // plain-text match

    if ($ok) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'] ?? 'staff';

        header('Location: dashboard.php');
        exit();
    }
}

/* ---------- ANY FAILURE ---------- */
header('Location: index.php?error=1');
exit();
