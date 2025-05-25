<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only allow logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// DB connection
$cn = new mysqli('localhost', 'root', '', 'manana_inventory');
if ($cn->connect_error) die("Connection failed: " . $cn->connect_error);

// Get form data
$id                 = $_POST['id'] ?? null;
$item_name          = $_POST['item_name'] ?? '';
$quantity           = $_POST['quantity'] ?? 0;
$unit               = $_POST['unit'] ?? '';
$category           = $_POST['category'] ?? '';
$low_stock_threshold = $_POST['low_stock_threshold'] ?? 0;

if (!$id) die("Missing item ID.");

// Sanitize inputs
$item_name = $cn->real_escape_string($item_name);
$unit = $cn->real_escape_string($unit);
$category = $cn->real_escape_string($category);

// Prepare SQL
$sql = "UPDATE inventory SET 
            item_name = '$item_name',
            quantity = $quantity,
            unit = '$unit',
            category = '$category',
            low_stock_threshold = $low_stock_threshold,
            updated_by = {$_SESSION['user_id']},
            updated_at = NOW()
        WHERE id = $id";

if ($cn->query($sql)) {
    header("Location: dashboard.php");
    exit();
} else {
    die("Update failed: " . $cn->error);
}
