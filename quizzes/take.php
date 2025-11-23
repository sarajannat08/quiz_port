<?php
session_start();
require_once '../includes/db.php';

// ✅ Ensure student is logged in
if (empty($_SESSION['user_email']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}


include '../includes/header.php';



// STEP 1: Show class & subject selection
if (!isset($_GET['class']) || !isset($_GET['subject'])) {
    $classes = $pdo->query("SELECT DISTINCT class FROM quizzes ORDER BY class ASC")->fetchAll(PDO::FETCH_COLUMN);
    $subjects = $pdo->query("SELECT DISTINCT subject FROM quizzes ORDER BY subject ASC")->fetchAll(PDO::FETCH_COLUMN);
    ?>
    <div class="container mt-5 text-center text-light">
        <h2>Select Class and Subject</h2>
        <form method="GET" action="">
            <div class="row justify-content-center mt-4">
                <div class="col-md-4">
                    <select name="class" class="form-select mb-3" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $cls): ?>
                            <option value="<?= htmlspecialchars($cls) ?>">Class <?= htmlspecialchars($cls) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="subject" class="form-select mb-3" required>
                        <option value="">Select Subject</option>
                        <?php foreach ($subjects as $sub): ?>
                            <option value="<?= htmlspecialchars($sub) ?>"><?= ucfirst($sub) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Find Quizzes</button>
        </form>
    </div>
    <?php
    include '../includes/footer.php';
    exit;
}

// STEP 2: Show quizzes for selected class & subject
$class = $_GET['class'];
$subject = $_GET['subject'];

if (!isset($_GET['quiz_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE class = ? AND subject = ?");
    $stmt->execute([$class, $subject]);
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="container text-center text-light mt-5">
        <h3>Available Quizzes for Class <?= htmlspecialchars($class) ?> - <?= htmlspecialchars($subject) ?></h3>
        <?php if ($quizzes): ?>
            <ul class="list-group mt-4">
                <?php foreach ($quizzes as $quiz): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($quiz['title']) ?>
                        <a href="take.php?class=<?= urlencode($class) ?>&subject=<?= urlencode($subject) ?>&quiz_id=<?= $quiz['id'] ?>" class="btn btn-success">Attempt</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="mt-4 text-warning">No quizzes found for this class and subject.</p>
        <?php endif; ?>
    </div>
    <?php
    include '../includes/footer.php';
    exit;
}

// STEP 3: Load quiz questions
$quiz_id = $_GET['quiz_id'];
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    echo "<p class='text-center text-danger mt-5'>Quiz not found.</p>";
    include '../includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container text-light mt-5">
    <h2><?= htmlspecialchars($quiz['title']) ?> (Class <?= htmlspecialchars($quiz['class']) ?> - <?= htmlspecialchars($quiz['subject']) ?>)</h2>
    <div id="timer" class="mb-3 fw-bold">⏱ Time: <span id="time">0</span> seconds</div>

    <form id="quizForm" method="POST" action="result.php">
        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
        <input type="hidden" name="time_taken" id="time_taken">

        <?php foreach ($questions as $index => $q): ?>
            <div class="question-block p-3 mb-4 bg-dark rounded">
                <p><strong>Q<?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></p>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <label class="d-block">
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $i ?>" required>
                        <?= htmlspecialchars($q["option$i"]) ?>
                    </label>
                <?php endfor; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Submit Quiz</button>
    </form>
</div>

<script>
let seconds = 0;
const timerEl = document.getElementById("time");
const timeTakenEl = document.getElementById("time_taken");

setInterval(() => {
    seconds++;
    timerEl.textContent = seconds;
    timeTakenEl.value = seconds;
}, 1000);
</script>

<?php include '../includes/footer.php'; ?>
