<?php
require_once '../config/db.php';

// Delete old users first (optional but recommended)
$conn->query("DELETE FROM users");

$users = [
    ['name' => 'Admin_1', 'email' => 'admin1@example.com', 'password' => 'admin123', 'role' => 'admin'],
    ['name' => 'Admin_2', 'email' => 'admin2@example.com', 'password' => 'admin456', 'role' => 'admin'],
    ['name' => 'Staff_1', 'email' => 'staff1@example.com', 'password' => 'staff123', 'role' => 'staff'],
    ['name' => 'Staff_2', 'email' => 'staff2@example.com', 'password' => 'staff456', 'role' => 'staff'],
    ['name' => 'Staff_3', 'email' => 'staff3@example.com', 'password' => 'staff789', 'role' => 'staff'],
    ['name' => 'Staff_4', 'email' => 'staff4@example.com', 'password' => 'staff321', 'role' => 'staff'],
];

foreach ($users as $user) {
    $hashed = password_hash($user['password'], PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user['name'], $user['email'], $hashed, $user['role']);
    $stmt->execute();
}

echo "✅ Users inserted successfully with hashed passwords!";
