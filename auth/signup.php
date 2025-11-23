<?php
session_start();
include('../includes/db.php'); // this defines $pdo

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        // Check if email already exists
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $message = "⚠️ Email already registered!";
        } else {
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $role]);

            // Set session variables
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;

            header("Location: ../index.php");
            exit;
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="http://localhost/quiz_port/assets/css/style.css?v=<?php echo time(); ?>">

<link rel="icon" type="image/png" href="../assets/images/pic1.png">

  <style>
    /* Body background animation */
    body {
      background: linear-gradient(270deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      background-size: 800% 800%;
      animation: gradientBG 15s ease infinite;
      transition: background 0.5s ease;
    }
    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Card animation */
    .card {
      border-radius: 15px;
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      opacity: 0;
      transform: translateY(50px);
      animation: fadeUp 0.8s forwards;
    }
    @keyframes fadeUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    /* Input and button transitions */
    input.form-control, select.form-select, button.btn {
      transition: all 0.3s ease;
    }
    input.form-control:focus, select.form-select:focus {
      border-color: #007bff;
      box-shadow: 0 0 8px rgba(0,123,255,0.3);
    }
    button.btn:hover {
      transform: scale(1.03);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* Alert fade-in */
    .alert {
      opacity: 0;
      animation: alertFade 1s forwards;
    }
    @keyframes alertFade {
      to { opacity: 1; }
    }

    /* Smooth link transition */
    a {
      transition: color 0.3s ease;
    }
    a:hover {
      color: #0056b3;
      text-decoration: underline;
    }

    /* Password lock/unlock button */
    .password-wrapper {
      position: relative;
    }
    .password-wrapper input[type="password"],
    .password-wrapper input[type="text"] {
      padding-right: 40px; /* space for lock icon */
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
      color: #999;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .toggle-password:hover {
      color: #007bff;
      transform: scale(1.2);
    }

    /* Blink animation while typing */
    @keyframes blink {
      0%, 100% { transform: scaleY(1); }
      50% { transform: scaleY(0.1); }
    }
  </style>
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card p-4 shadow">
        <h3 class="text-center mb-3">Sign Up</h3>
        <?php if ($message) echo "<div class='alert alert-warning'>$message</div>"; ?>
        <form method="POST">
          <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
          <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
          <div class="password-wrapper mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
            <span class="toggle-password" id="togglePassword">🔒</span>
          </div>
          <select name="role" class="form-select mb-3" required>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
          </select>
          <button class="btn btn-primary w-100">Sign Up</button>
        </form>
        <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
      </div>
    </div>
  </div>
</div>
<script>
  const togglePassword = document.getElementById('togglePassword');
  const password = document.getElementById('password');

  togglePassword.addEventListener('click', () => {
    if(password.type === 'password') {
      password.type = 'text';
      togglePassword.textContent = '🔓'; // unlock
    } else {
      password.type = 'password';
      togglePassword.textContent = '🔒'; // lock
    }
  });

  // Blink animation when typing
  password.addEventListener('input', () => {
    togglePassword.style.animation = 'blink 0.2s linear';
    setTimeout(() => togglePassword.style.animation = '', 200);
  });
</script>
</body>
</html>
