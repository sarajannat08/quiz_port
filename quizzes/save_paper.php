<?php
session_start();
require_once __DIR__ . '/../includes/db.php'; // ✅ correct path

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Not logged in");
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $meta = $data['meta'] ?? [];
    $questions = $data['questions'] ?? [];

    if (empty($questions)) {
        throw new Exception("No questions provided");
    }

    $pdo->beginTransaction();

    // Insert paper metadata
    $stmt = $pdo->prepare("INSERT INTO saved_papers (user_id, class, subject, institution, created_at)
                           VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([
        $_SESSION['user_id'],
        $meta['class'] ?? '',
        $meta['subject'] ?? '',
        $meta['institution'] ?? ''
    ]);
    $paper_id = $pdo->lastInsertId();

    // Insert questions
    $stmtQ = $pdo->prepare("INSERT INTO saved_questions
        (paper_id, question, option1, option2, option3, option4, correct_option)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($questions as $q) {
        $stmtQ->execute([
            $paper_id,
            $q['question'],
            $q['option1'],
            $q['option2'],
            $q['option3'],
            $q['option4'],
            $q['correct']   // integer 1–4
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'paper_id' => $paper_id]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
