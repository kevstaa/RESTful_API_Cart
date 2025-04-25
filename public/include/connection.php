<?php
require_once (__DIR__ . "/../../src/db_lib.php");

// Database connections variables
$server = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$db = $_ENV['DB_NAME'];

$conn = null;
// Database connection
try {
    $conn = openCon($server, $user, $pass, $db);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>