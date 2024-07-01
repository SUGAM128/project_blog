<?php
require "config/database.php";

if(isset($_POST['submit'])){
    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $thumbnail = $_FILES['thumbnail'];

    // Set featured to zero if unchecked
    $is_featured = $is_featured == 1 ? 1 : 0;

    // Validate form data
    if (!$title) {
        $_SESSION['add-post'] = "Enter post title";
    } elseif (!$category_id) {
        $_SESSION['add-post'] = "Select post category";
    } elseif (!$body) {
        $_SESSION['add-post'] = "Enter post body";
    } elseif (!$thumbnail['name']) {
        $_SESSION['add-post'] = "Choose post thumbnail";
    } else {
        // Work on thumbnail
        // Rename the image
        $time = time(); // Make each name unique
        $thumbnail_name = $time . $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = "../images/" . $thumbnail_name;

        // Make sure file is an image
        $allowed_files = ['jpg', 'png', 'jpeg'];
        $extension = explode('.', $thumbnail_name);
        $extension = end($extension);
        if (in_array($extension, $allowed_files)) {
            // Make sure image is not too large (2MB+)
            if ($thumbnail['size'] < 2000000) {
                // Upload thumbnail
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
            } else {
                $_SESSION['add-post'] = "File size too big. Should be less than 2MB";
            }
        } else {
            $_SESSION['add-post'] = "File should be PNG, JPG, or JPEG";
        }
    }

    // Redirect with form data if there's an error
    if (isset($_SESSION['add-post'])) {
        $_SESSION['add-post-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        die();
    } else {
        // Set is_featured of all posts to 0 if is_featured for this post is set to 1
        if ($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE posts SET is_featured=0";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }        

        // Insert post into database with status 'pending'
        $query = "INSERT INTO posts (title, body, thumbnail, category_id, author_id, is_featured, status) VALUES ('$title', '$body', '$thumbnail_name', $category_id, $author_id, $is_featured, 'pending')";
        $result = mysqli_query($connection, $query);
        if (mysqli_errno($connection)) {
            $_SESSION['add-post'] = "Failed to add post";
            header("location: " . ROOT_URL . 'admin/index.php');
            die();
        } else {
            $_SESSION['add-post-success'] = "New post added successfully and is awaiting review.";
            header("location: " . ROOT_URL . 'admin/index.php');
            die();
        }
    }
}

header("location: " . ROOT_URL . 'admin/index.php');
die();
?>
