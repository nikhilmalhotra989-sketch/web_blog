<?php
session_start();
require 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect non-admins
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM blogs WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;
?>
