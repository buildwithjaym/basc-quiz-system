<?php
require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db() {
    static $conn = null;
    if ($conn === null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $conn->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $conn;
}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

try {
    $connection = db();
    if ($connection->ping()) {
  
    }
} catch (mysqli_sql_exception $e) {
    die('Error connecting to the database: ' . htmlspecialchars($e->getMessage()));
}
?>