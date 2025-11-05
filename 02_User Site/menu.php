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
          <img src="assets/menuheader.png" alt="header" />
        </div>
        <div class="header__content">
          <h2>"দুনিয়াতে 'কাচ্চি' 
              থাকতে মানুষ কেন নেশা করে বুঝি না।"</h2>
          <h1> মেনু </h1>
        </div>
      </div>
    </header>


  <section class="dishes">
      <h1><p class="section-subtitle">
        কাচ্চিগঞ্জে প্রতিটি পদই তৈরি হয় খাঁটি উপকরণ এবং পরিপূর্ণ স্বাদের সাথে। 
        আমাদের বিশেষ ডিশগুলো আপনাকে এনে দেবে এক অসাধারণ রন্ধন অভিজ্ঞতা।   
      </p></h1>

      <div class="dish-container">
        <div class="dish-card">
          <div class="dish-img">
            <img src="assets/dish1.png" alt="Veg Biryani" />
            <span class="price">300/-</span>
          </div>
          <h3 class="dish-title">ভেজ বিরিয়ানি</h3>
          <p class="dish-desc">
            সুগন্ধি সবজি এবং মশলা দিয়ে তৈরি লোভনীয় ভেজ বিরিয়ানি, যা শাকাহারীদের
            জন্য এক অনন্য অভিজ্ঞতা।
          </p>
        </div>

        <div class="dish-card">
          <div class="dish-img">
            <img src="assets/dish1.png" alt="Morog Korma" />
            <span class="price">250/-</span>
          </div>
          <h3 class="dish-title">স্পেশাল মোরগ কোরমা</h3>
          <p class="dish-desc">
            নরম মুরগি, ক্রিমি সস এবং মশলার নিখুঁত সমন্বয়— একবারই খেলে অভ্যস্ত!
          </p>
        </div>

        <div class="dish-card">
          <div class="dish-img">
            <img src="assets/dish1.png" alt="Chicken Biryani" />
            <span class="price">280/-</span>
          </div>
          <h3 class="dish-title">চিকেন বিরিয়ানি</h3>
          <p class="dish-desc">
            সযত্নভাবে মশলা মিশিয়ে রান্না করা নরম মুরগি, যা প্রতিটি কামড়ে এনে দেয়
            অসাধারণ স্বাদ।
          </p>
        </div>

        <div class="dish-card">
          <div class="dish-img">
            <img src="assets/dish1.png" alt="Kacchi Biryani" />
            <span class="price">240/-</span>
          </div>
          <h3 class="dish-title">কাচ্চি বিরিয়ানি</h3>
          <p class="dish-desc">
            সুগন্ধি বাসমতী চাল, নরম মাংস এবং বিশেষ মশলা দিয়ে তৈরি কাচ্চি বিরিয়ানি—
            যা প্রতিটি বিরিয়ানি প্রেমীর প্রথম পছন্দ।
          </p>
        </div>

        <div class="dish-card">
          <div class="dish-img">
            <img src="assets/dish1.png" alt="Morog Korma" />
            <span class="price">250/-</span>
          </div>
          <h3 class="dish-title">স্পেশাল মোরগ কোরমা</h3>
          <p class="dish-desc">
            নরম মুরগি, ক্রিমি সস এবং মশলার নিখুঁত সমন্বয়— একবারই খেলে অভ্যস্ত!
          </p>
        </div>

      </div>
      <br/>
      <br/>
      <br/>

  <!-- Footer -->
   <footer class="footer">
      <div class="section__container footer__container">
        <div class="footer__logo">
          <img src="assets/logo-white.png" alt="logo" />
        </div>
        <div class="footer__content">
          <p>
           কাচ্চিগঞ্জ হলো এমন একটি রেস্তোরাঁ যা মুন্সিগঞ্জের ঐতিহ্যবাহী সংস্কৃতি, 
            উৎসব এবং খাবারের স্বাদকে জীবন্ত করে তোলার উদ্দেশ্যে প্রতিষ্ঠিত। 
            এখানে শুধু খাবারই পরিবেশন করা হয় না, বরং মুন্সিগঞ্জের সোনালি অতীতের সুগন্ধী স্মৃতি 
            এবং ঐতিহ্যের রসনা তুলে ধরা হয় প্রতিটি পদে।
            কাচ্চিগঞ্জে প্রতিটি খাবার তৈরি হয় সততা ও নিখুঁত কুশলতার মাধ্যমে, 
            যেখানে স্থানীয় মশলা ও ঘরোয়া রেসিপির সমন্বয়ে গড়ে ওঠে এক অনন্য স্বাদ,
            যা শহরের ভিড়ের মধ্যে হারিয়ে যাওয়া ঐতিহ্যবাহী স্বাদের পুনর্জন্ম ঘটায়। 
            এটি এমন এক গন্তব্য যেখানে খাবার শুধু পেট ভরানোর মাধ্যম নয়, 
            বরং একটি সংস্কৃতি ও ঐতিহ্যের মহোৎসব।
          </p>
          <div>
            <ul class="footer__links">
              <li>
                <span><i class="ri-map-pin-2-fill"></i></span>
                সুখনিবাস সংলগ্ন, হাটলক্ষীগঞ্জ রোড, মুন্সীগঞ্জ সদর, মুন্সীগঞ্জ
              </li>
              <li>
                <span><i class="ri-mail-fill"></i></span>
                kacciganjbd@gmail.com
              </li>
              <li>
                <span><i class="ri-whatsapp-fill"></i></span>
                +880 1886-083961
              </li>
            </ul>
            <div class="footer__socials">
              <a href="https://www.facebook.com/share/14j6EN8jSi/?mibextid=wwXIfr" target="_blank"><i class="ri-facebook-circle-fill"></i></a>
              <a href="https://www.instagram.com/kacchiganj_munshiganj/" target="_blank"><i class="ri-instagram-fill"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="footer__bar">
        Copyright © 2025 কাচ্চিগঞ্জ. All Rights Reserved.
      </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
  </body>
</html>

