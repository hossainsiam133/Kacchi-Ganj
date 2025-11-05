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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link  rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"/>
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
          <h2>"ঐতিহ্যের এমন এক স্বাদ, একবার খেলেই মন ভরে যায়!
                  প্রতিটি কামড়ে অনুভব করুন জাঁকজমকের মশলার ছোঁয়া,
                  যা আপনাকে নিয়ে যাবে স্মৃতির গভীরে —
                  এক মায়াজালে বেঁধে ফেলবে চিরকাল!"</h2>
          <h1>খেল খতম<br /><span>কাচ্চি হজম</span></h1>

          <div class="button-group">
          <button class="btn menu-btn" onclick="exploreMenu()">Explore Our Menu</button>
          </div>

        </div>
      </div>
    </header>

    <section class="special-dishes">
      <h2 class="section-title">আমাদের বিশেষ ডিশসমূহ</h2>
      <p class="section-subtitle">
        কাচ্চিগাওয়ে প্রতিটি পদই তৈরি হয় খাঁটি উপকরণ এবং পরিপূর্ণ ভালোবাসার সাথে।
        আমাদের বিশেষ ডিশগুলো আপনাকে এনে দেবে এক অসাধারণ রন্ধন অভিজ্ঞতা।
      </p>

      
      <div class="dish-container">
         <?php
               $select_products = mysqli_query($conn,"SELECT * FROM `products` ORDER BY id DESC") or die('query failed');
               if(mysqli_num_rows($select_products)>0)
                {
                  while($fetch_products=mysqli_fetch_assoc($select_products))
                    {
        ?>
        <div class="dish-card">
          <div class="dish-img">
            <img src="../01_Admin Site/image/<?php echo $fetch_products['image'];?>" alt="<?php echo $fetch_products['id'];?>" />
            <span class="price"><?php echo $fetch_products['price']."/-";?></span>
          </div>
          <h3 class="dish-title"><?php echo $fetch_products['name'];?></h3>
          <p class="dish-desc">
           <?php echo $fetch_products['product_details'];?>
          </p>
        </div>
         <?php
           }
              }
              else
                {
                  echo '<p class="empty">no products added yet!</p>';
              }
         ?>
      </div>
      

      <div class="button-group">
        <button class="btn menu-btn" onclick="Menu()">Menu</button>
        <!-- <button class="btn reserve-btn" onclick="Reserveatable()">Reserve a table</button> -->
      </div>
      </div>
    </section>

      <!--   EVENT   -->
        <section class="section__container event__container" id="event">
        <div class="event__content">
        <div class="event__image">
        <img src="assets/event.png" alt="event" />
        </div>
             
        <div class="event__details">
          <h2 class="section__header">আসন্ন ইভেন্ট</h2>
          <p class="section__description">
          আসছে জিভে জল আনা “বাদশাহী কাচ্চি”!
          একবার খেলেই মন বলবে — আরো একবার!
          💰 দাম মাত্র ১৮৫০ টাকা - রাজকীয় স্বাদ এখন সবার নাগালে!
          প্রস্তুত হোন এক অনন্য স্বাদের অভিজ্ঞতার জন্য, যেখানে মিশে আছে বাংলার ঐতিহ্য আর আধুনিক রেসিপির নিখুঁত ছোঁয়া।
          বিশ্বস্ততার সঙ্গে বাছাই করা মাংস, প্রিমিয়াম বাসমতি চাল, ঘি ও ঘ্রাণময় মশলার মোহনায় তৈরি আমাদের বাদশাহী কাচ্চি প্রতিটি
          কামড়েই দেবে পরিপূর্ণ তৃপ্তি।
          এই কাচ্চি শুধুমাত্র খাবার নয়, এটি একটি অভিজাত আয়োজন - যা আপনার স্মৃতিতে জায়গা করে নেবে বহুদিন ধরে।
          বন্ধুদের আড্ডা হোক কিংবা পরিবারের সাথে বিশেষ মুহূর্ত,
          বাদশাহী কাচ্চি উপভোগ করুন - সকলের সাথে, রাজকীয় পরিবেশে।
          🥘 খুব শীঘ্রই আসছে 
          আপনার পছন্দের গঞ্জ-এ, এক ব্যতিক্রমী অভিজ্ঞতা নিয়ে।
          স্বাদে, ঘ্রাণে আর পরিবেশনায় - আমরা প্রতিশ্রুতিবদ্ধ আপনাকে দিতে সেরা কাচ্চি অভিজ্ঞতা!
          ✨ স্বাদে রাজা, নামেই বাদশাহী -
          “বাদশাহী কাচ্চি” শুধুমাত্র কাচ্চিগঞ্জ-এ!
          </p>
        </div>
      </div>
    </section>

  <!--   FOOTER   -->
   <?php include 'footer.php'; ?> 

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
  </body>
</html>

