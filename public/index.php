<?php
/* ---------- LOGIN PAGE ---------- */
session_start();

/* If someone is already logged-in, send them straight to the dashboard */
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login – Manana Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
    <div class="container" style="max-width:420px;">
        <h2 class="text-center text-primary mb-4">Manana Inventory Login</h2>

        <!-- ✅  IMPORTANT: method=\"POST\"  and  action=\"login_process.php\"  -->
        <form action="login_process.php" method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Login</button>

            <?php
            if (isset($_GET['error'])) {
                echo "<p class='text-danger mt-3 text-center'>Invalid email or password</p>";
            }
            ?>
        </form>
    </div>
</body>
</html>
