<?php
session_start();
require_once '../includes/db.php'; // PDO connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // ✅ Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $email;
            $_SESSION['role'] = $user['role'];

            // Redirect to quiz selection page
            header("Location: ../index.php");
            exit;
        } else {
            $message = "❌ Incorrect password!";
        }
    } else {
        $message = "⚠️ No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="http://localhost/quiz_port/assets/css/style.css?v=<?php echo time(); ?>">

<link rel="icon" type="image/png" href="../assets/images/pic1.png">
  
  <style>
    /* Body gradient animation */
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

    /* Input/button transitions */
    input.form-control, button.btn {
      transition: all 0.3s ease;
    }
    input.form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 8px rgba(40,167,69,0.3);
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

    /* Link transitions */
    a {
      transition: color 0.3s ease;
    }
    a:hover {
      color: #1e7e34;
      text-decoration: underline;
    }

    /* Password toggle button */
    .password-wrapper {
      position: relative;
    }
    .password-wrapper input {
      padding-right: 45px;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
      color: #555;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .toggle-password:hover {
      color: #28a745;
      transform: scale(1.2);
    }

    /* Eye blink animation */
    @keyframes blink {
      0%, 100% { transform: scaleY(1); }
      50% { transform: scaleY(0.1); }
    }
    .toggle-password.blink {
      animation: blink 0.2s linear;
    }
  </style>
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card p-4 shadow">
        <h3 class="text-center mb-3">Login</h3>
        <?php if ($message) echo "<div class='alert alert-danger'>$message</div>"; ?>
        <form method="POST">
          <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
          <div class="password-wrapper mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
            <span class="toggle-password" id="togglePassword">🔒</span>
          </div>
          <button class="btn btn-success w-100">Log In</button>
        </form>
        <p class="text-center mt-3">Don't have an account? <a href="signup.php">Sign Up</a></p>
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
      togglePassword.textContent = '🔓'; // unlock icon
    } else {
      password.type = 'password';
      togglePassword.textContent = '🔒'; // lock icon
    }
  });

  // Blink animation on typing
  password.addEventListener('input', () => {
    togglePassword.classList.add('blink');
    setTimeout(() => togglePassword.classList.remove('blink'), 200);
  });
</script>
</body>
</html>
