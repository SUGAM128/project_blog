<?php
include "partials/header.php";
require "../Mailer.php";
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$Mailer=new Mailer();

// Verify database connection
if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Ensure only admin can access this page
if (!isset($_SESSION['user_is_admin'])) {
    // Redirect or display error message if not admin
    header("Location: " . ROOT_URL . "admin/index.php");
    exit();
}

// Handle checkbox update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['report_id'])) {
    $report_id = $_POST['report_id'];
    $checked = isset($_POST['checked']) ? 1 : 0;

    // Update the report status in the database
    $update_query = "UPDATE reports SET checked = $checked WHERE id = $report_id";
    mysqli_query($connection, $update_query);

    // Fetch report details for email notification
    $report_details_query = "
        SELECT r.id AS report_id, p.title AS post_name, r.reason, u.username AS reported_by, u.email AS reported_email
        FROM reports r
        INNER JOIN posts p ON r.post_id = p.id
        INNER JOIN users u ON r.reported_by = u.id
        WHERE r.id = $report_id
    ";
    $report_details_result = mysqli_query($connection, $report_details_query);
    $report_details = mysqli_fetch_assoc($report_details_result);

    // Send email notification if the status was changed
    if ($report_details['checked'] != $checked) {
        $to = $report_details['reported_email'];
        $subject = 'Report Status Update';
        $message = "Hello {$report_details['reported_by']},\n\n";
        $message .= "Your report on '{$report_details['post_name']}' has been checked for violation and is being reviewed by the team. ";
        $message .= $checked ? "\n\n" : "unchecked.\n\n";
        $message .= "Thank you for your report.\n\n";
        $headers = 'From: admin@example.com';

        // Send email
        $Mailer->smtp_mailer($to, $subject, $message);
            
    }
}

// Fetch submitted reports with post name and username
$reports_query = "
    SELECT r.id AS report_id, p.id AS post_id, p.title AS post_name, r.reason, u.username AS reported_by, r.checked
    FROM reports r
    INNER JOIN posts p ON r.post_id = p.id
    INNER JOIN users u ON r.reported_by = u.id
    ORDER BY r.id DESC
";
$reports_result = mysqli_query($connection, $reports_query);

// Fetch the number of unchecked reports
$unchecked_reports_query = "SELECT COUNT(*) AS unchecked_count FROM reports WHERE checked = 0";
$unchecked_reports_result = mysqli_query($connection, $unchecked_reports_query);
$unchecked_count = mysqli_fetch_assoc($unchecked_reports_result)['unchecked_count'];
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
                        <h5>Reports (<?= $unchecked_count ?>)</h5>
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>admin/review-post.php">
                        <i class="uil uil-postcard"></i>
                        <h5>Review Post</h5>
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
                            <th>Post Name</th>
                            <th>Reason</th>
                            <th>Reported By</th>
                            <th>Checked</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($report = mysqli_fetch_assoc($reports_result)): ?>
                            <tr>
                                <td><?= $report['report_id'] ?></td>
                                <td><a href="<?= ROOT_URL ?>post.php?id=<?= $report['post_id'] ?>"><?= $report['post_name'] ?></a></td>
                                <td><?= $report['reason'] ?></td>
                                <td><?= $report['reported_by'] ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="report_id" value="<?= $report['report_id'] ?>">
                                        <input type="checkbox" name="checked" <?= $report['checked'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                    </form>
                                </td>
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
