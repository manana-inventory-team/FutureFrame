<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$connection = new mysqli('localhost', 'root', '', 'manana_inventory');
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$item_name = $_POST['item_name'];
$quantity = $_POST['quantity'];
$unit = $_POST['unit'];
$category = $_POST['category'];
$low_stock_threshold = $_POST['low_stock_threshold'];
$updated_by = $_SESSION['user_id'];

$sql = "INSERT INTO inventory (item_name, quantity, unit, category, low_stock_threshold, updated_by)
        VALUES ('$item_name', $quantity, '$unit', '$category', $low_stock_threshold, $updated_by)";

if ($connection->query($sql)) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error: " . $connection->error;
}

$connection->close();
?>
