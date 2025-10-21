<?php 
include 'connection.php';

if(isset($_POST['submit-btn'])){
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $name = mysqli_real_escape_string($conn, $filter_name);

    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $filter_email);

    $filter_password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $password = mysqli_real_escape_string($conn, $filter_password);

    $filter_cpassword = htmlspecialchars($_POST['cpassword'], ENT_QUOTES, 'UTF-8');
    $cpassword = mysqli_real_escape_string($conn, $filter_cpassword);

    $select_user = mysqli_query($conn,"SELECT * FROM `users` WHERE email='$email'") or die('query failed');
    if(mysqli_num_rows($select_user) > 0){
        $message11[] = 'user already exists';
    } else {
        if($password != $cpassword){
            $message11[] = 'passwords do not match';
        } else {
            mysqli_query($conn, "INSERT INTO `users` (`name`,`email`,`password`) VALUES ('$name','$email','$password')") or die("query failed");
            $message11[] = 'registered successfully';
            header('Location: login.php');
            exit; // stop further output
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- box icon link -->
    <link  href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>Registration page</title>
</head>
<body>
    
    <section class="form-container">
    <?php
    if(isset($message11)){
        foreach ($message11 as $msg){
            echo '
            <div class="message">
            <span>'.$msg.'</span>
            <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>
            ';
        }
    }
     ?>
        <form method="post">
            <h1>register now</h1>
            <input type="text" name="name" placeholder="Enter your name" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="password" name="cpassword" placeholder="Confirm your password" required>
            <input type="submit" name="submit-btn" value="register now" class="btn">
            <p>Already have an account ? <a href="login.php"><b>Login Now</b></a></p>
        </form>
    </section>
</body>
</html>
