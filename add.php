<?php
require 'db.php';
session_start();

if(isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = trim($_POST['author']);

    // Insert blog into database
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
                $ext = strtolower(pathinfo($images['name'][$i], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','gif','webp'];

                if(in_array($ext, $allowed)){
                    $filename = time() . "_" . rand(1000,9999) . "." . $ext;
                    $target = $upload_dir . $filename;

                    if(move_uploaded_file($images['tmp_name'][$i], $target)){
                        $stmt_img = $conn->prepare("INSERT INTO blog_images (blog_id, image_path) VALUES (?, ?)");
                        $stmt_img->bind_param("is", $blog_id, $target);
                        $stmt_img->execute();
                    } else {
                        $_SESSION['error'] = "Failed to upload image: " . $images['name'][$i];
                    }
                } else {
                    $_SESSION['error'] = "Invalid file type: " . $images['name'][$i];
                }
            }
        }
    } else {
        $_SESSION['error'] = "Please upload between 1 and 5 images.";
    }

    // Redirect to index.php after processing
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

<?php
// Display error message if set
if(isset($_SESSION['error'])){
    echo "<p style='color:red;'>".$_SESSION['error']."</p>";
    unset($_SESSION['error']);
}
?>

<form method="POST" enctype="multipart/form-data" action="add.php">
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
