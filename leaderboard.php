<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
session_start();

$conn = db();

if (!function_exists('h')) {
  function h($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
}

$MAX_SCORE = 15;

$rows = $conn->query("
  SELECT 
    s.id,
    s.first_name,
    s.last_name,
    COALESCE(a.total_score, 0) AS total_score,
    COALESCE(a.score_mcq, 0) AS score_mcq,
    COALESCE(a.time_seconds, 0) AS time_seconds,
    COALESCE(a.submitted, 0) AS submitted,
    a.created_at
  FROM students s
  LEFT JOIN attempts a ON a.student_id = s.id
  ORDER BY 
    (a.submitted = 1) DESC,
    total_score DESC,
    time_seconds ASC,
    a.created_at ASC,
    s.last_name ASC,
    s.first_name ASC
")->fetch_all(MYSQLI_ASSOC);

$allScores = $conn->query("
  SELECT total_score
  FROM attempts
  WHERE submitted = 1
")->fetch_all(MYSQLI_ASSOC);

$dist = array_fill(0, $MAX_SCORE + 1, 0);
foreach ($allScores as $r) {
  $t = (int)$r['total_score'];
  if ($t >= 0 && $t <= $MAX_SCORE) $dist[$t]++;
}

function full_name(array $r) {
  return trim(($r['first_name']) . ' ' . ($r['last_name']));
}

$submittedOnly = array_values(array_filter($rows, function($r){
  return (int)($r['submitted']) === 1;
}));

$top3 = array_slice($submittedOnly, 0, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QUIZORA • Leaderboard</title>
  <link rel="stylesheet" href="assets/css/leaderboard.css">
</head>
<body>
  <main class="wrap">
    <header class="head">
      <div>
        <h1 class="title">Leaderboard</h1>
        <p class="sub" id="lbStatus">Live updates enabled</p>
      </div>

      <div class="actions">
        <a class="btn btn-ghost" href="export_leaderboard.php">Export CSV</a>
        <a class="btn btn-primary" href="index.php">Home</a>
      </div>
    </header>

    <section class="podium" id="podium">
      <?php if ($top3): ?>
        <?php for ($k = 0; $k < min(3, count($top3)); $k++): ?>
          <?php
            $r = $top3[$k];
            $pct = $MAX_SCORE > 0 ? (int)round(((int)$r['total_score'] / $MAX_SCORE) * 100) : 0;
          ?>
          <article class="podium-card podium-<?= $k+1 ?>">
            <div class="medal"><?= $k===0 ? "🥇" : ($k===1 ? "🥈" : "🥉") ?></div>
            <div class="pname"><?= h(full_name($r)) ?></div>
            <div class="pmeta">
              <span class="pscore"><strong><?= (int)$r['total_score'] ?></strong>/<?= $MAX_SCORE ?></span>
              <span class="dot">•</span>
              <span class="ptime"><?= (int)$r['time_seconds'] ?>s</span>
              <span class="dot">•</span>
              <span class="ppct"><strong><?= $pct ?>%</strong></span>
            </div>
          </article>
        <?php endfor; ?>
      <?php endif; ?>
    </section>

    <section class="grid">
      <div class="card">
        <div class="card-head">
          <h2 class="h2">Rankings</h2>
          <div class="hint">Swipe table ↔️ to see all columns</div>
        </div>

        <div style="margin:10px 0 12px;">
          <input id="lbSearch" type="search" placeholder="Search student name..." autocomplete="off"
                 style="width:100%; padding:10px 12px; border-radius:12px; border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.04); color:rgba(255,255,255,.92); outline:none;">
        </div>

        <div class="table-scroll" tabindex="0" aria-label="Leaderboard table scroll area">
          <table class="lb">
            <thead>
              <tr>
                <th class="c-rank">#</th>
                <th class="c-name">Name</th>
                <th class="c-total">Total</th>
                <th class="c-pct">%</th>
                <th class="c-mcq">MCQ</th>
                <th class="c-time">Time</th>
                <th class="c-date">Submitted</th>
                <th class="c-status">Status</th>
              </tr>
            </thead>

            <tbody id="lbBody">
              <?php if (!$rows): ?>
                <tr><td class="empty" colspan="8">No students yet.</td></tr>
              <?php else: ?>
                <?php foreach ($rows as $i => $r): ?>
                  <?php
                    $rank = $i + 1;
                    $submitted = (int)($r['submitted']) === 1;
                    $submittedText = $r['created_at'] ? date("M d, Y h:i A", strtotime($r['created_at'])) : '';
                    $status = $submitted ? "Submitted" : "Not yet";
                    $rowClass = $submitted ? (($rank <= 5) ? "top5 top5-{$rank}" : "") : "not-submitted";
                    $pct = $MAX_SCORE > 0 ? (int)round(((int)$r['total_score'] / $MAX_SCORE) * 100) : 0;
                  ?>
                  <tr class="<?= h($rowClass) ?>" data-name="<?= h(strtolower(full_name($r))) ?>">
                    <td class="c-rank"><?= $rank ?></td>
                    <td class="c-name"><?= h(full_name($r)) ?></td>
                    <td class="c-total"><strong><?= (int)$r['total_score'] ?></strong>/<?= $MAX_SCORE ?></td>
                    <td class="c-pct"><strong><?= $pct ?>%</strong></td>
                    <td class="c-mcq"><?= (int)$r['score_mcq'] ?>/<?= $MAX_SCORE ?></td>
                    <td class="c-time"><span class="pill"><?= (int)$r['time_seconds'] ?>s</span></td>
                    <td class="c-date"><span class="muted"><?= h($submittedText) ?></span></td>
                    <td class="c-status"><span class="muted"><?= h($status) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <p class="note">Search filters by name. “Not yet” means the student has no submitted attempt.</p>
      </div>

      <div class="card">
        <h2 class="h2">Score Distribution</h2>
        <div class="chart-box">
          <canvas id="chartDist"></canvas>
        </div>
        <p class="note">Distribution counts only submitted attempts.</p>
      </div>
    </section>

    <footer class="foot">
      <span class="muted">QUIZORA • Leaderboard</span>
    </footer>
  </main>

  <script src="assets/js/chart.js"></script>
  <script>
    const MAX_SCORE = <?= (int)$MAX_SCORE ?>;
    const labels = [...Array(MAX_SCORE + 1).keys()].map(String);

    const chart = new Chart(document.getElementById('chartDist'), {
      type: 'line',
      data: { labels, datasets: [{ label: 'Students', data: <?= json_encode($dist) ?>, tension: 0.25 }] },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
      }
    });

    const podiumEl = document.getElementById('podium');
    const bodyEl = document.getElementById('lbBody');
    const statusEl = document.getElementById('lbStatus');
    const searchEl = document.getElementById('lbSearch');

    function escapeHtml(s){
      return String(s)
        .replaceAll('&','&amp;')
        .replaceAll('<','&lt;')
        .replaceAll('>','&gt;')
        .replaceAll('"','&quot;')
        .replaceAll("'",'&#039;');
    }

    function formatDate(dt) {
      if (!dt) return '';
      const d = new Date(dt.replace(' ', 'T'));
      if (isNaN(d.getTime())) return dt;
      return d.toLocaleString(undefined, { year:'numeric', month:'short', day:'2-digit', hour:'2-digit', minute:'2-digit' });
    }

    function renderPodium(top3){
      if (!podiumEl) return;
      if (!top3 || top3.length === 0){
        podiumEl.innerHTML = '';
        return;
      }
      podiumEl.innerHTML = top3.slice(0,3).map((p, idx) => {
        const k = idx + 1;
        const m = k===1 ? "🥇" : (k===2 ? "🥈" : "🥉");
        const pct = MAX_SCORE ? Math.round((Number(p.total_score)||0) / MAX_SCORE * 100) : 0;
        return `
          <article class="podium-card podium-${k}">
            <div class="medal">${m}</div>
            <div class="pname">${escapeHtml(p.name)}</div>
            <div class="pmeta">
              <span class="pscore"><strong>${Number(p.total_score)||0}</strong>/${MAX_SCORE}</span>
              <span class="dot">•</span>
              <span class="ptime">${Number(p.time_seconds)||0}s</span>
              <span class="dot">•</span>
              <span class="ppct"><strong>${pct}%</strong></span>
            </div>
          </article>
        `;
      }).join('');
    }

    function applySearch(){
      const q = (searchEl?.value || '').trim().toLowerCase();
      const trs = bodyEl.querySelectorAll('tr[data-name]');
      trs.forEach(tr => {
        const name = tr.getAttribute('data-name') || '';
        tr.style.display = name.includes(q) ? '' : 'none';
      });
    }

    searchEl?.addEventListener('input', applySearch);

    function renderRows(rows){
      if (!rows || rows.length === 0){
        bodyEl.innerHTML = `<tr><td class="empty" colspan="8">No students yet.</td></tr>`;
        return;
      }

      bodyEl.innerHTML = rows.map((r, i) => {
        const rank = i + 1;
        const submitted = Number(r.submitted) === 1;
        const status = submitted ? "Submitted" : "Not yet";
        const rowClass = submitted ? (rank <= 5 ? `top5 top5-${rank}` : '') : 'not-submitted';
        const pct = MAX_SCORE ? Math.round((Number(r.total_score)||0) / MAX_SCORE * 100) : 0;

        return `
          <tr class="${rowClass}" data-name="${escapeHtml(String(r.name || '').toLowerCase())}">
            <td class="c-rank">${rank}</td>
            <td class="c-name">${escapeHtml(r.name || '')}</td>
            <td class="c-total"><strong>${Number(r.total_score)||0}</strong>/${MAX_SCORE}</td>
            <td class="c-pct"><strong>${pct}%</strong></td>
            <td class="c-mcq">${Number(r.score_mcq)||0}/${MAX_SCORE}</td>
            <td class="c-time"><span class="pill">${Number(r.time_seconds)||0}s</span></td>
            <td class="c-date"><span class="muted">${escapeHtml(formatDate(r.created_at || ''))}</span></td>
            <td class="c-status"><span class="muted">${status}</span></td>
          </tr>
        `;
      }).join('');

      applySearch();
    }

    let isFetching = false;

    async function refreshLeaderboard(){
      if (isFetching) return;
      isFetching = true;

      try{
        const res = await fetch('leaderboard_data.php', { cache: 'no-store' });
        if (!res.ok) throw new Error('Fetch failed');
        const data = await res.json();

        renderPodium(data.top3 || []);
        renderRows(data.rows || []);

        chart.data.datasets[0].data = data.dist || [];
        chart.update();

        if (statusEl){
          const t = new Date((data.server_time || Date.now()/1000) * 1000);
          statusEl.textContent = "Live updates • Last check: " + t.toLocaleTimeString();
        }
      }catch(e){
        if (statusEl) statusEl.textContent = "Live updates • Connection issue";
      }finally{
        isFetching = false;
      }
    }

    setInterval(refreshLeaderboard, 1000);
  </script>
</body>
</html>
