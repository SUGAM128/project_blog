<?php 
session_start();
include 'partials/header.php';

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

// Function to handle post reporting
if (isset($_POST['report'])) {
    $report_reason = mysqli_real_escape_string($connection, $_POST['report_reason']);
    $report_author_id = $_SESSION['user-id'];

    // Insert the report into database
    $report_query = "INSERT INTO reports (post_id, reported_by, reason) VALUES ($id, $report_author_id, '$report_reason')";
    $report_result = mysqli_query($connection, $report_query);

    if ($report_result) {
        $_SESSION['report_success'] = "Post reported successfully.";
    } else {
        $_SESSION['report_error'] = "Failed to report the post.";
    }

    // Redirect back to the same page
    header("Location: {$_SERVER['PHP_SELF']}?id=$id");
    exit();
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
                        </div>
                        <div class="comment-body">
                            <?= $comment['body'] ?>
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
          <!-- Report Post Section -->
          <section class="report-post">
            <div class="toggle-report">
                <button id="toggle-report-btn" class="btn">Report Post</button>
            </div>
            <?php if (isset($_SESSION['user-id'])): ?>
            <div id="report-form" style="display: none;">
                <h3>Report This Post</h3>
                <form action="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $id ?>" method="post">
                    <div class="form-group">
                        <label for="report_reason">Reason for Reporting:</label>
                        <textarea name="report_reason" id="report_reason" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="report" class="btn">Report</button>
                </form>

                <?php if (isset($_SESSION['report_success'])): ?>
                    <p class="success-message"><?=$_SESSION['report_success']?></p>
                    <?php unset($_SESSION['report_success']); ?>
                <?php elseif (isset($_SESSION['report_error'])): ?>
                    <p class="error-message"><?=$_SESSION['report_error']?></p>
                    <?php unset($_SESSION['report_error']); ?>
                <?php endif; ?>
            </div>
            <?php else: ?>
                <p>You need to <a href="signin.php">log in</a> to report.</p>
            <?php endif; ?>
        </section>
        </section>
    </div>
</section>

<script>
    document.getElementById('toggle-report-btn').addEventListener('click', function() {
        var reportForm = document.getElementById('report-form');
        if (reportForm.style.display === 'none') {
            reportForm.style.display = 'block';
        } else {
            reportForm.style.display = 'none';
        }
    });
</script>

<?php
include 'partials/footer.php';
?>
