<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$cn = new mysqli('localhost', 'root', '', 'manana_inventory');
if ($cn->connect_error) {
    die('DB connection failed: ' . $cn->connect_error);
}

$filter = $_GET['filter'] ?? '';

// Main inventory query
if ($filter === 'low') {
    $sql = "SELECT inventory.id, inventory.item_name, inventory.quantity, inventory.unit,
                   inventory.category, inventory.low_stock_threshold, inventory.updated_at,
                   users.name AS updated_by_name
            FROM inventory
            LEFT JOIN users ON inventory.updated_by = users.id
            WHERE inventory.quantity <= inventory.low_stock_threshold";
} elseif ($filter) {
    $sql = "SELECT inventory.id, inventory.item_name, inventory.quantity, inventory.unit,
                   inventory.category, inventory.low_stock_threshold, inventory.updated_at,
                   users.name AS updated_by_name
            FROM inventory
            LEFT JOIN users ON inventory.updated_by = users.id
            WHERE inventory.category = '$filter'";
} else {
    $sql = "SELECT inventory.id, inventory.item_name, inventory.quantity, inventory.unit,
                   inventory.category, inventory.low_stock_threshold, inventory.updated_at,
                   users.name AS updated_by_name
            FROM inventory
            LEFT JOIN users ON inventory.updated_by = users.id";
}
$items = $cn->query($sql);

// Expiry count logic
$expiry_query = "SELECT COUNT(*) AS expiring_count
                 FROM inventory
                 WHERE quantity <= low_stock_threshold
                 AND item_name IS NOT NULL";



$expiry_result = $cn->query($expiry_query);
$expiring_count = 0;
if ($expiry_result && $row = $expiry_result->fetch_assoc()) {
    $expiring_count = $row['expiring_count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Manana Inventory</a>
    <div class="d-flex align-items-center gap-3">
        <a href="expiry_alerts.php" class="btn position-relative btn-light">
            🔔
            <?php if ($expiring_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= $expiring_count ?>
                </span>
            <?php endif; ?>
        </a>
        <span class="text-light">Hello, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
        <a class="btn btn-outline-light" href="logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">

  <div class="mb-3 d-flex flex-wrap gap-2">
      <a href="add_item.php" class="btn btn-success">➕ Add Item</a>

      <?php if ($_SESSION['user_role'] === 'admin'): ?>
          <a href="add_user.php" class="btn btn-dark">👤 Add User</a>
          <a href="manage_user.php" class="btn btn-outline-dark">👥 Manage Users</a>
      <?php endif; ?>

      <a href="logs.php" class="btn btn-info">📜 View Logs</a>
  </div>

  <form method="GET" class="mb-4 d-flex align-items-center gap-2">
      <label class="form-label mb-0">Filter:</label>
      <select name="filter" class="form-select w-auto" onchange="this.form.submit()">
          <option value="">-- All Items --</option>
          <option value="low"      <?= $filter === 'low'      ? 'selected' : ''; ?>>🚨 Low Stock Only</option>
          <option value="Meat"     <?= $filter === 'Meat'     ? 'selected' : ''; ?>>Meat</option>
          <option value="Snacks"   <?= $filter === 'Snacks'   ? 'selected' : ''; ?>>Snacks</option>
          <option value="Sauce"    <?= $filter === 'Sauce'    ? 'selected' : ''; ?>>Sauce</option>
          <option value="Desserts" <?= $filter === 'Desserts' ? 'selected' : ''; ?>>Desserts</option>
          <option value="Bread"    <?= $filter === 'Bread'    ? 'selected' : ''; ?>>Bread</option>
          <option value="Spices"   <?= $filter === 'Spices'   ? 'selected' : ''; ?>>Spices</option>
      </select>
      <?php if ($filter): ?>
          <a href="dashboard.php" class="btn btn-secondary">Reset</a>
      <?php endif; ?>
  </form>

  <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
          <tr>
              <th>Item</th>
              <th>Qty</th>
              <th>Unit</th>
              <th>Category</th>
              <th>Threshold</th>
              <th>Updated By</th>
              <th>Updated At</th>
              <th style="width:90px">Actions</th>
          </tr>
      </thead>
      <tbody>
      <?php while ($row = $items->fetch_assoc()): ?>
          <?php $low = $row['quantity'] <= $row['low_stock_threshold']; ?>
          <tr class="<?= $low ? 'table-danger' : ''; ?>">
              <td><?= htmlspecialchars($row['item_name']); ?></td>
              <td>
                  <?= $low
                      ? "<span class='text-danger fw-bold'><i class=\"bi bi-exclamation-triangle\"></i> {$row['quantity']}</span>"
                      : $row['quantity']; ?>
              </td>
              <td><?= htmlspecialchars($row['unit']); ?></td>
              <td><?= htmlspecialchars($row['category']); ?></td>
              <td><?= $row['low_stock_threshold']; ?></td>
              <td><?= htmlspecialchars($row['updated_by_name']); ?></td>
              <td><?= $row['updated_at']; ?></td>
              <td>
                  <a href="update_item.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                      <i class="bi bi-pencil-square"></i>
                  </a>
              </td>
          </tr>
      <?php endwhile; ?>
      </tbody>
  </table>
</div>

</body>
</html>
<?php $cn->close(); ?>
