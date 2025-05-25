<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$connection = new mysqli('localhost', 'root', '', 'manana_inventory');
if ($connection->connect_error) {
    die('DB connection failed: ' . $connection->connect_error);
}

$name     = trim($_POST['name']     ?? '');
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');
$role     = ($_POST['role'] === 'admin') ? 'admin' : 'staff';

/* --- hash password for security --- */
$hashed = password_hash($password, PASSWORD_DEFAULT);

/* --- insert (handle duplicate email) --- */
$stmt = $connection->prepare(
    "INSERT INTO users (name, email, password, role) VALUES (?,?,?,?)"
);
$stmt->bind_param('ssss', $name, $email, $hashed, $role);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
}

/* duplicate email → back to form with error flag */
header("Location: add_user.php?error=duplicate");
exit();
?>
