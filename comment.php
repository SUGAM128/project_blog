<?php
session_start();
include 'config/database.php'; // Include your database configuration file

if (isset($_POST['submit'])) {
    if (isset($_SESSION['user-id'])) {
        $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
        $author_name = $_SESSION['username']; // Use session username
        $body = filter_var($_POST['body'], FILTER_SANITIZE_STRING);

        // Insert comment into database
        $query = "INSERT INTO comments (post_id, author_name, body, date_time) VALUES ('$post_id', '$author_name', '$body', NOW())";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header("Location:post.php?id=$post_id");
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        header('location: ' . ROOT_URL . 'signup.php');
        exit();
    }
} else {
    header('location: ' . ROOT_URL . 'blog.php');
    exit();
}
?>
