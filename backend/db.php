<?php
$host = "localhost";
$user = "root";        // change if needed
$pass = "root";            // change if needed
$db   = "docvault";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Database connection failed");
}
