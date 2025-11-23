<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$categories = [
    9 => "General Knowledge",
    10 => "Entertainment: Books",
    11 => "Entertainment: Film",
    12 => "Entertainment: Music",
    14 => "Entertainment: Television",
    15 => "Entertainment: Video Games",
    17 => "Science & Nature",
    18 => "Science: Computers",
    19 => "Science: Mathematics",
    21 => "Sports",
    22 => "Geography",
    23 => "History",
    24 => "Politics",
    27 => "Animals",
    31 => "Entertainment: Anime & Manga"
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = (int)$_POST['amount'];
    $category = (int)$_POST['category'];
    $url = "https://opentdb.com/api.php?amount=$amount&category=$category&type=multiple";

    $response = file_get_contents($url);
    $data = json_decode($response, true);
    $questions = $data['results'];

    $quiz_id = 1; // Replace with a valid quiz_id from your quizzes table

   // Create a new quiz row first
$stmt = $pdo->prepare("INSERT INTO quizzes_maker (title, created_at) VALUES (?, NOW())");
$stmt->execute(['Open Trivia Quiz']);
$quiz_id = $pdo->lastInsertId();   // ✅ guaranteed to exist

foreach ($questions as $q) 
    $check = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE question = ?");
    $check->execute([$q['question']]);
    if ($check->fetchColumn() == 0) {
        $options = $q['incorrect_answers'];
        $options[] = $q['correct_answer'];
        shuffle($options);

        while (count($options) < 4) {
            $options[] = "N/A";
        }

        $correctIndex = array_search($q['correct_answer'], $options) + 1;

        $stmt = $pdo->prepare("INSERT INTO questions 
            (quiz_id, question, option1, option2, option3, option4, correct_option)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $quiz_id,
            $q['question'],
            $options[0],
            $options[1],
            $options[2],
            $options[3],
            $correctIndex
        ]);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Open Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <h2 class="mb-4">🎯 Open Quiz</h2>

    <?php if (!isset($questions)): ?>
        <form method="POST">
            <div class="mb-3">
                <label>Category:</label>
                <select name="category" class="form-select" required>
                    <?php foreach ($categories as $id => $name): ?>
                        <option value="<?= $id ?>"><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Number of Questions:</label>
                <select name="amount" class="form-select" required>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                </select>
            </div>
            <button class="btn btn-primary">Start Quiz</button>
        </form>
    <?php else: ?>
        <form id="quizForm" method="POST" action="score_quiz.php">
            <div id="timer" class="mb-3 fw-bold">⏱ Time Left: <span id="time"></span></div>
            <input type="hidden" name="time_taken" id="time_taken">
            <input type="hidden" name="category" value="<?= $category ?>">

            <?php foreach ($questions as $index => $q): ?>
                <div class="mb-4">
                    <p><strong>Q<?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></p>
                    <?php
                    $options = $q['incorrect_answers'];
                    $options[] = $q['correct_answer'];
                    shuffle($options);
                    ?>
                    <?php foreach ($options as $opt): ?>
                        <label class="d-block">
                            <input type="radio" name="answers[<?= $index ?>]" value="<?= htmlspecialchars($opt) ?>" required>
                            <?= htmlspecialchars($opt) ?>
                        </label>
                    <?php endforeach; ?>
                    <input type="hidden" name="correct[<?= $index ?>]" value="<?= htmlspecialchars($q['correct_answer']) ?>">
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-success">Submit Answers</button>
        </form>
    <?php endif; ?>
</div>

<?php if (isset($questions)): ?>
<script>
let totalSeconds = <?= count($questions) ?> * 60;
let timerEl = document.getElementById("time");
let timeTakenEl = document.getElementById("time_taken");

function formatTime(s) {
    let m = Math.floor(s / 60);
    let sec = s % 60;
    return `${m}:${sec < 10 ? '0' : ''}${sec}`;
}

let interval = setInterval(() => {
    totalSeconds--;
    timerEl.textContent = formatTime(totalSeconds);
    timeTakenEl.value = <?= count($questions) ?> * 60 - totalSeconds;

    if (totalSeconds <= 0) {
        clearInterval(interval);
        alert("⏰ Time's up! Submitting your quiz.");
        document.getElementById("quizForm").submit();
    }
}, 1000);
</script>
<?php endif; ?>
</body>
</html>
