<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$id = (int)($_GET['id'] ?? 0);
/* don’t let admin delete own account */
if ($id === $_SESSION['user_id'] || $id === 0) {
    header("Location: manage_users.php");
    exit();
}

$cn = new mysqli('localhost','root','','manana_inventory');
if ($cn->connect_error) die($cn->connect_error);

/* HARD delete — remove row */
$cn->query("DELETE FROM users WHERE id = $id");

/* SOFT delete alternative:  UPDATE users SET is_active=0 WHERE id=$id */
header("Location: manage_users.php");
exit();
?>
