<?php
$host = 'localhost';
$user = 'root'; // Change for production
$pass = ''; // Change for production
$dbname = 'UPLOAD.DOC';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>