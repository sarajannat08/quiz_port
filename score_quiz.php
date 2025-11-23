<?php
session_start();
require_once 'includes/db.php';

$answers = $_POST['answers'];
$correct = $_POST['correct'];

$score = 0;
foreach ($answers as $i => $ans) {
    if ($ans === $correct[$i]) {
        $score++;
    }
}
$total = count($correct);
$time_taken = (int)$_POST['time_taken'];
$category = $_POST['category'] ?? 'Unknown';

$stmt = $pdo->prepare("INSERT INTO results (user_id, quiz_id, score, time_taken) VALUES (?, NULL, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $score, $time_taken]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5 text-center">
    <h2>🎉 Quiz Completed!</h2>
    <p class="mt-3 fs-4">You scored <strong><?= $score ?>/<?= $total ?></strong></p>
    <p class="mt-2">⏱ Time Taken: <strong><?= $time_taken ?> seconds</strong></p>
    <a href="external_quiz.php" class="btn btn-primary mt-3">Try Another Quiz</a>
    <a href="index.php" class="btn btn-primary mt-3">Go To Home</a>

    
</div>
</body>
</html>
