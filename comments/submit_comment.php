<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $quiz_id = $_POST['quiz_id'] ?? null;
    $parent_id = $_POST['parent_id'] ?? null;
    $user_id = $_SESSION['user_id'];

    if ($message !== '') {
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, quiz_id, message, parent_id, submitted_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $quiz_id, $message, $parent_id]);
    }

    header("Location: view_comment.php");
    exit;
}
?>
