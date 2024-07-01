<?php
require "config/database.php";
require "../Mailer.php"; // Include your mail function or mailer library
// session_start();
$Mailer=new Mailer();

// Check if the user is an admin
if (!isset($_SESSION['user_is_admin'])) {
    header('Location: ' . ROOT_URL . 'signin.php');
    exit();
}

if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    
    // Fetch post details including author ID
    $post_query = "SELECT * FROM posts WHERE id = $post_id";
    $post_result = mysqli_query($connection, $post_query);
    $post = mysqli_fetch_assoc($post_result);

    if ($post) {
        // Update post status to 'rejected'
        $query = "UPDATE posts SET status = 'rejected' WHERE id = $post_id";
        mysqli_query($connection, $query);

        // Fetch the author's email
        $author_id = $post['author_id'];
        $user_query = "SELECT email FROM users WHERE id = $author_id";
        $user_result = mysqli_query($connection, $user_query);
        $user = mysqli_fetch_assoc($user_result);

        if ($user) {
            $user_email = $user['email'];
            $post_title = $post['title'];

            // Prepare the email
            $subject = "Your post has been rejected";
            $message = "Dear User,\n\nWe regret to inform you that your post titled '$post_title' has been rejected by our review team.\n\nThank you for your understanding.\n\nBest regards,\nYour Team";
            $Mailer->smtp_mailer($user_email, $subject, $message);
            // Send the email
//             if (send_email($user_email, $subject, $message)) {
//                 $_SESSION['message'] = "Post rejected and user notified.";
//             } else {
//                 $_SESSION['message'] = "Post rejected but failed to notify the user.";
//             }
//         } else {
//             $_SESSION['message'] = "Failed to fetch user details.";
//         }
//     } else {
//         $_SESSION['message'] = "Post not found.";
//     }
}

header('Location: ' . ROOT_URL . 'admin/review-post.php');
exit();

}
}