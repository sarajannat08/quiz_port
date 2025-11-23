<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="dashboard">
  <h2>Admin Panel - Welcome, <?php echo $_SESSION['name']; ?>!</h2>

  <h3>Manage Quizzes</h3>
  <a href="../quizzes/create.php" class="btn">Create New Quiz</a>
  <table>
    <tr><th>Title</th><th>Class</th><th>Section</th><th>Created</th></tr>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE created_by = ?");
    $stmt->execute([$_SESSION['user_id']]);
    foreach ($stmt as $quiz) {
      echo "<tr><td>{$quiz['title']}</td><td>{$quiz['class']}</td><td>{$quiz['section']}</td><td>{$quiz['created_at']}</td></tr>";
    }
    ?>
  </table>

 <h3>Manage Quizzes</h3>
<a href="../quizzes/create.php" class="btn">Create New Quiz</a>
<table>
  <tr><th>Title</th><th>Class</th><th>Section</th><th>Created</th><th>Actions</th></tr>
  <?php
  $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE created_by = ?");
  $stmt->execute([$_SESSION['user_id']]);
  foreach ($stmt as $quiz) {
    echo "<tr>
      <td>{$quiz['title']}</td>
      <td>{$quiz['class']}</td>
      <td>{$quiz['section']}</td>
      <td>{$quiz['created_at']}</td>
      <td>
        <a href='../quizzes/export_pdf.php?id={$quiz['id']}' target='_blank'>🗎 Export PDF</a>
      </td>
    </tr>";
  }
  ?>
</table>



  <a href="../auth/logout.php" class="btn">Logout</a>
</div>

<?php include '../includes/footer.php'; ?>

