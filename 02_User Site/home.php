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
    <title>কাচ্চি গঞ্জ</title>
  </head>
  <body>
    <header class="header">
      <nav>
        <div class="nav__header">
          <div class="nav__logo">
            <a href="#">
              <img
                src="assets/logo-white.png"
                alt="logo"
                class="nav__logo-white"
              />
            </a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="home.php">HOME</a></li>
          <li><a href="menu.php">MENU</a></li>
          <li><a href="reservation.php">RESERVATION</a></li>
          <li><a href="contact.php">CONTACT</a></li>
          <li><a href="about.php">ABOUT</a></li>
          <li><a href="checkout.php">CHECKOUT</a></li>
          <li><a href="cart.php">CART</a></li>
          
        </ul>
      </nav>

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
      </div>

      <div class="button-group">
        <button class="btn menu-btn" onclick="Menu()">Menu</button>
        <button class="btn reserve-btn" onclick="Reserveatable()">Reserve a table</button>
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

