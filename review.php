<?php
// review.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/question_bank.php';
session_start();

if (empty($_SESSION['attempt_id']) || empty($_SESSION['student_id'])) {
  header("Location: index.php");
  exit;
}

$conn = db();
$attempt_id = (int)$_SESSION['attempt_id'];
$student_id = (int)$_SESSION['student_id'];

if (!function_exists('h')) {
  function h($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
}

/* must be submitted */
$chk = $conn->prepare("
  SELECT submitted, total_score, percent, created_at
  FROM attempts
  WHERE id=? AND student_id=?
  LIMIT 1
");
$chk->bind_param("ii", $attempt_id, $student_id);
$chk->execute();
$attempt = $chk->get_result()->fetch_assoc();

if (!$attempt) { header("Location: index.php"); exit; }
if ((int)$attempt['submitted'] !== 1) { header("Location: quiz.php"); exit; }

/* load question bank (MCQ only) */
$bank = get_question_bank();
$mcq = (isset($bank['mcq']) && is_array($bank['mcq'])) ? $bank['mcq'] : [];
$totalItems = count($mcq);

if ($totalItems < 1) {
  header("Location: result.php");
  exit;
}

/* q index (1..totalItems) */
$q = isset($_GET['q']) ? (int)$_GET['q'] : 1;
if ($q < 1) $q = 1;
if ($q > $totalItems) $q = $totalItems;

$idx = $q - 1;
$question = $mcq[$idx];

$qKey = (string)$question['key'];
$correct = (string)$question['answer'];

/* load given answers for this attempt */
$ansRows = $conn->prepare("
  SELECT q_key, given_answer, is_correct
  FROM attempt_answers
  WHERE attempt_id=? AND q_type='mcq'
");
$ansRows->bind_param("i", $attempt_id);
$ansRows->execute();
$rows = $ansRows->get_result()->fetch_all(MYSQLI_ASSOC);

$givenMap = [];
foreach ($rows as $r) {
  $givenMap[(string)$r['q_key']] = [
    'given' => (string)$r['given_answer'],
    'is_correct' => (int)$r['is_correct']
  ];
}

$given = '';
$isCorrect = 0;

if (isset($givenMap[$qKey])) {
  $given = (string)$givenMap[$qKey]['given'];
  $isCorrect = (int)$givenMap[$qKey]['is_correct'];
}

$hasAnswer = ($given !== '');

$statusText = $hasAnswer ? ($isCorrect ? 'Correct' : 'Wrong') : 'Not answered';
$statusClass = $hasAnswer ? ($isCorrect ? 'badge badge--ok' : 'badge badge--bad') : 'badge';

$prevQ = ($q > 1) ? ($q - 1) : 1;
$nextQ = ($q < $totalItems) ? ($q + 1) : $totalItems;

require __DIR__ . '/partials/header.php';
?>
<link rel="stylesheet" href="assets/css/review.css" />
<section class="card reviewPage">
  <!-- top header -->
  <div class="reviewTop">
    <div class="reviewTop__left">
      <h2 class="h2">Check Answers</h2>
      <div class="muted reviewTop__meta">
        Submitted: <?= h($attempt['created_at']) ?> •
        Score: <strong><?= (int)$attempt['total_score'] ?></strong>/<?= (int)$totalItems ?> •
        <strong><?= (int)$attempt['percent'] ?>%</strong>
      </div>
    </div>

    <div class="reviewTop__right">
      <a class="btn btn--ghost" href="result.php">← Back to Result</a>
    </div>
  </div>

  <!-- question header row -->
  <div class="reviewHead">
    <div class="reviewHead__left">
      <div class="reviewNum">Question <?= (int)$q ?> / <?= (int)$totalItems ?></div>
      <div class="reviewSub muted">Q<?= (int)$q ?>.</div>
    </div>

    <div class="reviewHead__right">
      <span class="<?= h($statusClass) ?>">
        <?= $hasAnswer ? ($isCorrect ? '✅ ' : '❌ ') : '— ' ?><?= h($statusText) ?>
      </span>
    </div>
  </div>

  <!-- question card -->
  <div class="panel reviewCard">
    <p class="lead reviewPrompt"><?= h((string)$question['prompt']) ?></p>

    <ul class="choices reviewChoices">
      <?php foreach (['A','B','C','D'] as $ch): ?>
        <?php
          $text = '';
          if (isset($question['choices'][$ch])) $text = (string)$question['choices'][$ch];

          $isRightChoice = ($ch === $correct);
          $isPicked = ($hasAnswer && $ch === $given);

          $liClass = "choice";
          $hint = "";

          // show correct choice (green highlight)
          if ($isRightChoice) {
            $liClass .= " choice--right";
            $hint = "Correct answer";
          }

          // show what student picked
          if ($isPicked && $isCorrect) {
            $liClass .= " choice--picked-right";
            $hint = "Your answer";
          }
          if ($isPicked && !$isCorrect) {
            $liClass .= " choice--picked-wrong";
            $hint = "Your answer";
          }
        ?>

        <li class="<?= h($liClass) ?>">
          <div class="choice__letter"><?= h($ch) ?>.</div>

          <div class="choice__body">
            <div class="choice__text"><?= h($text) ?></div>

            <?php if ($hint !== ""): ?>
              <div class="choice__hint"><?= h($hint) ?></div>
            <?php endif; ?>
          </div>

          <div class="choice__mark">
            <?php if ($isRightChoice): ?>
              <span class="mark mark--ok">✓</span>
            <?php elseif ($isPicked && !$isCorrect): ?>
              <span class="mark mark--bad">✕</span>
            <?php else: ?>
              <span class="mark mark--empty"></span>
            <?php endif; ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="reviewSummary muted">
      Your Answer: <strong><?= h($hasAnswer ? $given : '—') ?></strong>
      <?php if (!$isCorrect): ?>
        • Correct Answer: <strong><?= h($correct) ?></strong>
      <?php endif; ?>
    </div>
  </div>

  <!-- nav -->
  <div class="reviewNav">
    <a class="btn btn--ghost reviewNav__prev <?= $q <= 1 ? 'is-disabled' : '' ?>"
      href="review.php?q=<?= (int)$prevQ ?>"
      <?= $q <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' ?>>← Prev</a>

    <div class="reviewNav__mid">
      <span class="badge">Item <?= (int)$q ?> / <?= (int)$totalItems ?></span>
    </div>

    <a class="btn btn--primary reviewNav__next <?= $q >= $totalItems ? 'is-disabled' : '' ?>"
      href="review.php?q=<?= (int)$nextQ ?>"
      <?= $q >= $totalItems ? 'aria-disabled="true" tabindex="-1"' : '' ?>>Next →</a>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
