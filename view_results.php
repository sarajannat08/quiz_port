<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM results WHERE user_id = ? ORDER BY taken_at DESC");
$stmt->execute([$user_id]);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://localhost/quiz_port/assets/css/style.css?v=<?php echo time(); ?>">

  <link rel="icon" type="image" href="assets/images/pic1.png">
</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">📊 Quiz Results</h2>

    <?php if (count($results) === 0): ?>
        <p class="text-center">No results found. Try taking a quiz!</p>
    <?php else: ?>
        <table class="table table-bordered table-striped table-dark">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Score</th>
                    <th>Time Taken (sec)</th>
                    <th>Taken At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $r['score'] ?></td>
                        <td><?= $r['time_taken'] ?></td>
                        <td><?= date("d M Y, h:i A", strtotime($r['taken_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="external_quiz.php" class="btn btn-primary">Take Another Quiz</a>
        <a href="index.php" class="btn btn-primary">Go To Home</a>
        
    </div>
</div>
</body>
</html>
