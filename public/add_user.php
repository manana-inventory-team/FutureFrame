<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:520px">
    <h2 class="text-center text-primary mb-4">👤 Add New User</h2>

    <form action="add_user_process.php" method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button class="btn btn-success w-100">Create User</button>

        <?php
        if (isset($_GET['error']) && $_GET['error'] === 'duplicate') {
            echo "<p class='text-danger mt-3 text-center'>Email already exists</p>";
        }
        ?>
    </form>

    <p class="text-center mt-3">
        <a href="dashboard.php">← Back to Dashboard</a>
    </p>
</div>
</body>
</html>
