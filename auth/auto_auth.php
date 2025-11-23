<?php
session_start();
require_once '../includes/db.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$role = $_POST['role'];

if (empty($email) || empty($password)) {
  $_SESSION['error'] = "Email and password are required.";
  header("Location: ../index.php");
  exit;
}

$check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$check->execute([$email]);
$user = $check->fetch();

if ($user) {
  // Email exists → verify password
  if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['success'] = "Welcome back, {$user['name']}!";
    header("Location: ../index.php");
    exit;
  } else {
    $_SESSION['error'] = "Incorrect password.";
    header("Location: ../index.php");
    exit;
  }
} else {
  // Email not found → create account
  $hashed = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $hashed]);

  $new_id = $pdo->lastInsertId();

  $_SESSION['user_id'] = $new_id;
  $_SESSION['role'] = $role;
  $_SESSION['name'] = $name;
  $_SESSION['success'] = "Account created. Welcome, $name!";
  header("Location: ../index.php");
  exit;
}
?>
