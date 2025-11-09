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
    // adding product to cart
    if(isset($_POST['add_to_cart'])){
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price= $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];

        $cart_num = mysqli_query($conn,"SELECT * FROM `cart` WHERE `name`='$product_name' AND `user_id`='$user_id'") or die('query failed');
        if(mysqli_num_rows($cart_num)>0){
            $message12[]='product already exist in cart';
        }else{
            mysqli_query($conn,"INSERT INTO `cart` (`user_id`,`pid`,`name`,`price`,`quantity`,`image`) VALUES('$user_id','$product_id','$product_name','$product_price','$product_quantity','$product_image')") or die('query failed');
            $message12[]='product successfuly added in your cart';
        }
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
    <style>
      .add-to-cart-form {
    text-align: center;
    margin-top: 10px;
}

.add-cart-btn {
    background: #d4af37;
    color: #fff;
    border: none;
    padding: 10px 14px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 20px;
    transition: 0.3s;
}

.add-cart-btn:hover {
    background: #b68c25;
    transform: scale(1.1);
}
.message {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #2ecc71;
    color: #fff;
    padding: 14px 18px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.25);
    z-index: 99999;
    animation: fadeIn 0.4s ease forwards;
}

/* Close (X) icon */
.message i {
    cursor: pointer;
    font-size: 18px;
    transition: 0.3s;
}

.message i:hover {
    transform: scale(1.2);
    color: #e74c3c;
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}


    </style>
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
      <?php
           if(isset($message12)){
             foreach ($message12 as $message12){
              echo '
              <div class="message">
              <span>'.$message12.'</span>
              <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
              </div>
              ';
             }
           }
        ?>
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
           <form action="" method="post" class="add-to-cart-form">
                  <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                   <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                   <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                   <input type="hidden" name="product_quantity" value="1" min="1">
                   <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <button type="submit" name="add_to_cart" class="add-cart-btn">
                    <i class="bi bi-cart-fill"></i>
                  </button>
        </form>          
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
        <a href="menu.php"><button class="btn menu-btn" >Menu</button></a> 
        <!-- <button class="btn menu-btn" onclick="Menu()">Menu</button> -->
        <!-- <button class="btn reserve-btn" onclick="Reserveatable()">Reserve a table</button> -->
      </div>
      </div>
    </section>

    <!-- 🎥 video section -->

<section class="section__container video__section" id="videos">
<p class="section__description">
   “কাচ্চি ছাড়া ভালোবাসা আর কি?
  যেখানে প্রতিটি মণ কাচ্চির গন্ধ মিশে থাকে হৃদয়ের কোণে,
  সেখানে রসনা যেন খুঁজে পায় তার আসল স্বাদ।
  </p>
  <div class="video__grid">    
    <div class="video__card">
      <video autoplay muted loop controls>
        <source src="assets/vid-3.mp4" type="video/mp4" />
        আপনার ব্রাউজার ভিডিও প্লে করতে অক্ষম।
      </video>
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
          <p class="section__description" style="text-align: justify;">
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
    <script>
      setTimeout(() => {
  document.querySelectorAll('.message').forEach(msg => {
    msg.remove();
  });
}, 2000);

    </script>
  </body>
</html>

