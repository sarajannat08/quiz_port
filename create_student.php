<?php
require_once '../includes/db.php';

$name = "John Doe";
$email = "student@example.com";
$password = password_hash("123456", PASSWORD_DEFAULT);
$role = "student";

$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $password, $role]);

echo "Student created successfully!";
?>
