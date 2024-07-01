<?php
require 'config/database.php';
require "../Mailer.php"; // Include your Mailer class file here

if(isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Fetch post from database
    $query = "SELECT * FROM posts WHERE id = $id";
    $result = mysqli_query($connection, $query);

    // Check if exactly 1 record was fetched from database
    if(mysqli_num_rows($result) == 1) {
        $post = mysqli_fetch_assoc($result);
        $author_id = $post['author_id'];
        $thumbnail_name = $post['thumbnail'];
        $thumbnail_path = "../images/" . $thumbnail_name;

        // Delete post from database
        $delete_post_query = "DELETE FROM posts WHERE id = $id LIMIT 1";
        $delete_post_result = mysqli_query($connection, $delete_post_query);

        // Check if deletion was successful
        if($delete_post_result) {
            // Post deleted successfully
            $_SESSION['edit-post-success'] = "Post deleted successfully";

            // Send email notification to the author
            $user_query = "SELECT email FROM users WHERE id = $author_id";
            $user_result = mysqli_query($connection, $user_query);
            if(mysqli_num_rows($user_result) == 1) {
                $user = mysqli_fetch_assoc($user_result);
                $author_email = $user['email'];

                // Prepare email notification
                $subject = 'Your Post has been Deleted';
                $message = "Hello,\n\n";
                $message .= "We regret to inform you that your post titled '{$post['title']}' has been deleted.\n\n";
                $message .= "If you have any questions, please contact us.\n\n";
                $headers = 'From: admin@example.com';

                // Instantiate Mailer class and send email
                $Mailer = new Mailer();
                $Mailer->smtp_mailer($author_email, $subject, $message);
            }
        }
    }
}

// Redirect back to admin page after processing
header('Location: ' . ROOT_URL . 'admin/');
exit();
?>
