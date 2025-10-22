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

if (!$blog) {
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
    <style>
        /* --- Carousel Styling --- */
        .carousel {
            position: relative;
            max-width: 700px;
            margin: 15px auto;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .carousel img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: none;
        }
        .carousel img.active {
            display: block;
        }
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0,0,0,0.4);
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 50%;
            font-size: 18px;
        }
        .carousel-btn:hover {
            background-color: rgba(0,0,0,0.6);
        }
        .carousel-btn.prev {
            left: 10px;
        }
        .carousel-btn.next {
            right: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="blog-card">
            <h2><?= htmlspecialchars($blog['title']) ?></h2>

            <!-- Image Carousel -->
            <div class="carousel" id="blogCarousel">
                <?php
                $img_stmt = $conn->prepare("SELECT image_path FROM blog_images WHERE blog_id = ?");
                $img_stmt->bind_param("i", $id);
                $img_stmt->execute();
                $img_result = $img_stmt->get_result();
                $first = true;
                while ($img = $img_result->fetch_assoc()):
                ?>
                    <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Blog Image"
                         class="<?= $first ? 'active' : '' ?>">
                <?php
                $first = false;
                endwhile;
                ?>
                <button class="carousel-btn prev" onclick="moveSlide(-1)">&#10094;</button>
                <button class="carousel-btn next" onclick="moveSlide(1)">&#10095;</button>
            </div>

            <p>By <?= htmlspecialchars($blog['author']) ?> | <?= $blog['created_at'] ?></p>
            <p><?= nl2br(htmlspecialchars($blog['content'])) ?></p>

            <?php if ($current_role === 'admin'): ?>
                <a href="edit.php?id=<?= $blog['id'] ?>"><button>Edit</button></a>
                <a href="delete.php?id=<?= $blog['id'] ?>" onclick="return confirm('Delete this blog?')">
                    <button>Delete</button></a>
            <?php endif; ?>

            <a href="index.php"><button>Back to Home</button></a>
        </div>
    </div>

    <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll('.carousel img');

        function moveSlide(direction) {
            slides[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + direction + slides.length) % slides.length;
            slides[currentIndex].classList.add('active');
        }
    </script>
</body>
</html>
