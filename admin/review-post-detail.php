<?php
require "config/database.php";
// session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_is_admin'])) {
    header('Location: ' . ROOT_URL . 'signin.php');
    exit();
}

// Get the post ID from the query string
$post_id = intval($_GET['id']);

// Fetch the post from the database
$query = "SELECT * FROM posts WHERE id = $post_id AND status = 'pending'";
$result = mysqli_query($connection, $query);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    header('Location: ' . ROOT_URL . 'admin/review-post.php');
    exit();
}

// Fetch the category
$category_query = "SELECT * FROM categories WHERE id = {$post['category_id']}";
$category_result = mysqli_query($connection, $category_query);
$category = mysqli_fetch_assoc($category_result);

// Fetch the author
$author_query = "SELECT * FROM users WHERE id = {$post['author_id']}";
$author_result = mysqli_query($connection, $author_query);
$author = mysqli_fetch_assoc($author_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Post Detail</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<section class="singlepost">
<div class="container singlepost__container">
            <h1><?= htmlspecialchars($post['title']) ?></h1>
            <div class="post-detail__info">
                <span>Category: <?= htmlspecialchars($category['title']) ?></span>
                <span>By: <?= htmlspecialchars($author['firstname']) . ' ' . htmlspecialchars($author['lastname']) ?></span>
                <span>Date: <?= date("M d, Y - H:i", strtotime($post['date_time'])) ?></span>
            </div>
            <div class="post-detail__image">
                <img src="../images/<?= $post['thumbnail'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
            </div>
            <div class="post-detail__content">
                <?= nl2br(html_entity_decode($post['body'])) ?>
            </div>
            <div class="post-detail__actions">
                <a href="approve-post.php?id=<?= $post['id'] ?>" class="btn success">Approve</a>
                <a href="reject-post.php?id=<?= $post['id'] ?>" class="btn danger">Reject</a>
            </div>
        </div>
    </section>
</body>
</html>
