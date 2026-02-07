<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
session_start();

header('Content-Type: application/json; charset=UTF-8');

if (empty($_SESSION['student_id']) || empty($_SESSION['attempt_id'])) {
  echo json_encode(['ok' => false]);
  exit;
}

$conn = db();
$attempt_id = (int)$_SESSION['attempt_id'];
$student_id = (int)$_SESSION['student_id'];

$upd = $conn->prepare("UPDATE attempts SET restricted=1 WHERE id=? AND student_id=? LIMIT 1");
$upd->bind_param("ii", $attempt_id, $student_id);
$upd->execute();

echo json_encode(['ok' => true]);
