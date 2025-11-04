<?php
    include 'connection.php';
    session_start();
    $user_id=$_SESSION['user_id'];

    if(!isset($user_id)){
       header('location: ../01_Admin Site/login.php');
    }
    if(isset($_POST['logout'])){
        session_destroy();
        header('location: ../01_Admin Site/login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>"> 
    <title>কাচ্চি গঞ্জ</title>
  </head>
  <body>
    <header class="header">
     <?php include 'nav.php'; ?>
      <div class="section__container header__container" id="home">
        <div class="header__image">
          <img src="assets/header.png" alt="header" />
        </div>
        <div class="header__content">
          <h2>"ঐতিহ্যবাহী কাচ্চি মানেই 
              — কাচ্চিগঞ্জ এর কাচ্চি।"</h2>
          <h1> আমাদের সম্পর্কে </h1>
        </div>
      </div>
    </header>

  

  <!-- Footer -->
   <section class="section__container contact__section" id="contact"> </section>
   <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
  </body>
</html>

