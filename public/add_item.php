<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-primary text-center">➕ Add New Inventory Item</h2>

    <form action="add_item_process.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" step="0.01" name="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" name="unit" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                <option value="">-- Select Category --</option>
                <option value="Meat">Meat</option>
                <option value="Snacks">Snacks</option>
                <option value="Sauce">Sauce</option>
                <option value="Desserts">Desserts</option>
                <option value="Bread">Bread</option>
                <option value="Spices">Spices</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Low Stock Warning Level</label>
            <input type="number" step="0.01" name="low_stock_threshold" class="form-control" value="5.00" required>
        </div>

        <input type="submit" value="Add Item" class="btn btn-success">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </form>
</div>

</body>
</html>
