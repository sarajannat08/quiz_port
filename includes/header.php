<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
  <link rel="icon" type="image/x-icon" href="assets/images/pic1.png">
  


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
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">Quiz Portal</a>
      <div class="ms-auto">
        <?php if(isset($_SESSION['user_name'])): 
          $initial = strtoupper($_SESSION['user_name'][0]);
        ?>
          <span class="btn btn-primary rounded-circle" title="<?php echo $_SESSION['user_name']; ?>">
            <?php echo $initial; ?>
          </span>
          <a href="auth/logout.php" class="btn btn-outline-danger ms-2">Logout</a>
        <?php else: ?>
          <a href="auth/login.php" class="btn btn-success me-2">Login</a>
          <a href="auth/signup.php" class="btn btn-primary">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

<main style="margin-top: 70px;">
