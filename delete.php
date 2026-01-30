<?php
session_start();
require "backend/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

if (!isset($_GET["id"])) {
    die("Invalid request");
}

$docId  = (int) $_GET["id"];
$userId = $_SESSION["user_id"];

/* Get file path first (to delete file from folder) */
$stmt = $conn->prepare(
    "SELECT file_path FROM documents WHERE id = ? AND user_id = ?"
);
$stmt->bind_param("ii", $docId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Document not found or access denied");
}

$file = $result->fetch_assoc();

/* Delete file from uploads folder */
if (file_exists($file["file_path"])) {
    unlink($file["file_path"]);
}

/* Delete record from database */
$stmt = $conn->prepare(
    "DELETE FROM documents WHERE id = ? AND user_id = ?"
);
$stmt->bind_param("ii", $docId, $userId);
$stmt->execute();

header("Location: dashboard.php");
exit;
