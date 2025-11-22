<?php 
include 'connection.php';
session_start();

if(isset($_POST['submit-btn'])){
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $name = mysqli_real_escape_string($conn, $filter_name);

    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $filter_email);

    $filter_password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $password = mysqli_real_escape_string($conn, $filter_password);

    $filter_cpassword = htmlspecialchars($_POST['cpassword'], ENT_QUOTES, 'UTF-8');
    $cpassword = mysqli_real_escape_string($conn, $filter_cpassword);

    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile'] ?? '');
    $select_user = mysqli_query($conn,"SELECT * FROM `users` WHERE email='$email'") or die('query failed');
    if(mysqli_num_rows($select_user) > 0){
        $message11[] = 'user already exists';
    } else {
        if($password != $cpassword){
            $message11[] = 'passwords do not match';
        } else {
            mysqli_query($conn, "INSERT INTO `users` (`name`,`email`,`password`,`address`,`mobile`) VALUES ('$name','$email','$password','$address','$mobile')") or die("query failed");
            $message11[] = 'registered successfully';
            
            // After registration, proceed to login with return_url
            $return_url = '';
            if(!empty($_GET['return_url'])){
                $r = urldecode($_GET['return_url']);
                // basic safety: only allow internal redirects
                if(strpos($r, '..') === false){
                    $return_url = '?return_url=' . urlencode($r);
                }
            }
            
            header('Location: login.php' . $return_url);
            exit;
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
        <form method="post" style="background:#fff3e0;border-radius:16px;box-shadow:0 6px 24px rgba(212,175,55,0.10);padding:32px;max-width:420px;margin:auto;">
            <h1 style="color:#b68c25;margin-bottom:18px;">Register</h1>
            <div style="margin-bottom:16px;"><input type="text" name="name" placeholder="Full Name" required style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6eef8;font-size:1rem;background:#fff;"></div>
            <div style="margin-bottom:16px;"><input type="email" name="email" placeholder="Email" required style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6eef8;font-size:1rem;background:#fff;"></div>
            <div style="margin-bottom:16px;"><input type="password" name="password" placeholder="Password" required style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6eef8;font-size:1rem;background:#fff;"></div>
            <div style="margin-bottom:16px;"><input type="password" name="cpassword" placeholder="Confirm Password" required style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6eef8;font-size:1rem;background:#fff;"></div>
            <div style="margin-bottom:16px;"><input type="text" name="address" placeholder="Address" required style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6eef8;font-size:1rem;background:#fff;"></div>
            <div style="margin-bottom:16px;"><input type="tel" name="mobile" placeholder="Mobile No" required style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6eef8;font-size:1rem;background:#fff;"></div>
            <input type="submit" name="submit-btn" value="Register Now" class="btn" style="background:linear-gradient(135deg,#d4af37 0%,#b68c25 100%);color:#fff;border:none;padding:12px 24px;border-radius:8px;cursor:pointer;font-weight:600;box-shadow:0 2px 12px rgba(212,175,55,0.10);transition:background 0.2s;">
            <p style="margin-top:12px;">Already have an account? <a href="login.php<?php echo isset($_GET['return_url']) ? '?return_url=' . urlencode($_GET['return_url']) : ''; ?>"><b>Login Now</b></a></p>
        </form>
    </section>
</body>
</html>
