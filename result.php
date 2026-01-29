<?php
// result.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
session_start();

if (!isset($_SESSION['attempt_id'])) {
  header("Location: index.php");
  exit;
}

$conn = db();
$attempt_id = (int)$_SESSION['attempt_id'];

$stmt = $conn->prepare("
  SELECT a.*, s.first_name, s.last_name
  FROM attempts a
  JOIN students s ON s.id = a.student_id
  WHERE a.id=? LIMIT 1
");
$stmt->bind_param("i", $attempt_id);
$stmt->execute();
$attempt = $stmt->get_result()->fetch_assoc();

if (!$attempt) {
  header("Location: index.php");
  exit;
}

require __DIR__ . '/partials/header.php';
?>

<section class="card">
  <div class="row between">
    <div>
      <h2 class="h2">Results</h2>
      <div class="muted">
        <?= h($attempt['first_name'] . " " . $attempt['last_name']) ?> • <?= h($attempt['created_at']) ?>
      </div>
    </div>
    <div class="scorebadge pop">
      <div class="scorebadge__big"><?= (int)$attempt['total_score'] ?></div>
      <div class="scorebadge__small">/ 30</div>
    </div>
  </div>

  <div class="compliment">
    <div class="compliment__spark">✨</div>
    <div>
      <div class="muted small">Cute compliment</div>
      <div class="compliment__text"><?= h(isset($attempt['compliment']) ? $attempt['compliment'] : "Nice work!") ?></div>
    </div>
  </div>

  <div class="grid2 gap">
    <div class="panel">
      <h3 class="h3">Breakdown</h3>
      <ul class="kv">
        <li><span>MCQ</span><span><strong><?= (int)$attempt['score_mcq'] ?></strong>/20</span></li>
        <li><span>Identification</span><span><strong><?= (int)$attempt['score_ident'] ?></strong>/10</span></li>
        <li><span>Time</span><span><strong><?= (int)$attempt['time_seconds'] ?></strong> sec</span></li>
      </ul>
      <a class="btn btn--ghost" href="leaderboard.php">Leaderboard</a>
    </div>

    <div class="panel">
      <h3 class="h3">Graphs</h3>
      <canvas id="chartA" height="170"></canvas>
      <div style="height:10px"></div>
      <canvas id="chartB" height="170"></canvas>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const mcq = <?= (int)$attempt['score_mcq'] ?>;
  const ident = <?= (int)$attempt['score_ident'] ?>;

  new Chart(document.getElementById('chartA'), {
    type: 'doughnut',
    data: { labels: [' MCQ: ', 'Identification: '], datasets: [{ data: [mcq, ident] }] },
    options: { plugins: { legend: { position: 'bottom' } } }
  });

  new Chart(document.getElementById('chartB'), {
    type: 'bar',
    data: { labels: [' MCQ: ', ' Identification'], datasets: [{ label: ' Score', data: [mcq, ident] }] },
    options: {
      plugins: { legend: { position: 'bottom' } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>