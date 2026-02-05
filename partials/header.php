<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

if (!function_exists('h')) {
  function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= h(APP_NAME) ?></title>

  <link rel="stylesheet" href="assets/css/app.css" />
</head>
<body>

<header class="topbar">
  <div class="brand">
    <img class="brand__logo" src="assets/img/basc-logo.jpg" alt="BASC Logo" />
    <div class="brand__text">
      <div class="brand__title"><?= h(APP_NAME) ?></div>
      <div class="brand__subtitle">Quiz Mode • Ethics and Morality</div>
    </div>
  </div>

  <nav class="topbar__nav">
    <a class="navlink" href="index.php">Home</a>
    <a class="navlink" href="leaderboard.php">Leaderboard</a>
  </nav>
</header>

<main class="container">
