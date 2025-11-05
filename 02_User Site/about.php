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
<html lang="bn">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"
      rel="stylesheet"
    />
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
          <h2>"ঐতিহ্যবাহী কাচ্চি মানেই — কাচ্চিগঞ্জ এর কাচ্চি।"</h2>
          <h1>আমাদের সম্পর্কে</h1>
        </div>
      </div>
    </header>

    <!-- আমাদের সম্পর্কে -->
    <section class="section__container event__container" id="event">
      <div class="event__content">
        <div class="event__image">
          <img src="assets/about-1.png" alt="event" />
        </div>
        <div class="event__details">
          <p class="section__description">
            স্বাগতম কাচ্চিগঞ্জে, যেখানে আমাদের রন্ধনযাত্রা কাচ্চির শিল্পকলার এক অনন্য নিদর্শন। খাঁটি স্বাদের প্রতি ভালোবাসা আর ঐতিহ্যের প্রতি অঙ্গীকার থেকে আমাদের পথচলা শুরু। আমরা গর্বের সঙ্গে পরিবেশন করি এমন সব কাচ্চি, যা জিভে জল আনে আর মনে রেখে যায় অনন্য অভিজ্ঞতা।
আমাদের গল্প হলো সময়ের পরীক্ষিত রেসিপি, উৎকৃষ্ট উপাদান আর সুগন্ধি মসলার নিখুঁত সমন্বয়ের। রান্নাঘর থেকে আপনার টেবিল পর্যন্ত প্রতিটি পদ একেকটি সুরেলা স্বাদের সিম্ফনি, আমাদের কারিগরির প্রতি ভালোবাসার প্রতিফলন।
কাচ্চি গঞ্জে আমরা আপনাকে আমন্ত্রণ জানাই ঐতিহ্যের স্বাদ, আতিথেয়তার উষ্ণতা আর একসাথে কাটানো আনন্দময় মুহূর্ত উপভোগ করতে। কারণ কাচ্চি শুধু খাবার নয়, এটি আমাদের আবেগ, আমাদের গল্প।
          </p>
        </div>
      </div>
    </section>

    <header class="header">
      <div class="section__container header__container" id="home">
        <div class="header__image">
          <img src="assets/chef.png" alt="header" />
        </div>
        <div class="header__content">
          <h2>কাচ্চিগঞ্জের প্রতিটি স্বাদ এবং রান্নার নিখুঁত মানের পিছনে আছেন আমাদের শেফ।  
            তিনি কেবল একজন রন্ধনশিল্পী নন, বরং একজন স্বপ্নদ্রষ্টা, 
            যিনি খাঁটি উপকরণ এবং প্রাচীন রান্নার কৌশল ব্যবহার করে প্রতিটি ডিশকে এক অনন্য অভিজ্ঞতায় রূপান্তর করেন।"</h2>
            <br>

          <h1>আমাদের শেফ</h1>
        </div>
      </div>
    </header>

    <section class="section__container event__container" id="event">
      <div class="event__content">
        <div class="event__image">
          <img src="assets/founder.png" alt="event" />
        </div>
        <div class="event__details">
          <h2 class="section__header"></h2>
          <p class="section__description">
            ️“ছোটবেলা থেকেই আমার স্বপ্ন ছিল নিজের কিছু করার, নিজের ভাবনা বাস্তবায়ন করার। আমি চেয়েছিলাম এমন কিছু তৈরি করতে, যা শুধু খাবার নয় – মানুষের কাছে হয়ে উঠবে একটি অভিজ্ঞতা।
            সেই ভাবনা থেকেই জন্ম নেয় ‘কাচ্চিগঞ্জ’।  আমি চেয়েছিলাম ব্র্যান্ডের নামের সাথে ‘কাচ্চি’ শব্দটি থাকুক, কারণ এই নামেই আছে আমাদের ঐতিহ্যের গন্ধ। আর ‘গঞ্জ’ মানে মিলনস্থল – যেখানে মানুষ একসাথে হবে, খাবার ভাগাভাগি করবে, আনন্দ করবে।
            আজ ‘কাচ্চিগঞ্জ’ শুধু একটি রেস্টুরেন্ট নয়, এটি একটি পরিবার। এখানে আমরা খাবারের সাথে সাথে দিচ্ছি বিশ্বাস, যত্ন আর ভালোবাসা। মানুষের হাসিই আমাদের সবচেয়ে বড় অর্জন।”
          <br>
          <b>— ✍️ আতিফ হাসান  </b>
          <br>
          প্রতিষ্ঠাতা, কাচ্চিগঞ্জ
          </p>
        </div>
      </div>
    </section>

  

    <!-- Footer -->
    <section class="section__container contact__section" id="contact"></section>
    <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
  </body>
</html>
