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


$filename = "basc_leaderboard_" . date("Y-m-d_His") . ".csv";
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Pragma: no-cache');
header('Expires: 0');


echo "\xEF\xBB\xBF";

$out = fopen('php://output', 'w');

fputcsv($out, ['Rank', 'Name', 'Total (15)', 'Percent %','Questions (15)', 'Time (seconds)', 'Submitted At']);

$rank = 0;
foreach ($rows as $r) {
  $rank++;
  $name = trim($r['first_name'] . ' ' . $r['last_name']);

  fputcsv($out, [
    $rank,
    $name,
    (int)$r['total_score'],
    (int)$r['percent']. '%',
    (int)$r['score_mcq'],
    (int)$r['time_seconds'],
    $r['created_at'],
  ]);
}

fclose($out);
exit;
