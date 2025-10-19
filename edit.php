

<?php
session_start();
require 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect non-admins
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM blogs WHERE id=$id");
$blog = $result->fetch_assoc();

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE blogs SET title=?, content=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" href="styles.css">

    <title>Edit Blog</title>
</head>
<body>
<h1>Edit Blog</h1>
<form method="POST" action="">
    Title: <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required><br><br>
    Content: <br><textarea name="content" rows="8" cols="50" required><?= htmlspecialchars($blog['content']) ?></textarea><br><br>
    <button type="submit" name="submit">Update Blog</button>
</form>
<a href="index.php">Back to Home</a>
</body>
</html>
