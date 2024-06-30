<?php
require "config/database.php";

if (isset($_POST['verify'])) {
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['signup-data']['otp']) {
        // OTP is correct, register the user
        $firstname = $_SESSION['signup-data']['firstname'];
        $lastname = $_SESSION['signup-data']['lastname'];
        $username = $_SESSION['signup-data']['username'];
        $email = $_SESSION['signup-data']['email'];
        $hashed_password = $_SESSION['signup-data']['password'];
        $avatar_name = $_SESSION['signup-data']['avatar'];

        $insert_user_query = "INSERT INTO users SET firstname ='$firstname', lastname='$lastname', username='$username', email ='$email', password='$hashed_password', avatar='$avatar_name', is_admin=0";
        $insert_user_result = mysqli_query($connection, $insert_user_query);

        if (!mysqli_errno($connection)) {
            unset($_SESSION['signup-data']);
            $_SESSION['signup-success'] = "Registration Successful. Please login";
            header('location: ' . ROOT_URL . 'signin.php');
            die();
        }
    } else {
        $_SESSION['verify-error'] = "Incorrect OTP. Please try again.";
        header('location: ' . ROOT_URL . 'verify-otp.php');
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
</head>
<body>
<section class="form__section">
    <div class="container form__section-container">
        <form action="verify-otp.php" method="POST">
        <h2>Verify OTP</h2>
        <?php if (isset($_SESSION['verify-error'])): ?>
            <div class="error">
                <?php
                    echo $_SESSION['verify-error'];
                    unset($_SESSION['verify-error']);
                ?>
            </div>
        <?php endif; ?>
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit" name="verify" class="btn">Verify</button>
        <p>Didn't get OTP? <a href="<?= ROOT_URL ?>signup.php?email=<?= $_SESSION['signup-data']['email'] ?>">Check your email address</a></p>
        </form>
    </div>
</section>
</body>
</html>
