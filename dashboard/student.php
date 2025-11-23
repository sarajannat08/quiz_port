<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT q.title, r.score, r.total, r.time_taken, r.date_submitted 
                       FROM results r 
                       JOIN quizzes q ON r.quiz_id = q.id 
                       WHERE r.user_id = ? ORDER BY r.date_submitted DESC");
$stmt->execute([$user_id]);
$results = $stmt->fetchAll();

include '../includes/header.php';
?>
<div class="container mt-5 text-light">
  <h2>Your Quiz Results</h2>
  <table class="table table-striped table-dark mt-4">
    <tr>
      <th>Quiz Title</th>
      <th>Score</th>
      <th>Total</th>
      <th>Time (sec)</th>
      <th>Date</th>
    </tr>
    <?php foreach ($results as $r): ?>
      <tr>
        <td><?php echo $r['title']; ?></td>
        <td><?php echo $r['score']; ?></td>
        <td><?php echo $r['total']; ?></td>
        <td><?php echo $r['time_taken']; ?></td>
        <td><?php echo $r['date_submitted']; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
