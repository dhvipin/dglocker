<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../signup.html");
  exit;
}

$name     = trim($_POST["name"]);
$email    = trim($_POST["email"]);
$password = $_POST["password"];

/* Basic validation */
if (!$name || !$email || !$password) {
  die("All fields are required");
}

/* Check if email already exists */
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  die("Email already registered");
}

/* Hash password */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* Insert user */
$stmt = $conn->prepare(
  "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
  $_SESSION["user_id"] = $stmt->insert_id;
  $_SESSION["user_name"] = $name;

  header("Location: ../upload.html"); // redirect after signup
  exit;
} else {
  die("Signup failed, try again");
}
