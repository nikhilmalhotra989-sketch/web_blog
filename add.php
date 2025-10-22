<?php
require 'db.php';
session_start();

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    // Insert blog first
    $stmt = $conn->prepare("INSERT INTO blogs (title, content, author) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $author);
    $stmt->execute();
    $blog_id = $stmt->insert_id;

    // Handle image uploads
    $upload_dir = "uploads/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $images = $_FILES['images'];
    $total = count($images['name']);
    
    if($total > 0 && $total <= 5){
        for($i = 0; $i < $total; $i++){
            if($images['error'][$i] == 0){
                $filename = time() . "_" . basename($images['name'][$i]);
                $target = $upload_dir . $filename;
                move_uploaded_file($images['tmp_name'][$i], $target);

                $stmt_img = $conn->prepare("INSERT INTO blog_images (blog_id, image_path) VALUES (?, ?)");
                $stmt_img->bind_param("is", $blog_id, $target);
                $stmt_img->execute();
            }
        }
    } else {
        echo "Please upload between 1 and 5 images.";
        exit;
    }

    header("Location: index.php");
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
<form method="POST" enctype="multipart/form-data" action="add_blog.php">
    <input type="text" name="title" placeholder="Blog Title" required><br>
    <textarea name="content" placeholder="Content" required></textarea><br>
    <input type="text" name="author" placeholder="Author" required><br>

    <label>Upload Images (1–5):</label>
    <input type="file" name="images[]" multiple accept="image/*" required><br><br>

    <button type="submit" name="submit">Add Blog</button>
</form>

<a href="index.php">Back to Home</a>
</body>
</html>
