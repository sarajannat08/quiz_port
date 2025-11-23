<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['comment_id'])) {
    $comment_id = (int)$_POST['comment_id'];

    // Optional: prevent multiple likes per session
    if (!isset($_SESSION['liked_comments'])) {
        $_SESSION['liked_comments'] = [];
    }

    if (!in_array($comment_id, $_SESSION['liked_comments'])) {
        $stmt = $pdo->prepare("UPDATE comments SET likes = likes + 1 WHERE id = ?");
        $stmt->execute([$comment_id]);
        $_SESSION['liked_comments'][] = $comment_id;
    }

    header("Location: view_comment.php");
    exit;
}
?>
