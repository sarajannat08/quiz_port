<?php
session_start();
require_once '../includes/db.php';

// Fetch top-level comments
function fetchComments($pdo, $parent_id = null) {
    $stmt = $pdo->prepare("SELECT c.*, u.name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.parent_id " . ($parent_id === null ? "IS NULL" : "= ?") . " ORDER BY c.submitted_at DESC");
    $stmt->execute($parent_id === null ? [] : [$parent_id]);
    return $stmt->fetchAll();
}

// Render comments recursively
function renderComments($pdo, $comments, $depth = 0) {
    foreach ($comments as $comment) {
        $margin = $depth * 30;
        echo "<div class='comment-box animate-on-scroll' style='margin-left: {$margin}px'>";
        echo "<p class='comment-author'>" . htmlspecialchars($comment['name']) . "</p>";
        echo "<p class='comment-message'>" . nl2br(htmlspecialchars($comment['message'])) . "</p>";
        echo "<small class='comment-meta'>" . date("d M Y, h:i A", strtotime($comment['submitted_at'])) . "</small>";
        echo "<div class='comment-actions'>";
        echo "<form method='POST' action='react_comment.php' class='d-inline'>";
        echo "<input type='hidden' name='comment_id' value='{$comment['id']}'>";
        echo "<button type='submit' name='emoji' value='like' class='emoji-btn'>❤️ {$comment['likes']}</button>";
        echo "</form>";
        echo "<button class='reply-toggle btn btn-sm btn-outline-light' data-id='{$comment['id']}'>↩️ Reply</button>";
        echo "</div>";

        echo "<form method='POST' action='submit_comment.php' class='reply-form' id='reply-form-{$comment['id']}' style='display:none'>";
        echo "<input type='hidden' name='parent_id' value='{$comment['id']}'>";
        echo "<input type='hidden' name='quiz_id' value='{$comment['quiz_id']}'>";
        echo "<textarea name='message' class='form-control mb-2' rows='2' placeholder='Write a reply...' required></textarea>";
        echo "<button type='submit' class='btn btn-success btn-sm'>Reply</button>";
        echo "</form>";

        $replies = fetchComments($pdo, $comment['id']);
        if ($replies) {
            renderComments($pdo, $replies, $depth + 1);
        }

        echo "</div>";
    }
}

$topLevelComments = fetchComments($pdo);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Discussion Thread</title>
    <link href="../assets/style.css" rel="stylesheet">

<style>
    body {
  background-color: #121212;
  color: #eee;
  font-family: 'Segoe UI', sans-serif;
  font-size: 18px;
  text-align: center;
}

.container {
  max-width: 800px;
  margin: auto;
  padding: 20px;
}

h2 {
  color: #8ab4f8;
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 30px;
}

.comment-form,
.comment-box {
  background-color: #1e1e1e;
  border: 1px solid #444;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 25px;
  box-shadow: 0 0 15px rgba(138, 180, 248, 0.2);
  transition: all 0.3s ease;
  text-align: left;
}

.comment-form:hover,
.comment-box:hover {
  box-shadow: 0 0 25px rgba(138, 180, 248, 0.3);
  transform: scale(1.01);
}

.comment-form textarea,
.reply-form textarea {
  background-color: #2a2a2a;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 14px;
  font-size: 16px;
  resize: vertical;
  width: 100%;
}

.comment-form button,
.reply-form button {
  background-color: #8ab4f8;
  border: none;
  color: #000;
  font-weight: bold;
  border-radius: 6px;
  padding: 10px 20px;
  font-size: 16px;
  transition: background-color 0.3s ease;
}

.comment-form button:hover,
.reply-form button:hover {
  background-color: #6a9ef6;
}

.comment-author {
  font-weight: bold;
  color: #8ab4f8;
  font-size: 1.1rem;
  margin-bottom: 5px;
}

.comment-label {
  font-weight: bold;
  color: #aaa;
  margin-bottom: 5px;
}

.comment-message {
  margin: 10px 0;
  line-height: 1.6;
}

.comment-meta {
  font-size: 0.9rem;
  color: #aaa;
}

.comment-actions {
  margin-top: 10px;
  display: flex;
  gap: 12px;
  align-items: center;
}

.emoji-btn {
  background: none;
  border: none;
  font-size: 1.4rem;
  color: #ff6ec7;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.emoji-btn:hover {
  transform: scale(1.3);
}

.reply-toggle {
  font-size: 0.9rem;
  padding: 6px 12px;
}

.animate-on-scroll {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.6s ease-out;
}

.animate-on-scroll.visible {
  opacity: 1;
  transform: translateY(0);
}

.btn-outline-light,
.btn-info {
  font-size: 1rem;
  padding: 10px 20px;
  border-radius: 6px;
}

.emoji-btn {
  background: none;
  border: none;
  font-size: 1.4rem;
  color: #ff6ec7;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.emoji-btn:hover {
  transform: scale(1.3);
}

    </style>






</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <h2 class="text-center mb-4">💬 Discussion Thread</h2>

    <!-- New Comment Form -->
    <form method="POST" action="submit_comment.php" class="comment-form mb-5">
        <textarea name="message" class="form-control mb-3" rows="4" placeholder="Start a new comment..." required></textarea>
        <input type="hidden" name="quiz_id" value="1">
        <button type="submit" class="btn btn-primary me-2">Post Comment</button>
        <a href="../index.php" class="btn btn-info">Back to 🏠</a>
    </form>

    
    <!-- Render Comments -->
    <?php renderComments($pdo, $topLevelComments); ?>
</div>

<script src="../assets/script.js"></script>
</body>
</html>
