<?php
// restricted.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/restricted.css" />
    <link rel="icon" href="assets/img/remove_logo.png" type="image/png" />
    <title>Restricted</title>
</head>
<body>
    <audio id="errorSound" src="assets/error.mp3" type="audio/mpeg"></audio>
    <div class="icon blink">⚠️</div>
    <h1 id="restricted" class="blink">You have been restricted</h1>

    <script>
        // Play the error sound when the page loads
        window.addEventListener('load', function() {
            var sound = document.getElementById('errorSound');
            sound.play();
        });
    </script>
</body>
</html>