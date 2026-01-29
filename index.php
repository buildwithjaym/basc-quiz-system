<?php
// index.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';

session_start();

$errors = [];
$first = '';
$last  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = trim($_POST['first_name']);
  $last  = trim($_POST['last_name'] );

  if ($first === '' || $last === '') $errors[] = "Enter first and last name.";
  if (mb_strlen($first) > 50 || mb_strlen($last) > 50) $errors[] = "Name too long.";
  if (!preg_match('/^[\p{L}\s\.\'-]+$/u', $first) || !preg_match('/^[\p{L}\s\.\'-]+$/u', $last)) {
    $errors[] = "Letters/spaces only (.,',- allowed).";
  }

  if (!$errors) {
    $conn = db();
    $stmt = $conn->prepare("
      INSERT INTO students(first_name,last_name)
      VALUES(?,?)
      ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)
    ");
    $stmt->bind_param("ss", $first, $last);
    $stmt->execute();

    $student_id = (int)$conn->insert_id;

    $_SESSION['student_id'] = $student_id;
    $_SESSION['student_name'] = $first . " " . $last;

    header("Location: start.php");
    exit;
  }
}
?>

<section class="card hero">
  <div>
    <h1 class="h1">Report 1 Quiz</h1>
    <p class="lead">20 Multiple Questions + 10 logo/tool identification. One attempt only.</p>

    <?php if ($errors): ?>
      <div class="alert">
        <strong>Fix:</strong>
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= h($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" class="form grid2">
      <div class="field">
        <label>First Name</label>
        <input name="first_name" value="<?= h($first) ?>" required />
      </div>

      <div class="field">
        <label>Last Name</label>
        <input name="last_name" value="<?= h($last) ?>" required />
      </div>

      <div class="form__actions">
        <button class="btn btn--primary" type="submit">
          Continue →
          <span class="btn__shine"></span>
        </button>
        <a class="btn btn--ghost" href="leaderboard.php">Leaderboard</a>
      </div>
    </form>

    
  </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
