<?php
require_once '../includes/db.php';

$quiz_id = $_GET['id'] ?? null;
if (!$quiz_id) die('Quiz not found');

$stmt = $pdo->prepare("SELECT * FROM quizzes_maker WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) die('Quiz not found');

$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Student name passed dynamically (optional)
$student = $_GET['student'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($quiz['title']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2><?= htmlspecialchars($quiz['title']) ?></h2>
  <p>
    <strong>Class:</strong> <?= htmlspecialchars($quiz['class']) ?> |
    <strong>Subject:</strong> <?= htmlspecialchars($quiz['subject']) ?> |
    <strong>Institution:</strong> <?= htmlspecialchars($quiz['institution']) ?>
    <?php if ($student): ?> |
      <strong>Student:</strong> <?= htmlspecialchars($student) ?>
    <?php endif; ?>
  </p>
  <hr>
  <?php foreach ($questions as $i => $q): ?>
    <div class="mb-3">
      <p><strong>Q<?= $i+1 ?>:</strong> <?= $q['question'] ?></p>
      <ul>
        <li><?= htmlspecialchars($q['option1']) ?></li>
        <li><?= htmlspecialchars($q['option2']) ?></li>
        <li><?= htmlspecialchars($q['option3']) ?></li>
        <li><?= htmlspecialchars($q['option4']) ?></li>
      </ul>
      <p class="text-success"><em>Answer:</em> <?= htmlspecialchars($q["option{$q['correct_option']}"]) ?></p>
    </div>
    <hr>
  <?php endforeach; ?>
</div>
</body>
</html>
