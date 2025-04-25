<?php
// Function to open database connections
function openCon($server, $user, $pass, $db) {
    try {
        $connectionString = "mysql:host=$server;dbname=$db";

        return $conn = new PDO($connectionString, $user, $pass);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// Functions to close database connections
function closeCon($conn) {
    try {
        if ($conn != null) {
            return $conn = null;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>