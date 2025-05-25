<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$cn = new mysqli('localhost','root','','manana_inventory');
if ($cn->connect_error) die($cn->connect_error);

$users = $cn->query("SELECT id, name, email, role FROM users ORDER BY id");
if (!$users) die("Query failed: " . $cn->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">👥 User Accounts</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th style="width:90px">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a href="delete_user.php?id=<?= $u['id'] ?>"
                           onclick="return confirm('Delete this user?');"
                           class="btn btn-danger btn-sm">🗑️</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>
</body>
</html>
<?php $cn->close(); ?>
