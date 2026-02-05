<?php
// submit.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/question_bank.php';
require_once __DIR__ . '/compliments.php';
session_start();

header("Content-Type: application/json; charset=utf-8");

function json_fail($code, $msg) {
  http_response_code($code);
  echo json_encode(["ok" => false, "error" => $msg], JSON_UNESCAPED_UNICODE);
  exit;
}

if (empty($_SESSION['student_id']) || empty($_SESSION['attempt_id'])) {
  json_fail(401, "Not logged in");
}

$attempt_id = (int)$_SESSION['attempt_id'];
$student_id = (int)$_SESSION['student_id'];

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);
if (!is_array($data)) {
  json_fail(400, "Invalid JSON");
}

$time_seconds = isset($data["time_seconds"]) ? (int)$data["time_seconds"] : 0;
$answers = (isset($data["answers"]) && is_array($data["answers"])) ? $data["answers"] : [];

$conn = db();

$bank = get_question_bank();

$mcq_map = [];
foreach ($bank["mcq"] as $q) $mcq_map[$q["key"]] = $q["answer"];

$ident_map = [];
foreach ($bank["ident"] as $q) $ident_map[$q["key"]] = $q["answer"];

$MAX_SCORE = count($bank["mcq"]) + count($bank["ident"]);

function norm($s) {
  $s = mb_strtolower(trim((string)$s));
  $s = preg_replace('/[\s\-_]+/u', '', $s);
  return (string)$s;
}

$score_mcq = 0;
$score_ident = 0;

$conn->begin_transaction();

try {
  $chk = $conn->prepare("SELECT submitted FROM attempts WHERE id=? AND student_id=? LIMIT 1 FOR UPDATE");
  $chk->bind_param("ii", $attempt_id, $student_id);
  $chk->execute();
  $row = $chk->get_result()->fetch_assoc();

  if (!$row) json_fail(403, "Attempt not found");
  if ((int)$row["submitted"] === 1) json_fail(409, "Already submitted");

  $del = $conn->prepare("DELETE FROM attempt_answers WHERE attempt_id=?");
  $del->bind_param("i", $attempt_id);
  $del->execute();

  $ins = $conn->prepare("
    INSERT INTO attempt_answers(attempt_id, q_key, q_type, given_answer, is_correct)
    VALUES(?,?,?,?,?)
  ");

  foreach ($answers as $key => $given) {
    $q_key = (string)$key;
    $given = is_string($given) ? trim($given) : '';

    if (isset($mcq_map[$q_key])) {
      $q_type = "mcq";
      $is_correct = ($given === $mcq_map[$q_key]) ? 1 : 0;
      if ($is_correct) $score_mcq++;
    } elseif (isset($ident_map[$q_key])) {
      $q_type = "ident";
      $is_correct = (norm($given) === norm($ident_map[$q_key])) ? 1 : 0;
      if ($is_correct) $score_ident++;
    } else {
      continue;
    }

    $ins->bind_param("isssi", $attempt_id, $q_key, $q_type, $given, $is_correct);
    $ins->execute();
  }

  $total = $score_mcq + $score_ident;

  $percent = 0;
  if ($MAX_SCORE > 0) {
    $percent = (int)round(($total / $MAX_SCORE) * 100);
    if ($percent < 0) $percent = 0;
    if ($percent > 100) $percent = 100;
  }

  $compliment = random_compliment();

  $upd = $conn->prepare("
    UPDATE attempts
    SET score_mcq=?, score_ident=?, total_score=?, percent=?, time_seconds=?, submitted=1, compliment=?
    WHERE id=? AND student_id=?
  ");
  $upd->bind_param("iiiiisii", $score_mcq, $score_ident, $total, $percent, $time_seconds, $compliment, $attempt_id, $student_id);
  $upd->execute();

  if ($upd->affected_rows < 1) {
    json_fail(409, "Submit conflict. Please refresh and try again.");
  }

  $conn->commit();

  echo json_encode([
    "ok" => true,
    "total_score" => $total,
    "percent" => $percent,
    "compliment" => $compliment
  ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  $conn->rollback();
  json_fail(500, "Server error: " . $e->getMessage());
}
