<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
session_start();

if (empty($_SESSION['is_teacher'])) {
  header("Location: login.php");
  exit;
}

$conn = db();

$quiz_id = (int)($_GET['quiz_id'] ?? 0);
if ($quiz_id <= 0) {
  header("Location: dashboard.php");
  exit;
}

$qz = $conn->prepare("SELECT * FROM quizzes WHERE id=? LIMIT 1");
$qz->bind_param("i", $quiz_id);
$qz->execute();
$quiz = $qz->get_result()->fetch_assoc();

if (!$quiz) {
  header("Location: dashboard.php");
  exit;
}

$err = '';
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'add_question') {
    $q_type = $_POST['q_type'] ?? 'mcq';
    $question_text = trim($_POST['question_text'] ?? '');
    $points = (int)($_POST['points'] ?? 1);
    if ($points < 1) $points = 1;
    if ($points > 20) $points = 20;

    if ($question_text === '') {
      $err = 'Question text is required.';
    } else {
      $posRes = $conn->prepare("SELECT COALESCE(MAX(position),0)+1 AS p FROM quiz_questions WHERE quiz_id=?");
      $posRes->bind_param("i", $quiz_id);
      $posRes->execute();
      $pos = (int)($posRes->get_result()->fetch_assoc()['p'] ?? 1);

      $answer_text = null;
      if ($q_type === 'ident') {
        $answer_text = trim($_POST['answer_text'] ?? '');
        if ($answer_text === '') {
          $err = 'Identification answer is required.';
        }
      }

      if ($err === '') {
        $ins = $conn->prepare("
          INSERT INTO quiz_questions (quiz_id, q_type, question_text, points, answer_text, position)
          VALUES (?, ?, ?, ?, ?, ?)
        ");
        $ins->bind_param("issisi", $quiz_id, $q_type, $question_text, $points, $answer_text, $pos);
        $ins->execute();
        $qid = (int)$conn->insert_id;

        if ($q_type === 'mcq') {
          $choices = $_POST['choice'] ?? [];
          $correct = (int)($_POST['correct'] ?? -1);

          $filtered = [];
          foreach ($choices as $c) {
            $c = trim((string)$c);
            if ($c !== '') $filtered[] = $c;
          }

          if (count($filtered) < 2) {
            $err = 'MCQ needs at least 2 choices.';
            $conn->query("DELETE FROM quiz_questions WHERE id=" . $qid);
          } else {
            $cins = $conn->prepare("
              INSERT INTO quiz_choices (question_id, choice_text, is_correct, position)
              VALUES (?, ?, ?, ?)
            ");
            foreach ($filtered as $i => $text) {
              $is_correct = ($i === $correct) ? 1 : 0;
              $pos = $i + 1;
              $cins->bind_param("isii", $qid, $text, $is_correct, $pos);
              $cins->execute();
            }
            $ok = 'Question added.';
          }
        } else {
          $ok = 'Question added.';
        }
      }
    }
  }

  if ($action === 'publish') {
    $pub = $conn->prepare("UPDATE quizzes SET is_published=1 WHERE id=?");
    $pub->bind_param("i", $quiz_id);
    $pub->execute();
    $ok = 'Quiz published. You can share the link now.';
    $quiz['is_published'] = 1;
  }

  if ($action === 'unpublish') {
    $pub = $conn->prepare("UPDATE quizzes SET is_published=0 WHERE id=?");
    $pub->bind_param("i", $quiz_id);
    $pub->execute();
    $ok = 'Quiz set to draft.';
    $quiz['is_published'] = 0;
  }

  if ($action === 'delete_question') {
    $qid = (int)($_POST['question_id'] ?? 0);
    if ($qid > 0) {
      $del = $conn->prepare("DELETE FROM quiz_questions WHERE id=? AND quiz_id=?");
      $del->bind_param("ii", $qid, $quiz_id);
      $del->execute();
      $ok = 'Question deleted.';
    }
  }
}

$qs = $conn->prepare("
  SELECT id, q_type, question_text, points, answer_text, position
  FROM quiz_questions
  WHERE quiz_id=?
  ORDER BY position ASC, id ASC
");
$qs->bind_param("i", $quiz_id);
$qs->execute();
$questions = $qs->get_result()->fetch_all(MYSQLI_ASSOC);

function h2($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$share_link = "quiz.php?code=" . $quiz['share_code'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz Builder • <?= h2($quiz['title']) ?></title>
  <link rel="stylesheet" href="../assets/css/leaderboard.css">
</head>
<body>
<main class="wrap">
  <header class="head">
    <div>
      <h1 class="title">Quiz Builder</h1>
      <p class="sub"><?= h2($quiz['title']) ?> • Code: <strong><?= h2($quiz['share_code']) ?></strong></p>
    </div>
    <div class="actions">
      <a class="btn btn--ghost" href="dashboard.php">Back</a>
      <a class="btn btn--primary" href="../<?= $share_link ?>" target="_blank">Open Link</a>
    </div>
  </header>

  <section class="card">
    <div class="row between">
      <div>
        <h2 class="h2">Share Link</h2>
        <div class="muted">Send this to students</div>
      </div>
      <div class="pill"><?= h2($share_link) ?></div>
    </div>

    <div class="row between" style="margin-top:12px; gap:10px; flex-wrap:wrap;">
      <?php if ((int)$quiz['is_published'] === 1): ?>
        <form method="post">
          <input type="hidden" name="action" value="unpublish">
          <button class="btn btn--ghost" type="submit">Unpublish</button>
        </form>
        <div class="muted">Status: <strong>Published</strong></div>
      <?php else: ?>
        <form method="post">
          <input type="hidden" name="action" value="publish">
          <button class="btn btn--primary" type="submit">Publish</button>
        </form>
        <div class="muted">Status: <strong>Draft</strong></div>
      <?php endif; ?>
    </div>

    <?php if ($err): ?>
      <div class="alert" style="margin-top:12px;"><?= h2($err) ?></div>
    <?php elseif ($ok): ?>
      <div class="alert" style="margin-top:12px;"><?= h2($ok) ?></div>
    <?php endif; ?>
  </section>

  <section class="grid" style="margin-top:14px;">
    <div class="card">
      <h2 class="h2">Add Question</h2>

      <form method="post" style="margin-top:12px;">
        <input type="hidden" name="action" value="add_question">

        <div class="field" style="margin-bottom:10px;">
          <label>Type</label>
          <select name="q_type" id="qType"
            style="width:220px; padding:10px 12px; border-radius:12px; border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.04); color:rgba(255,255,255,.92); outline:none;">
            <option value="mcq">Multiple Choice</option>
            <option value="ident">Identification</option>
          </select>
        </div>

        <div class="field" style="margin-bottom:10px;">
          <label>Question</label>
          <input name="question_text" required
            style="width:100%; padding:10px 12px; border-radius:12px; border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.04); color:rgba(255,255,255,.92); outline:none;">
        </div>

        <div class="field" style="margin-bottom:10px;">
          <label>Points</label>
          <input type="number" name="points" value="1" min="1" max="20"
            style="width:120px; padding:10px 12px; border-radius:12px; border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.04); color:rgba(255,255,255,.92); outline:none;">
        </div>

        <div id="mcqBox">
          <div class="muted small" style="margin:6px 0 8px;">MCQ choices (mark the correct one)</div>

          <div style="display:grid; gap:8px;">
            <?php for ($i=0;$i<4;$i++): ?>
              <div style="display:flex; gap:8px; align-items:center;">
                <input type="radio" name="correct" value="<?= $i ?>" <?= $i===0 ? 'checked' : '' ?>>
                <input name="choice[]" placeholder="Choice <?= $i+1 ?>"
                  style="flex:1; padding:10px 12px; border-radius:12px; border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.04); color:rgba(255,255,255,.92); outline:none;">
              </div>
            <?php endfor; ?>
          </div>
        </div>

        <div id="identBox" style="display:none;">
          <div class="field" style="margin-top:10px;">
            <label>Correct Answer</label>
            <input name="answer_text"
              style="width:100%; padding:10px 12px; border-radius:12px; border:1px solid rgba(255,255,255,.12); background:rgba(255,255,255,.04); color:rgba(255,255,255,.92); outline:none;">
          </div>
        </div>

        <div style="margin-top:12px;">
          <button class="btn btn--primary" type="submit">Add Question</button>
        </div>
      </form>
    </div>

    <div class="card">
      <h2 class="h2">Questions</h2>

      <div class="table-scroll" style="margin-top:12px;">
        <table class="lb" style="min-width:760px;">
          <thead>
            <tr>
              <th>#</th>
              <th>Type</th>
              <th>Question</th>
              <th>Points</th>
              <th>Answer</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$questions): ?>
              <tr><td class="empty" colspan="6">No questions yet.</td></tr>
            <?php else: ?>
              <?php foreach ($questions as $q): ?>
                <tr>
                  <td><?= (int)$q['position'] ?></td>
                  <td class="muted"><?= h2($q['q_type']) ?></td>
                  <td><?= h2($q['question_text']) ?></td>
                  <td><span class="pill"><?= (int)$q['points'] ?></span></td>
                  <td class="muted"><?= $q['q_type']==='ident' ? h2($q['answer_text']) : '—' ?></td>
                  <td>
                    <form method="post" onsubmit="return confirm('Delete this question?')">
                      <input type="hidden" name="action" value="delete_question">
                      <input type="hidden" name="question_id" value="<?= (int)$q['id'] ?>">
                      <button class="btn btn--ghost" type="submit">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</main>

<script>
  const qType = document.getElementById('qType');
  const mcqBox = document.getElementById('mcqBox');
  const identBox = document.getElementById('identBox');

  function syncType(){
    const t = qType.value;
    mcqBox.style.display = (t === 'mcq') ? '' : 'none';
    identBox.style.display = (t === 'ident') ? '' : 'none';
  }
  qType.addEventListener('change', syncType);
  syncType();
</script>
</body>
</html>
