<?php
session_start();
require 'db.php';

$current_user = $_SESSION['username'] ?? '';
$current_role = $_SESSION['role'] ?? '';

$keyword = $_GET['q'] ?? '';

$stmt = $conn->prepare("SELECT * FROM blogs WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC");
$search = "%$keyword%";
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">

    <header>
        <h1>Search Results for "<?= htmlspecialchars($keyword) ?>"</h1>
        <form method="GET" action="search.php">
            <input type="text" name="q" placeholder="Search blogs" value="<?= htmlspecialchars($keyword) ?>">
            <button type="submit">Search</button>
        </form>
        <a href="index.php"><button>Back to Home</button></a>
        <?php if($current_role === 'admin'): ?>
            <a href="add.php"><button>Add New Blog</button></a>
        <?php endif; ?>
    </header>

    <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="blog-card">
                <h2><a href="blog.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h2>
                <p>By <?= htmlspecialchars($row['author']) ?> | <?= $row['created_at'] ?></p>
                <p><?= nl2br(htmlspecialchars(substr($row['content'], 0, 200))) ?>...</p>

                <?php if($current_role === 'admin'): ?>
                    <a href="edit.php?id=<?= $row['id'] ?>"><button>Edit</button></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this blog?')"><button>Delete</button></a>
                <?php endif; ?>

                <a href="blog.php?id=<?= $row['id'] ?>"><button>Read More</button></a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No blogs found matching "<strong><?= htmlspecialchars($keyword) ?></strong>".</p>
    <?php endif; ?>

</div>

</body>
</html>
