<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/question_bank.php';
session_start();

if (empty($_SESSION['student_id']) || empty($_SESSION['attempt_id'])) {
  header("Location: index.php");
  exit;
}

$conn = db();
$attempt_id = (int)$_SESSION['attempt_id'];
$student_id = (int)$_SESSION['student_id'];

$chk = $conn->prepare("SELECT submitted FROM attempts WHERE id=? AND student_id=? LIMIT 1");
$chk->bind_param("ii", $attempt_id, $student_id);
$chk->execute();
$row = $chk->get_result()->fetch_assoc();

if (!$row) {
  header("Location: start.php");
  exit;
}

if ((int)$row['submitted'] === 1) {
  header("Location: result.php");
  exit;
}

$bank = get_question_bank();

$payload = [
  "studentName" => $_SESSION['student_name'] ,
  "maxTimeSeconds" => MAX_TIME_SECONDS,
  "mcq" => $bank["mcq"],
  "ident" => $bank["ident"]
];

require __DIR__ . '/partials/header.php';
?>

<section class="card">
  <div class="row between">
    <div>
      <h2 class="h2">Exam Session</h2>
      <div class="muted">Student: <strong><?= h($_SESSION['student_name'] ) ?></strong></div>
    </div>

    <div class="timer">
      <div class="timer__label muted">Time Left</div>
      <div class="timer__value" id="timer">--:--</div>
    </div>
  </div>

  <div class="progress">
    <div class="progress__bar" id="progressBar"></div>
  </div>

  <div class="quizlayout">
    <aside class="qnav">
      <div class="qnav__title">Questions</div>
      <div id="qGrid" class="qgrid"></div>
      <div class="muted small qhint">Tap a number to jump</div>
    </aside>

    <div class="qstage">
      <div id="quizRoot"></div>

      <div class="row between quiz__actions">
        <button id="prevBtn" class="btn btn--ghost" type="button">← Prev</button>
        <div class="muted" id="qCounter">Question</div>
        <button id="nextBtn" class="btn btn--primary" type="button">Next →</button>
      </div>

      <div class="quiz__submitRow">
        <button id="submitBtn" class="btn btn--submit" type="button" disabled>Submit Exam</button>
      </div>
    </div>
  </div>
</section>

<div class="modal" id="modal" aria-hidden="true">
  <div class="modal__card pop">
    <div class="modal__emoji" id="modalEmoji">🎉</div>
    <h3 class="h3" id="modalTitle">Submitted!</h3>
    <p class="lead" id="modalMsg">Nice work!</p>
    <button class="btn btn--primary" id="modalBtn">See Results →</button>
  </div>
</div>

<script>
  window.QUIZ_PAYLOAD = <?= json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
