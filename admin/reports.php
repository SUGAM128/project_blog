<?php
include "partials/header.php";

// Ensure only admin can access this page
if (!isset($_SESSION['user_is_admin'])) {
    // Redirect or display error message if not admin
    // Example: header("Location: " . ROOT_URL . "admin/index.php");
    exit();
}

// Fetch submitted reports
$reports_query = "SELECT * FROM reports ORDER BY id DESC";
$reports_result = mysqli_query($connection, $reports_query);


?>

<section class="dashboard">
    <div class="container dashboard__container">

        <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
        <button id="hide__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-left-b"></i></button>

        <aside>
            <ul>
                <li>
                    <a href="<?= ROOT_URL ?>admin/add-post.php">
                        <i class="uil uil-pen"></i>
                        <h5>Add Post</h5>
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>admin/index.php">
                        <i class="uil uil-postcard"></i>
                        <h5>Manage Posts</h5>
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>admin/reports.php" class="active">
                        <i class="uil uil-file-alt"></i>
                        <h5>Reports</h5>
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>admin/review-post.php">
                        <i class="uil uil-postcard"></i>
                        <h5>Review Post</h5>
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>admin/add-user.php">
                        <i class="uil uil-user-plus"></i> 
                        <h5>Add User</h5>
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>admin/manage-users.php">
                        <i class="uil uil-users-alt"></i>
                        <h5>Manage Users</h5>
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>admin/add-category.php">
                        <i class="uil uil-edit"></i>
                        <h5>Add Category</h5>
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>admin/manage-categories.php">
                        <i class="uil uil-list-ul"></i>
                        <h5>Manage Categories</h5>
                    </a>
                </li>
            </ul>
        </aside>

        <main>
            <h2>Reports</h2>

            <?php if(mysqli_num_rows($reports_result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Post ID</th>
                            <th>Reason</th>
                            <th>User ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($report = mysqli_fetch_assoc($reports_result)): ?>
                            <tr>
                                <td><?= $report['id'] ?></td>
                                <td><?= $report['post_id'] ?></td>
                                <td><?= $report['reason'] ?></td>
                                <td><?= $report['reported_by'] ?></td>
                                <td>
                                    <!-- Add action buttons or links for admin actions -->
                                    <!-- Example: <a href="#">Approve</a> | <a href="#">Delete</a> -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No reports found.</p>
            <?php endif; ?>
        </main>
    </div>
</section>

<?php include "../partials/footer.php"; ?>
