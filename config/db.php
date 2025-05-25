
<?php
$host = 'localhost';
$db   = 'manana_restaurant'; // name of the database you created in phpMyAdmin
$user = 'root';
$pass = ''; // default password for XAMPP

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
