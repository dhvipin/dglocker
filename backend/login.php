<?php
session_start();
require "db.php";

// Get POST data
$email = trim($_POST["email"]);
$password = $_POST["password"]; // raw password from form

// Prepare statement to get user by email
$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows !== 1) {
    die("User not found. Please sign up.");
}

$user = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $user["password"])) {
    die("Incorrect password.");
}

// Set session variables
$_SESSION["user_id"] = $user["id"];
$_SESSION["user_name"] = $user["name"];

// Redirect to dashboard
header("Location: ../dashboard.php");
exit;
?>
