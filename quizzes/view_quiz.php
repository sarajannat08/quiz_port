<?php
session_start();
require_once __DIR__ . '/../includes/db.php'; // ✅ correct path

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$quiz_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$printMode = isset($_GET['print']);

if ($quiz_id <= 0) {
    die("Paper ID not provided.");
}

// Fetch paper info and ensure ownership
$stmt = $pdo->prepare("SELECT * FROM saved_papers WHERE id = ? AND user_id = ?");
$stmt->execute([$quiz_id, $_SESSION['user_id']]);
$paper = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$paper) {
    die("Paper not found or not yours.");
}

// Fetch questions
$qStmt = $pdo->prepare("SELECT * FROM saved_questions WHERE paper_id = ? ORDER BY id ASC");
$qStmt->execute([$quiz_id]);
$questions = $qStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Paper</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body{font-family:sans-serif;background:#f0f4f8;padding:20px;}
        .container{max-width:800px;margin:auto;background:white;padding:20px;border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,0.1);}
        h2{color:#4caf50;text-align:center;}
        .questionBox{padding:10px;margin-bottom:15px;border-bottom:1px solid #ddd;transition:0.3s;}
        .questionBox:hover{background:#f1f8e9;transform:scale(1.01);}
        ul{list-style-type:circle;margin-left:20px;}
        .top-bar{text-align:right;margin-bottom:15px;}
        .top-bar a{background:#2196f3;padding:6px 12px;border-radius:5px;color:white;text-decoration:none;margin-left:5px;transition:0.3s;}
        .top-bar a:hover{background:#0b7dda;transform:scale(1.05);}
        @media print {
            body * { visibility: hidden; }
            #printArea, #printArea * { visibility: visible; }
            #printArea { position: absolute; left:0; top:0; width:100%; }
            @media print {
    .top-bar { display: none; }
    .questionBox { page-break-inside: avoid; }
}
        }
    </style>
</head>
<body>
<div class="container" id="printArea">
    <div class="top-bar">
        <?php if(!$printMode): ?>
            <a href="history.php">Back</a>
            <a href="#" onclick="window.print()">Print</a>
        <?php endif; ?>
    </div>

    <h2>Paper Preview</h2>
    <p><b>Class:</b> <?= htmlspecialchars($paper['class']) ?> |
       <b>Subject:</b> <?= htmlspecialchars($paper['subject']) ?> |
       <b>Institution:</b> <?= htmlspecialchars($paper['institution']) ?> |
       <b>Date:</b> <?= htmlspecialchars($paper['created_at']) ?></p>

    <?php if (empty($questions)): ?>
        <p>No questions found.</p>
    <?php else: ?>
        <?php foreach($questions as $i => $q): ?>
        <div class="questionBox">
            <b>Q<?= $i+1 ?>:</b> <?= htmlspecialchars($q['question']) ?>
            <ul>
                <li><?= htmlspecialchars($q['option1']) ?></li>
                <li><?= htmlspecialchars($q['option2']) ?></li>
                <li><?= htmlspecialchars($q['option3']) ?></li>
                <li><?= htmlspecialchars($q['option4']) ?></li>
            </ul>
            <?php if(!$printMode): ?>
    <em>Correct option: <?= htmlspecialchars((string)$q['correct_option']) ?></em>
<?php endif; ?>

        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
