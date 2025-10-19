<?php
session_start();
require 'db.php';

// Get logged-in user info
$current_user = $_SESSION['username'] ?? '';
$current_role = $_SESSION['role'] ?? '';

// Fetch all blogs
$result = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Blog</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">

    <!-- Header -->
    <header>
        <h1>Student Blogs</h1>
        <form method="GET" action="search.php">
            <input type="text" name="q" id="search-bar" placeholder="Search blogs">
            <button type="submit" id="search">Search</button>
        </form>

        <!-- Admin: Add Blog -->
        <?php if($current_role === 'admin'): ?>
            <a href="add.php"><button>Add New Blog</button></a>
        <?php endif; ?>

        <!-- Login / Logout -->
        <?php if($current_user): ?>
            <a href="logout.php"><button>Logout (<?= htmlspecialchars($current_user) ?>)</button></a>
        <?php else: ?>
            <a href="login.php"><button>Login</button></a>
        <?php endif; ?>
    </header>

    <!-- Blog List -->
    <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="blog-card">
                <h2><a href="blog.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h2>
                <p>By <?= htmlspecialchars($row['author']) ?> | <?= $row['created_at'] ?></p>
                <p><?= nl2br(htmlspecialchars(substr($row['content'], 0, 200))) ?>...</p>

                <!-- Admin: Edit/Delete Buttons -->
                <?php if($current_role === 'admin'): ?>
                    <a href="edit.php?id=<?= $row['id'] ?>"><button>Edit</button></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this blog?')"><button>Delete</button></a>
                <?php endif; ?>

                <a href="blog.php?id=<?= $row['id'] ?>"><button>Read More</button></a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No blogs found.</p>
    <?php endif; ?>

</div>

</body>
</html>
