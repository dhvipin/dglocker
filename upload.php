<?php
session_start();
require "backend/db.php";

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to upload files.");
}

$userId = $_SESSION["user_id"];

$category = $_POST['category'] ?? '';
$tags = $_POST['tags'] ?? '';

$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['documents'])) {
    foreach ($_FILES['documents']['name'] as $i => $name) {

        $tmp = $_FILES['documents']['tmp_name'][$i];
        $size = $_FILES['documents']['size'][$i];
        $type = $_FILES['documents']['type'][$i];

        $newName = time() . "_" . basename($name);
        $path = $uploadDir . $newName;

        if (move_uploaded_file($tmp, $path)) {

            $stmt = $conn->prepare("
                INSERT INTO documents 
                (file_name, file_type, file_size, file_path, category, tags, user_id, uploaded_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->bind_param(
                "ssisssi",
                $name,
                $type,
                $size,
                $path,
                $category,
                $tags,
                $userId
            );

            $stmt->execute();
        }
    }
}

// Redirect to dashboard after upload
header("Location: dashboard.php");
exit;
