<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';

session_start();

$errors = [];
$first = '';
$last  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = trim($_POST['first_name']);
  $last  = trim($_POST['last_name']);

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

<main class="container">
  <section class="card" style="max-width: 720px; margin: 0 auto;">
    <div>
      <h1 class="h1">Report 1 Quiz</h1>
      <p class="lead">15 Multiple Questions about the Ethics and Morality. One attempt only.</p>

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

      <form method="post" class="form" action="">
        <div class="field" style="margin-bottom:14px;">
          <label for="first_name">First Name</label>
          <input id="first_name" name="first_name" placeholder="Your Name" value="<?= h($first) ?>" autocomplete="given-name" required />
        </div>

        <div class="field" style="margin-bottom:14px;">
          <label for="last_name">Last Name</label>
          <input id="last_name" name="last_name" placeholder="Your Surname" value="<?= h($last) ?>" autocomplete="family-name" required />
        </div>

        <div class="form__actions" style="justify-content:stretch;">
          <button class="btn btn--primary" type="submit" style="width:100%;">
            Continue →
            <span class="btn__shine"></span>
          </button>
        </div>
      </form>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
