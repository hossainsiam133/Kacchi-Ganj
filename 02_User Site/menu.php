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
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"
      rel="stylesheet"
    />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
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
     <script>
      setTimeout(() => {
  document.querySelectorAll('.message').forEach(msg => {
    msg.remove();
  });
}, 3000);

    </script>
  </body>
</html>

