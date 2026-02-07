<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
session_start();

$conn = db();

$rows = $conn->query("
  SELECT s.first_name, s.last_name,
         a.total_score, a.percent, a.score_mcq, a.score_ident, a.time_seconds, a.created_at
  FROM attempts a
  JOIN students s ON s.id = a.student_id
  WHERE a.submitted = 1
  ORDER BY a.total_score DESC, a.time_seconds ASC, a.created_at ASC
")->fetch_all(MYSQLI_ASSOC);

if (!function_exists('h')) {
  function h($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
}

$filename = "basc_leaderboard_" . date("Y-m-d_His") . ".xls";
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Pragma: no-cache');
header('Expires: 0');

echo "\xEF\xBB\xBF";
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    table { border-collapse: collapse; width: 100%; font-family: Calibri, Arial, sans-serif; }
    th, td { border: 1px solid #d9d9d9; padding: 8px 10px; white-space: nowrap; }
    th { background: #dc2626; color: #ffffff; font-weight: 700; text-align: center; }
    td.num { text-align: center; }
    td.name { min-width: 220px; }
  </style>
</head>
<body>
  <table>
    <thead>
      <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Total (15)</th>
        <th>Percent %</th>
        <th>Questions (15)</th>
        <th>Time (seconds)</th>
        <th>Submitted At</th>
      </tr>
    </thead>
    <tbody>
      <?php $rank = 0; foreach ($rows as $r): $rank++; ?>
        <tr>
          <td class="num"><?= $rank ?></td>
          <td class="name"><?= h(trim($r['first_name'].' '.$r['last_name'])) ?></td>
          <td class="num"><?= (int)$r['total_score'] ?></td>
          <td class="num"><?= (int)$r['percent'] ?>%</td>
          <td class="num"><?= (int)$r['score_mcq'] ?></td>
          <td class="num"><?= (int)$r['time_seconds'] ?></td>
          <td><?= h((string)$r['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
<?php exit; ?>
