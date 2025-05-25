<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login
    exit();
}

$connection = new mysqli('localhost', 'root', '', 'manana_inventory');
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT inventory_logs.*, users.name AS user_name
        FROM inventory_logs
        LEFT JOIN users ON inventory_logs.updated_by = users.id
        ORDER BY change_time DESC";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-primary mb-4">📜 Inventory Logs</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Item</th>
                <th>Action</th>
                <th>Qty Changed</th>
                <th>User</th>
                <th>Date/Time</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['item_name']); ?></td>
            <td><?= htmlspecialchars($row['change_type']); ?></td>
            <td><?= htmlspecialchars($row['quantity_change']); ?></td>
            <td><?= htmlspecialchars($row['user_name']); ?></td>
            <td><?= $row['change_time']; ?></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
</div>

</body>
</html>

<?php $connection->close(); ?>
