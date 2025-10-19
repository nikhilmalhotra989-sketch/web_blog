<?php
require 'db.php';

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    $stmt = $conn->prepare("INSERT INTO blogs (title, content, author) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $author);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Blog</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Add New Blog</h1>
<form method="POST" action="">
    Title: <input type="text" name="title" required><br><br>
    Author: <input type="text" name="author" required><br><br>
    Content: <br><textarea name="content" rows="8" cols="50" required></textarea><br><br>
    <button type="submit" name="submit">Add Blog</button>
</form>
<a href="index.php">Back to Home</a>
</body>
</html>
