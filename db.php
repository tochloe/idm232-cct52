<?php
$host = 'localhost:8889'; // MAMP MySQL port
$user = 'root';
$pass = 'root';
$db   = 'all_recipes';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>