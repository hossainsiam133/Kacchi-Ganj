<?php 
include 'connection.php';
session_start();

if(isset($_POST['submit-btn'])){
    // Sanitize input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $email);

    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $password = mysqli_real_escape_string($conn, $password);

    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'") or die('query failed');
    
    if(mysqli_num_rows($select_user) > 0){
        $row = mysqli_fetch_assoc($select_user);

        if($row['user_type'] == 'admin' && $row['password'] == $password){
            $_SESSION['admin_name']  = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id']    = $row['id'];
            header('Location: admin_pannel.php');
            exit;
        } 
        else if($row['user_type'] == 'user' && $row['password'] == $password){
            $_SESSION['user_name']  = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id']    = $row['id'];
            header('Location: ../02_User Site/home.php');
            exit;
        } 
        else {
            $message1[] = 'incorrect email or password';
        }
    } else {
        $message1[] = 'no account found with this email';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- box icon link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>Login page</title>
</head>
<body>
    
    <section class="form-container">
    <?php
    if(isset($message1)){
        foreach ($message1 as $msg){
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
            <h1 style="color:#b68c25;margin-bottom:18px;">Login</h1>
            <div style="margin-bottom:16px;">
                <input type="email" name="email" placeholder="Email" required style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6eef8;font-size:1rem;background:#fff;">
            </div>
            <div style="margin-bottom:16px;">
                <input type="password" name="password" placeholder="Password" required style="width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6eef8;font-size:1rem;background:#fff;">
            </div>
            <input type="submit" name="submit-btn" value="Login Now" class="btn" style="background:linear-gradient(135deg,#d4af37 0%,#b68c25 100%);color:#fff;border:none;padding:12px 24px;border-radius:8px;cursor:pointer;font-weight:600;box-shadow:0 2px 12px rgba(212,175,55,0.10);transition:background 0.2s;">
            <p style="margin-top:12px;">Don't have an account? <a href="register.php"><b>Register Now</b></a></p>
        </form>
    </section>
</body>
</html>
