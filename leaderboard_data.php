<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
session_start();

$conn = db();

$rows = $conn->query("
  SELECT 
    s.id,
    CONCAT(s.first_name, ' ', s.last_name) AS name,
    COALESCE(a.total_score, 0) AS total_score,
    COALESCE(a.percent, 0) AS percent,
    COALESCE(a.score_mcq, 0) AS score_mcq,
    COALESCE(a.score_ident, 0) AS score_ident,
    COALESCE(a.time_seconds, 0) AS time_seconds,
    COALESCE(a.submitted, 0) AS submitted,
    COALESCE(a.restricted, 0) AS restricted,
    a.created_at
  FROM students s
  LEFT JOIN attempts a ON a.student_id = s.id
  ORDER BY 
    (a.submitted = 1) DESC,
    (COALESCE(a.restricted,0) = 0) DESC,
    total_score DESC,
    percent DESC,
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

$dist = array_fill(0, 31, 0);
foreach ($allScores as $r) {
  $t = (int)$r['total_score'];
  if ($t >= 0 && $t <= 30) $dist[$t]++;
}

$submittedOnly = array_values(array_filter($rows, function($r){
  return (int)$r['submitted'] === 1;
}));

$top3 = array_slice($submittedOnly, 0, 3);

header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
  'rows' => array_map(function($r){
    return [
      'name' => $r['name'],
      'total_score' => (int)$r['total_score'],
      'percent' => (int)$r['percent'],
      'score_mcq' => (int)$r['score_mcq'],
      'score_ident' => (int)$r['score_ident'],
      'time_seconds' => (int)$r['time_seconds'],
      'submitted' => (int)$r['submitted'],
      'restricted' => (int)$r['restricted'],
      'created_at' => $r['created_at'],
    ];
  }, $rows),
  'top3' => array_map(function($r){
    return [
      'name' => $r['name'],
      'total_score' => (int)$r['total_score'],
      'time_seconds' => (int)$r['time_seconds'],
    ];
  }, $top3),
  'dist' => $dist,
  'server_time' => time()
]);
