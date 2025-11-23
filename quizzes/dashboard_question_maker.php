<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$stmt = $pdo->prepare("SELECT id, title, class, subject, institution, created_at 
                       FROM quizzes_maker 
                       WHERE created_by = ? 
                       ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Question Maker Dashboard</h2>
    <a href="dashboard_question_maker.php" class="btn btn-primary">+ New Paper</a>
  </div>

  <?php if (!$quizzes): ?>
    <div class="alert alert-info">No papers yet. Create your first one.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($quizzes as $q): ?>
        <div class="list-group-item d-flex justify-content-between">
          <div>
            <strong><?= htmlspecialchars($q['title']) ?></strong>
            <div class="small text-muted">
              Class <?= htmlspecialchars($q['class']) ?> • Subject <?= htmlspecialchars($q['subject']) ?> • <?= htmlspecialchars($q['institution']) ?>
            </div>
            <div class="small text-muted"><?= htmlspecialchars($q['created_at']) ?></div>
          </div>
          <div class="d-flex gap-2">
            <a href="share_quiz.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank">Share</a>
            <a href="print_pdf.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-outline-success">PDF</a>
            <a href="preview.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-outline-dark">Preview</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>
