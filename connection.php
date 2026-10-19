<?php
$servername = "sql106.infinityfree.com";
$username = "if0_43216778";
$password = "regiel2622";
$database = "if0_43216778_expensedb";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>