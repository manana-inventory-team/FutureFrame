<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$cn = new mysqli('localhost', 'root', '', 'manana_inventory');
if ($cn->connect_error) die("Connection failed: " . $cn->connect_error);

$id = $_GET['id'] ?? null;
if (!$id) die("No item ID provided.");

$result = $cn->query("SELECT * FROM inventory WHERE id = $id");
if (!$result || $result->num_rows === 0) die("Item not found.");

$item = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">✏️ Edit Inventory Item</h2>

    <form action="update_item_save.php" method="POST">
        <input type="hidden" name="id" value="<?= $item['id']; ?>">

        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" class="form-control" name="item_name" value="<?= htmlspecialchars($item['item_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" class="form-control" name="quantity" value="<?= $item['quantity']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" class="form-control" name="unit" value="<?= htmlspecialchars($item['unit']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" class="form-control" name="category" value="<?= htmlspecialchars($item['category']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Low Stock Threshold</label>
            <input type="number" class="form-control" name="low_stock_threshold" value="<?= $item['low_stock_threshold']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary">← Cancel</a>
    </form>
</div>
</body>
</html>
<?php $cn->close(); ?>
