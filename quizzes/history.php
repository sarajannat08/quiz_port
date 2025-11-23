<?php
session_start();
require_once __DIR__ . '/../includes/db.php'; // ✅ correct path

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch saved papers for this user
$stmt = $pdo->prepare("
    SELECT id, class, subject, institution, created_at
    FROM saved_papers
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$papers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Saved Papers</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body{font-family:sans-serif;background:#f0f4f8;padding:20px;}
        .container{max-width:900px;margin:auto;background:white;padding:20px;border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,0.1);}
        h2{color:#4caf50;}
        table{width:100%;border-collapse:collapse;}
        th,td{border-bottom:1px solid #eee;padding:10px;text-align:left;}
        th{background:#f7fafc;}
        a.btn{background:#2196f3;padding:6px 12px;border-radius:5px;color:white;text-decoration:none;margin-right:6px;transition:0.3s;}
        a.btn:hover{background:#0b7dda;transform:scale(1.05);}
    </style>
</head>
<body>
<div class="container">
    <h2>📜 My Saved Papers</h2>

    <?php if (empty($papers)): ?>
        <p>No papers saved yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Institution</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($papers as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['class']) ?></td>
                    <td><?= htmlspecialchars($p['subject']) ?></td>
                    <td><?= htmlspecialchars($p['institution']) ?></td>
                    <td><?= htmlspecialchars($p['created_at']) ?></td>
                    <td>
                        <a class="btn" href="view_quiz.php?id=<?= $p['id'] ?>">View</a>
                        <a class="btn" href="view_quiz.php?id=<?= $p['id'] ?>&print=1" target="_blank">Print</a>
                        
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        
    <?php endif; ?>
</div>
</body>
</html>
