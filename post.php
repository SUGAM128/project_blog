<?php 
session_start();
include 'partials/header.php';
 // Start the session to access session variables



if(isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE id=$id";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $post = mysqli_fetch_assoc($result);
        $author_id = $post['author_id'];
        $author_query = "SELECT * FROM users WHERE id=$author_id";
        $author_result = mysqli_query($connection, $author_query);
        $author = mysqli_fetch_assoc($author_result);

        // Fetch comments for the post
        $comments_query = "SELECT * FROM comments WHERE post_id=$id ORDER BY date_time DESC";
        $comments_result = mysqli_query($connection, $comments_query);

        if (!$comments_result) {
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} else {
    header('location: ' . ROOT_URL . 'blog.php');
    die();
}


?>

<section class="singlepost">
    <div class="container singlepost__container">
        <h2><?=$post['title']?></h2>
        <div class="post__author">
            <div class="post__author-avatar">
                <img src="./images/<?= $author['avatar'] ?>">
            </div>
            <div class="post__author-info">
                <h5>By: <?= "{$author['firstname']} {$author['lastname']}" ?></h5>
                <small>
                    <?=date("M d, Y -H:i", strtotime($post['date_time']))?>
                </small>
            </div>
        </div>
        <div class="singlepost__thumbnail">
            <img src="./images/<?=$post['thumbnail']?>">
        </div>
        <p><?=$post['body']?></p>

        <!-- Comments Section -->
        <section class="comments">
            <h3>Comments</h3>
            <?php if ($comments_result && mysqli_num_rows($comments_result) > 0): ?>
                <?php while($comment = mysqli_fetch_assoc($comments_result)): ?>
                    <div class="comment">
                        <div class="comment-author">
                            <strong><?= $comment['author_name'] ?></strong> on <?=date("M d, Y - H:i", strtotime($comment['date_time']))?>
                            <!-- <?= $comment[ 'body'] ?> -->
                        </div>
                        <div class="comment-body">
                        <?= $comment[ 'body'] ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </section>

        <section class="comment-form">
                <h3>Leave a Comment</h3>
                <?php if (isset($_SESSION['user-id'])): ?>
    <form action="comment.php" method="post">
        <input type="hidden" name="post_id" value="<?=$id?>">
        <div class="form-group">
            <label for="author_name">Name</label>
            <input type="text" name="author_name" id="author_name" value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : '' ?>" required readonly>
        </div>
        <div class="form-group">
            <label for="body">Comment</label>
            <input type="text" name="body" id="body" required></input>
        </div>
        <button type="submit" name="submit" class="btn">Submit</button>
    </form>
<?php else: ?>
    <p>You need to <a href="signin.php">log in</a> to comment.</p>
<?php endif; ?>
            </section>
        </section>
    </div>
</section>

<?php
include 'partials/footer.php';
?>