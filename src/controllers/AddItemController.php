<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../../public/login.php");
    exit();
}

$item_name = $_POST['item_name'];
$quantity = $_POST['quantity'];
$unit = $_POST['unit'];
$category = $_POST['category'];
$expiry_date = $_POST['expiry_date'] ?: null;
$updated_by = $_SESSION['user']['id'];

// Insert into inventory
$stmt = $conn->prepare("INSERT INTO inventory (item_name, quantity, unit, category, expiry_date, updated_by) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdsssi", $item_name, $quantity, $unit, $category, $expiry_date, $updated_by);
$stmt->execute();

// Log the action
$stmt_log = $conn->prepare("INSERT INTO inventory_logs (item_name, change_type, quantity_change, updated_by) VALUES (?, 'added', ?, ?)");
$stmt_log->bind_param("sdi", $item_name, $quantity, $updated_by);
$stmt_log->execute();

header("Location: ../../public/dashboard.php?msg=Item added successfully");
exit();
