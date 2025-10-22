<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Fetch blog data
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();

if (!$blog) {
    header("Location: index.php");
    exit;
}

// Fetch existing images
$img_stmt = $conn->prepare("SELECT * FROM blog_images WHERE blog_id = ?");
$img_stmt->bind_param("i", $id);
$img_stmt->execute();
$images = $img_stmt->get_result();

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Update blog text
    $update_stmt = $conn->prepare("UPDATE blogs SET title=?, content=?, updated_at=NOW() WHERE id=?");
    $update_stmt->bind_param("ssi", $title, $content, $id);
    $update_stmt->execute();

    // Handle image deletions
    if (!empty($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $img_id) {
            $del_stmt = $conn->prepare("DELETE FROM blog_images WHERE id = ?");
            $del_stmt->bind_param("i", $img_id);
            $del_stmt->execute();
        }
    }

    // Handle new image uploads (max 5)
    if (!empty($_FILES['images']['name'][0])) {
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $count = count($_FILES['images']['name']);
        for ($i = 0; $i < $count && $i < 5; $i++) {
            if ($_FILES['images']['error'][$i] == 0) {
                $filename = time() . "_" . basename($_FILES['images']['name'][$i]);
                $target = $upload_dir . $filename;
                move_uploaded_file($_FILES['images']['tmp_name'][$i], $target);

                $ins_stmt = $conn->prepare("INSERT INTO blog_images (blog_id, image_path) VALUES (?, ?)");
                $ins_stmt->bind_param("is", $id, $target);
                $ins_stmt->execute();
            }
        }
    }

    header("Location: blog.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Edit Blog</title>
    <style>
        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 15px 0;
        }
        .image-preview div {
            position: relative;
        }
        .image-preview img {
            width: 180px;
            height: 130px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ccc;
        }
        .image-preview label {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.8);
            color: white;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<h1>Edit Blog</h1>

<form method="POST" enctype="multipart/form-data">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required><br><br>

    <label>Content:</label><br>
    <textarea name="content" rows="8" cols="50" required><?= htmlspecialchars($blog['content']) ?></textarea><br><br>

    <h3>Existing Images:</h3>
    <div class="image-preview">
        <?php while ($img = $images->fetch_assoc()): ?>
            <div>
                <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Blog Image">
                <label>
                    <input type="checkbox" name="delete_images[]" value="<?= $img['id'] ?>"> Delete
                </label>
            </div>
        <?php endwhile; ?>
    </div>

    <label>Add New Images (max 5):</label><br>
    <input type="file" name="images[]" multiple accept="image/*"><br><br>

    <button type="submit" name="submit">Update Blog</button>
</form>

<a href="index.php">Back to Home</a>
</body>
</html>
