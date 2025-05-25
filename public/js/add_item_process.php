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

// Collect form data
$item_name = $_POST['item_name'];
$quantity = $_POST['quantity'];
$unit = $_POST['unit'];
$category = $_POST['category'];
$updated_by = $_SESSION['user_id'];

// Insert into inventory
$sql = "INSERT INTO inventory (item_name, quantity, unit, category, updated_by)
        VALUES ('$item_name', $quantity, '$unit', '$category', $updated_by)";

if ($connection->query($sql) === TRUE) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $connection->error;
}

$connection->close();
?>
