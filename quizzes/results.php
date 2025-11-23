<?php
session_start();
require_once '../includes/db.php';

// ✅ Only students allowed
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

// Make sure form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['quiz_id']) || empty($_POST['answers'])) {
    header("Location: take.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$quiz_id = $_POST['quiz_id'];
$answers = $_POST['answers'];
$time_taken = intval($_POST['time_taken'] ?? 0);

// Get quiz questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($questions);
$score = 0;

// Calculate score
foreach ($questions as $q) {
    $qid = $q['id'];
    $correct = $q['correct_option']; // Assuming `correct_option` column stores 1-4
    if (isset($answers[$qid]) && intval($answers[$qid]) === intval($correct)) {
        $score++;
    }
}

// Store result in database
$stmt = $pdo->prepare("INSERT INTO results (user_id, quiz_id, score, total, time_taken) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $quiz_id, $score, $total, $time_taken]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5 text-center">
    <h2>Quiz Completed!</h2>
    <p class="mt-4">You scored <strong><?= $score ?>/<?= $total ?></strong></p>
    <p>Time Taken: <strong><?= $time_taken ?> seconds</strong></p>
    <a href="take.php" class="btn btn-primary mt-3">Back to Quizzes</a>
</div>
</body>
</html>
