<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Inventory Item</title>
</head>
<body>
    <h2>Add Inventory Item</h2>
    <form method="POST" action="../src/controllers/AddItemController.php">
        <label>Item Name:</label><br>
        <input type="text" name="item_name" required><br><br>

        <label>Quantity:</label><br>
        <input type="number" step="0.01" name="quantity" required><br><br>

        <label>Unit:</label><br>
        <input type="text" name="unit" required><br><br>

        <label>Category:</label><br>
        <input type="text" name="category"><br><br>

        <label>Expiry Date:</label><br>
        <input type="date" name="expiry_date"><br><br>

        <button type="submit">Add Item</button>
    </form>

    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
