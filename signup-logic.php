<?php
require "config/database.php";
require("Mailer.php");


//get signup form data
$Mailer = new Mailer();
$otp=rand(00000,999999);

if(isset($_POST["submit"])){


   
    


    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];
    if(!$firstname){
        $_SESSION['signup'] = 'Please enter your First Name';
    }elseif(!$lastname){
        $_SESSION['signup'] = 'Please enter your Last Name';
    }elseif(!$username){
        $_SESSION['signup'] = 'Please enter your Username';
    }elseif(!$email){
        $_SESSION['signup'] = 'Please enter your Email';
    } elseif(strlen($createpassword) < 8 || !preg_match('/[A-Za-z]/', $createpassword) || !preg_match('/[0-9]/', $createpassword) || !preg_match('/[\W]/', $createpassword)){
        $_SESSION['signup'] = 'Password should be at least 8 characters long and include at least one letter, one number, and one special character';
    } elseif(!$avatar['name']){
        $_SESSION['signup'] = 'Please add Avatar ';
    }else{
        if($createpassword !== $confirmpassword){
            $_SESSION['signup']="Passwords donot match";

        }else{


            $hashed_password = password_hash($createpassword,PASSWORD_DEFAULT);
            


            $user_check_query="SELECT * FROM users WHERE username='$username' OR email ='$email'";
            $user_check_result = mysqli_query($connection, $user_check_query);
            if(mysqli_num_rows($user_check_result)>0){
                $_SESSION['signup'] = "Username or Email already exists";
            }else{
                //WORK ON AVATAR
                //rename avatar
                $time = time(); // make each image name unique using current timestamp 
                $avatar_name = $time . $avatar['name'];
                $avatar_tmp_name=$avatar['tmp_name'];
                $avatar_destination_path='images/' . $avatar_name;

                //,ake sure file is an image
                $allowed_files = ['png', 'jpg', 'jpeg'];
                $extension = explode('.', $avatar_name);
                $extension = end($extension);
                if(in_array($extension,$allowed_files)){
                    //if image not too large

                    if($avatar['size']<1000000){

                        //upload avatar
                        move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                    }else{
                        $_SESSION['signup']="Folder size too big.Should be less than 1mb";
                    }
                }else{
                    $_SESSION['signup']="File should be png, jpg or jpeg";
                }
            }



        }
    }
    // redirect back t signup on error
    if(isset($_SESSION['signup'])){
        // pass data back to sign up page
        $_SESSION['signup-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signup.php');
        die();
        
    }else{

//send mail
// $adopterSubject = "Pet Adoption Request";
// $adopterMessage = "There has been a Pet Adoption Request for the pet  listed by you.<br><br>";
$owner_id="$email";
$adopterSubject="Email Verification";
$adopterMessage="Your OTP is: {$otp} <br>Verify your email for registration.";
$Mailer->smtp_mailer($owner_id, $adopterSubject, $adopterMessage);

$_SESSION['signup-data'] = [
    'firstname' => $firstname,
    'lastname' => $lastname,
    'username' => $username,
    'email' => $email,
    'password' => $hashed_password,
    'avatar' => $avatar_name,
    'otp' => $otp
];

// Redirect to OTP verification page
header('location: ' . ROOT_URL . 'verify-otp.php');
die();
}
} else {
header('location: ' . ROOT_URL . "signup.php");
die();
}
?>

<!-- 
        //insert new user into users table
        $inset_user_query = "INSERT INTO users SET firstname ='$firstname' ,lastname='$lastname',username='$username',email ='$email' ,password='$hashed_password',avatar='$avatar_name',is_admin=0";
        $inset_user_result = mysqli_query($connection, $inset_user_query);

}


        if(!mysqli_errno($connection)){
            $_SESSION['signup-success'] = "Registration Successful Please login";
            header('location: ' . ROOT_URL . 'signin.php');

        }
    }
}else{
    //button not clicked
    header('location: ' . ROOT_URL . "signup.php");
    die();
} -->