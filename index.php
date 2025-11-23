<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title> Quiz Port</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
<link rel="stylesheet" href="http://localhost/quiz_port/assets/css/style.css?v=<?php echo time(); ?>">

  <link rel="icon" type="image" href="assets/images/pic1.png">
</head>
<body>

<!-- 🌐 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
<a class="navbar-brand fw-bold" href="#">
  📚 Online Quiz Portal
  <span class="badge bg-danger ms-2"></span>
</a>


    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home 🏠</a></li>
        <li class="nav-item"><a class="nav-link" href="external_quiz.php">Take Quiz</a></li>
        <li class="nav-item"><a class="nav-link" href="view_results.php">View Results</a></li>
      
        <li class="nav-item"><a class="nav-link" href="contact/contact.php">Contact ☎︎彡</a></li>
      </ul>

      <div class="ms-lg-3">
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
  </div>
</nav>


<!-- Background Layer -->
  <div class="background-animation"></div>

<!-- 🎓 Hero Section -->
<section class="hero d-flex align-items-center justify-content-center text-center animate-fade-in" style="min-height: 90vh;">


  <!-- Foreground Content -->
  <div class="content">
    <div class="feature-box fancy-box p-5" style="max-width: 800px;">
      <h1 class="mb-3 animate-fade-in delay-1" style="color: #ffd49dff;">🎓 Welcome to Quiz Portal</h1>
      <p class="lead animate-fade-in delay-2" style="color: #b7ff8eff; font-weight: bold;">⚡ Practice Participate Progress </p>
      <a href="external_quiz.php" class="btn btn-light btn-lg mt-4 animate-fade-in delay-3">
        ❓📝✔️ Let's -ˋˏStudyˎˊ-
      </a>
    </div>
  </div>


</section>



<!-- 🌟 Features Section -->
<section class="features-section py-5">
  <div class="container text-center">
    <h2 class="mb-5 feature-title">🔧 Features</h2>

    <div class="row g-4 justify-content-center">

      <!-- Feature 1 -->
      <div class="col-md-3">
        <div class="feature-box">
          <h4>📝 Take Quizzes</h4>
          <p>👩🏻‍💻🧠 Challenge yourself or others anytime [ ⩇⩇:⩇⩇ ] </p>
          <a href="external_quiz.php" class="btn feature-btn btn-primary">Take Quiz</a>
        </div>
      </div>

      <!-- Feature 2 -->
      <div class="col-md-3">
        <div class="feature-box">
          <h4>📊 Track Scores & Time</h4>
          <p> 📜✨✍️🥇 Monitor your quiz performance easily.</p>
          <a href="view_results.php" class="btn feature-btn btn-success">View Results</a>
        </div>
      </div>

      <!-- Feature 3 -->
      <div class="col-md-3">
        <div class="feature-box">
          <h4>💬 Comment & Get Replies</h4>
          <p> ✉🖊 Reach out to admins anytime for feedback.</p>
          <a href="comments/view_comment.php" class="btn feature-btn btn-warning">Comment</a>
        </div>
      </div>

      <!-- Feature 4 -->
      <div class="col-md-3">
        <div class="feature-box">
          <h4>📝 Create Quizzes<br> & ✎ Export </h4>
          <p> ‧₊˚🖇️✩ ₊˚📖 Create quizzes, print, download & view history.</p>
          <a href="quizzes/create.php" class="btn feature-btn btn-info">Hit The Button</a>
        </div>
      </div>

    </div>
  </div>
</section>



<!-- 🌐 Footer -->
<footer class="text-center text-lg-start text-white bg-footer mt-5 pt-4" style="background:#4c2c72;">

  <div class="container">

    <div class="row">

      <!-- 🔹 About -->
      <div class="col-lg-4 col-md-6 mb-4">
        <h5 class="fw-bold">Quiz Portal</h5>
        <p>
         ✨💖 A smart and interactive platform for quizzes, assessments, and learning.
        🚀🧩 Build knowledge, test skills, and grow everyday.
        </p>
      </div>

      <!-- 🔹 Contact Us -->
      <div class="col-lg-4 col-md-6 mb-4">
        <h5 class="fw-bold">Contact Us</h5>
        <p class="mb-1">📧 Email: support@quizportal.com</p>
        <p class="mb-1">📞 Phone: +0 1 2 3 4 5 6 7 8 9</p>
        <p>📍 Address: DHAKA, BANGLADESH</p>
      </div>

      <!-- 🔹 Quick Links -->
      <div class="col-lg-4 col-md-6 mb-4">
        <h5 class="fw-bold">Quick Links</h5>
        <a href="#" class="d-block text-white-50 mb-1">Home</a>
        <a href="#" class="d-block text-white-50 mb-1">About</a>
        <a href="#" class="d-block text-white-50 mb-1">Support</a>
        <a href="#" class="d-block text-white-50">Privacy Policy</a>
      </div>

    </div>
  </div>

  <!-- 🔻 Bottom bar -->
  <div class="text-center py-3 mt-3" style="background:#381f57;">
    © 2025 Quiz Portal — All rights reserved.
  </div>

</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


