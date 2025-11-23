<?php
// delete_quiz.php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$quiz_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($quiz_id <= 0) {
    header("Location: history.php");
    exit;
}

// Ensure ownership
$stmt = $pdo->prepare("SELECT id FROM quizzes_maker WHERE id = ? AND user_id = ?");
$stmt->execute([$quiz_id, $_SESSION['user_id']]);
if (!$stmt->fetch()) {
    header("Location: history.php");
    exit;
}

// Delete quiz; questions should be removed via FK ON DELETE CASCADE
$del = $pdo->prepare("DELETE FROM quizzes_maker WHERE id = ?");
$del->execute([$quiz_id]);

header("Location: history.php");
exit;
