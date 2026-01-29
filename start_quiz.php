<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
session_start();

$conn = db();

if (empty($_SESSION['student_id'])) {
  header("Location: index.php");
  exit;
}

$student_id = (int)$_SESSION['student_id'];

$find = $conn->prepare("
  SELECT id
  FROM attempts
  WHERE student_id=? AND submitted=0
  ORDER BY id DESC
  LIMIT 1
");
$find->bind_param("i", $student_id);
$find->execute();
$existing = $find->get_result()->fetch_assoc();

if ($existing) {
  $_SESSION['attempt_id'] = (int)$existing['id'];
  header("Location: quiz.php");
  exit;
}

$ins = $conn->prepare("INSERT INTO attempts (student_id, submitted) VALUES (?, 0)");
$ins->bind_param("i", $student_id);
$ins->execute();

$_SESSION['attempt_id'] = $ins->insert_id;

header("Location: quiz.php");
exit;
