<?php
session_start();
require 'db.php';

$current_user = $_SESSION['username'] ?? '';
$current_role = $_SESSION['role'] ?? '';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if(!$blog){
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($blog['title']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">

    <div class="blog-card">
        <h2><?= htmlspecialchars($blog['title']) ?></h2>
        <p>By <?= htmlspecialchars($blog['author']) ?> | <?= $blog['created_at'] ?></p>
        <p><?= nl2br(htmlspecialchars($blog['content'])) ?></p>

        <?php if($current_role === 'admin'): ?>
            <a href="edit.php?id=<?= $blog['id'] ?>"><button>Edit</button></a>
            <a href="delete.php?id=<?= $blog['id'] ?>" onclick="return confirm('Delete this blog?')"><button>Delete</button></a>
        <?php endif; ?>

        <a href="index.php"><button>Back to Home</button></a>
    </div>

</div>

</body>
</html>
