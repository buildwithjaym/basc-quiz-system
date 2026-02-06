<?php
// result.php
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

$stmt = $conn->prepare("
  SELECT a.*, s.first_name, s.last_name
  FROM attempts a
  JOIN students s ON s.id = a.student_id
  WHERE a.id=? AND a.student_id=? LIMIT 1
");
$stmt->bind_param("ii", $attempt_id, $student_id);
$stmt->execute();
$attempt = $stmt->get_result()->fetch_assoc();

if (!$attempt) { header("Location: index.php"); exit; }
if ((int)$attempt['submitted'] !== 1) { header("Location: quiz.php"); exit; }

$bank = get_question_bank();
$MAX_SCORE = (isset($bank['mcq']) && is_array($bank['mcq'])) ? count($bank['mcq']) : 15;

$score = (int)$attempt['score_mcq'];
$timeS = (int)$attempt['time_seconds'];
$percent = isset($attempt['percent']) ? (int)$attempt['percent'] : 0;

require __DIR__ . '/partials/header.php';
?>

<section class="card result">
  <div class="result__top">
    <div class="result__title">
      <h2 class="h2">Results</h2>
      <div class="muted">
        <?= h($attempt['first_name'] . " " . $attempt['last_name']) ?> • <?= h($attempt['created_at']) ?>
      </div>
    </div>

    <div class="scorebadge pop">
      <div class="scorebadge__big"><?= (int)$attempt['total_score'] ?></div>
      <div class="scorebadge__small">/ <?= (int)$MAX_SCORE ?></div>
      <div class="scorebadge__pct"><?= (int)$percent ?>%</div>
    </div>
  </div>

  <div class="compliment">
    <div class="compliment__spark">✨</div>
    <div>
      <div class="muted small">Cute compliment</div>
      <div class="compliment__text"><?= h((string)$attempt['compliment']) ?></div>
    </div>
  </div>

  <div class="result__grid">
    <div class="panel result__panel">
      <h3 class="h3">Breakdown</h3>

      <ul class="kv">
        <li><span>MCQ</span><span><strong><?= (int)$score ?></strong>/<?= (int)$MAX_SCORE ?></span></li>
        <li><span>Percent</span><span><strong><?= (int)$percent ?>%</strong></span></li>
        <li><span>Time</span><span><strong><?= (int)$timeS ?></strong> sec</span></li>
      </ul>

      <div class="result__actions">
        <a class="btn btn--primary" href="review.php">Check Answers</a>
       
      </div>
    </div>

    <div class="panel result__panel">
      <h3 class="h3">Graphs</h3>

      <div class="result__charts">
        <div class="chartbox"><canvas id="chartA"></canvas></div>
        <div class="chartbox"><canvas id="chartB"></canvas></div>
      </div>

      <p class="muted small result__hint">
        Score shown out of <?= (int)$MAX_SCORE ?> items.
      </p>
    </div>
  </div>
</section>

<script src="assets/js/chart.js"></script>
<script>
  const score = <?= (int)$score ?>;
  const maxScore = <?= (int)$MAX_SCORE ?>;
  const wrong = Math.max(0, maxScore - score);

  new Chart(document.getElementById('chartA'), {
    type: 'doughnut',
    data: { labels: ['Correct', 'Wrong'], datasets: [{ data: [score, wrong] }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
  });

  new Chart(document.getElementById('chartB'), {
    type: 'bar',
    data: { labels: ['Correct', 'Wrong'], datasets: [{ label: 'Count', data: [score, wrong] }] },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
