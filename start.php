<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
session_start();

if (empty($_SESSION['student_id'])) {
  header("Location: index.php");
  exit;
}

$conn = db();
$student_id = (int)$_SESSION['student_id'];

$stmt = $conn->prepare("SELECT id, submitted FROM attempts WHERE student_id=? LIMIT 1");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$attempt = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($attempt && (int)$attempt['submitted'] === 1) {
    header("Location: result.php");
    exit;
  }

  $up = $conn->prepare("
    INSERT INTO attempts (student_id, submitted, score_mcq, score_ident, total_score, time_seconds, compliment)
    VALUES (?, 0, 0, 0, 0, 0, NULL)
    ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)
  ");
  $up->bind_param("i", $student_id);
  $up->execute();

  $_SESSION['attempt_id'] = (int)$conn->insert_id;
  header("Location: quiz.php");
  exit;
}

require __DIR__ . '/partials/header.php';
?>

<section class="card">
  <div class="row between">
    <div>
      <h2 class="h2">Start Exam</h2>
      <div class="muted">Student: <strong><?= h($_SESSION['student_name']) ?></strong></div>
    </div>
    <div class="pill">One attempt only</div>
  </div>

  <?php if ($attempt && (int)$attempt['submitted'] === 1): ?>
    <div class="alert">
      You already submitted your exam. You can only view your result.
    </div>
    <div class="row end" style="margin-top:14px;">
      <a class="btn btn--primary" href="result.php">View Result</a>
    </div>
  <?php else: ?>
    <div class="panel">
      <h3 class="h3">Rules</h3>
      <ul class="rules">
        <li>20 Multiple Choice (A/B/C/D)</li>
        <li>10 Identification (type the tool/logo name)</li>
        <li>You may navigate questions before submitting</li>
        <li>Once submitted, answers are locked</li>
        <li>Time limit: <strong><?= (int)(MAX_TIME_SECONDS/60) ?> minutes</strong></li>
      </ul>
    </div>

    <div class="row between" style="margin-top:16px;">
      <a class="btn btn--ghost" href="index.php">← Change Name</a>
      <form method="post">
        <button class="btn btn--primary" type="submit">
          Begin Exam
          <span class="btn__shine"></span>
        </button>
      </form>
    </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
