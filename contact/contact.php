<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $message = trim($_POST['message']);

  if (empty($name) || empty($email) || empty($message)) {
    $_SESSION['error'] = "All fields are required.";
  } else {
    $stmt = $pdo->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);
    $_SESSION['success'] = "Message sent successfully!";
  }

  header("Location: contact.php");
  exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" type="image/x-icon" href="../assets/images/pic1.png">


  <style>
    .navbar .btn-primary.rounded-circle {
        width: 40px;
        height: 40px;
        padding: 0;
        text-align: center;
        line-height: 40px;
        font-weight: bold;
    }
  </style>
</head>




<div class="form-container">
  <h2>Contact Us</h2>
  <?php
    if (isset($_SESSION['error'])) {
      echo "<p class='error'>{$_SESSION['error']}</p>";
      unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
      echo "<p class='success'>{$_SESSION['success']}</p>";
      unset($_SESSION['success']);
    }
  ?>
 

 
 <style>
body {
  background-color: #121212;
  color: #eeeeeeff;
  font-family: 'Segoe UI', sans-serif;
}

.form-container {
  max-width: 600px;
  margin: 50px auto;
  background-color: #1e1e1e;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 255, 100, 0.2);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.form-container:hover {
  transform: scale(1.02);
  box-shadow: 0 0 25px rgba(0, 255, 100, 0.3);
}

.form-container h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #00ff99;
}

form input,
form textarea {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border: none;
  border-radius: 6px;
  background-color: #2a2a2a;
  color: #fff;
  transition: box-shadow 0.3s ease;
}

form input:focus,
form textarea:focus {
  box-shadow: 0 0 8px rgba(0, 255, 100, 0.5);
  outline: none;
}

form button {
  width: 100%;
  padding: 12px;
  background-color: #00ff99;
  border: none;
  border-radius: 6px;
  color: #000;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

form button:hover {
  background-color: #00cc88;
}

.success {
  color: #00ff99;
  text-align: center;
  margin-bottom: 15px;
}

.error {
  color: #ff4d4d;
  text-align: center;
  margin-bottom: 15px;
}

.home-btn {
  display: block;
  margin: 30px auto 0;
  text-align: center;
}

.home-btn a {
  display: inline-block;
  padding: 10px 20px;
  background-color: #444;
  color: #fff;
  border-radius: 6px;
  text-decoration: none;
  transition: background-color 0.3s ease;
}

.home-btn a:hover {
  background-color: #00ff99;
  color: #000;
}
</style>
 
 <form method="POST">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Your Message" required></textarea>
    <button type="submit">Send Message</button>
  </form>
  <div class="home-btn">
  <a href="../index.php">🏠 Back to Home</a>
</div>

</div>

<?php include '../includes/footer.php'; ?>




