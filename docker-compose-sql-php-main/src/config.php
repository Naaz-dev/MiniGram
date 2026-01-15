<?php
session_start();

$servername = "mysql";
$dbusername = "root";
$dbpassword = "password";
$database = "tp2";

$conn = new mysqli($servername, $dbusername, $dbpassword, $database);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Helpers
function now_string() {
    // Your schema uses VARCHAR(45) for time; store ISO-like string
    return date("Y-m-d H:i:s");
}

function require_login() {
    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
        exit;
    }
}

function h($str) {
    return htmlspecialchars($str ?? "", ENT_QUOTES, "UTF-8");
}