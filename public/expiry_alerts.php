<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$cn = new mysqli('localhost', 'root', '', 'manana_inventory');
if ($cn->connect_error) die("Connection failed: " . $cn->connect_error);

// Show all items with low quantity
$sql = "SELECT * FROM inventory
        WHERE quantity <= low_stock_threshold
        AND item_name IS NOT NULL
        ORDER BY quantity ASC";


$items = $cn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Low Stock Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-danger mb-4">🚨 Low Stock Items</h2>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Item</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Low Stock Threshold</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($items->num_rows > 0): ?>
            <?php while ($row = $items->fetch_assoc()): ?>
            <tr class="table-warning">
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= htmlspecialchars($row['unit']) ?></td>
                <td><?= $row['low_stock_threshold'] ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">All stocks are healthy 🎉</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>
</body>
</html>
<?php $cn->close(); ?>
