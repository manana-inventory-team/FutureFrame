<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT inventory.*, users.name AS updated_by_name 
        FROM inventory 
        LEFT JOIN users ON inventory.updated_by = users.id 
        ORDER BY updated_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #009578;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #009578;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            text-decoration: none;
            color: #009578;
        }

        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Inventory List</h2>

    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Category</th>
                <th>Expiry Date</th>
                <th>Last Updated</th>
                <th>Updated By</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['unit']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['expiry_date'] ?? '—'; ?></td>
                    <td><?php echo $row['updated_at']; ?></td>
                    <td><?php echo $row['updated_by_name']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="back">
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
