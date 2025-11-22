<?php
/**
 * Landing Page for Non-Logged-In Users
 * 
 * This page showcases the restaurant and its offerings
 * with attractive marketing content and call-to-action buttons
 */
// include DB connection so we can show products on the landing page
include '02_User Site/connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"/>
     <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="02_User Site/styles.css" />
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>"> 
    <title>কাচ্চিগঞ্জ - ঐতিহ্যবাহী কাচ্চি রেস্তোরাঁ</title>
    <style>
        
        /* Landing page specific styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        /* Simple navigation for landing page */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 6%;
            background: linear-gradient(135deg, #280000 0%, #550000 100%);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .nav__logo img {
            max-width: 150px;
        }

        .nav__auth {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav__auth a {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            font-size: 14px;
        }

        .nav__login {
            color: #fff;
            border: 2px solid #d4af37;
        }

        .nav__login:hover {
            background: #d4af37;
            color: #280000;
        }

        .nav__register {
            background: linear-gradient(135deg, #d4af37 0%, #b68c25 100%);
            color: #fff;
        }

        .nav__register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212,175,55,0.3);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgba(40,0,0,0.8) 0%, rgba(85,0,0,0.8) 100%), 
                        url('02_User Site/assets/header-bg.png') center/cover no-repeat;
            color: #fff;
            padding: 100px 6% 80px;
            text-align: center;
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
            font-family: 'Bebas Neue', sans-serif;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            max-width: 600px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 14px 32px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d4af37 0%, #b68c25 100%);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(212,175,55,0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #d4af37;
            border: 2px solid #d4af37;
        }

        .btn-secondary:hover {
            background: #d4af37;
            color: #280000;
        }

        /* Features Section */
        .features {
            background: #fff;
            padding: 80px 6%;
            text-align: center;
        }

        .section-title {
            font-size: 2.5rem;
            color: #280000;
            margin-bottom: 50px;
            font-family: 'Bebas Neue', sans-serif;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            padding: 30px;
            background: #fff8f0;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(212,175,55,0.1);
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 32px rgba(212,175,55,0.2);
        }

        .feature-icon {
            font-size: 3rem;
            color: #d4af37;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            color: #280000;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #555;
            line-height: 1.6;
        }

        /* About Section */
        .about {
            background: linear-gradient(135deg, rgba(40,0,0,0.05) 0%, rgba(85,0,0,0.05) 100%);
            padding: 80px 6%;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-text h2 {
            font-size: 2.2rem;
            color: #280000;
            margin-bottom: 30px;
            font-family: 'Bebas Neue', sans-serif;
        }

        .about-text p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 20px;
            text-align: justify;
        }

        .about-image img {
            max-width: 100%;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, #280000 0%, #550000 100%);
            color: #fff;
            padding: 60px 6%;
            text-align: center;
        }

        .cta h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            font-family: 'Bebas Neue', sans-serif;
        }

        .cta p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        .landing-footer {
            background: #111;
            color: #fff;
            padding: 40px 6%;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #d4af37;
            text-decoration: none;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: #fff;
        }

        .footer-divider {
            border-top: 1px solid #333;
            padding-top: 20px;
            margin-top: 20px;
        }

        .footer-text {
            color: #999;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-hero {
                width: 100%;
                max-width: 300px;
            }

            .about-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .nav__auth {
                flex-direction: column;
                gap: 8px;
                width: 100%;
            }

            .nav__auth a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav__logo">
            <a href="index.php">
                <img src="02_User Site/assets/logo-white.png" alt="Kacchi-Ganj Logo" />
            </a>
        </div>
        <div class="nav__auth">
            <a href="01_Admin Site/login.php" class="nav__login">
                <i class="bi bi-person"></i> লগইন
            </a>
            <a href="01_Admin Site/register.php" class="nav__register">
                <i class="bi bi-person-plus"></i> নিবন্ধন
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>কাচ্চিগঞ্জে স্বাগতম</h1>
        <p>"ঐতিহ্যবাহী কাচ্চি মানেই — কাচ্চিগঞ্জ এর কাচ্চি"</p>
        <p style="font-size: 1rem; font-weight: 300;">মুন্সিগঞ্জের খাঁটি ঐতিহ্যবাহী স্বাদ আপনার দোরগোড়ায়</p>
        <div class="hero-buttons">
            <a href="01_Admin Site/login.php" class="btn-hero btn-primary">
                এখনই অর্ডার করুন
            </a>
            <a href="01_Admin Site/register.php" class="btn-hero btn-secondary">
                নতুন অ্যাকাউন্ট তৈরি করুন
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2 class="section-title">আমাদের বিশেষত্ব</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="ri-restaurant-2-fill"></i>
                </div>
                <h3>ঐতিহ্যবাহী রেসিপি</h3>
                <p>মুন্সিগঞ্জের সোনালি ঐতিহ্যবাহী কাচ্চির খাঁটি স্বাদ, প্রতিটি পদে ঐতিহ্যের ছোঁয়া।</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="ri-chef-hat-fill"></i>
                </div>
                <h3>দক্ষ শেফ</h3>
                <p>অভিজ্ঞ শেফদের দল প্রতিটি খাবার সযত্নে এবং যত্নের সাথে প্রস্তুত করে।</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="ri-shield-check-fill"></i>
                </div>
                <h3>গুণমান নিশ্চয়তা</h3>
                <p>সর্বোচ্চ মানের উপাদান এবং স্বাস্থ্যসম্মত রান্নার প্রক্রিয়া আমাদের প্রতিশ্রুতি।</p>
            </div>
        </div>
    </section>

        <!-- Product / Menu Section (embedded from home.php) -->
       <section class="dishes">
      <p class="section-subtitle">
        কাচ্চিগঞ্জে প্রতিটি পদই তৈরি হয় খাঁটি উপকরণ এবং পরিপূর্ণ স্বাদের সাথে। 
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
            <img src="01_Admin Site/image/<?php echo $fetch_products['image'];?>" alt="<?php echo $fetch_products['id'];?>" />
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
                  echo '<p class="empty">No Products Added Yet!</p>';
              }
         ?>
      </div>
  </section>
               <br>
              <br>
              <br>
<!-- video section -->
 <section class="section__container video__section" id="videos">
<p style="text-align: center;" class="section__description">
   “কাচ্চি ছাড়া ভালোবাসা আর কি?
  যেখানে প্রতিটি মণ কাচ্চির গন্ধ মিশে থাকে হৃদয়ের কোণে,
  সেখানে রসনা যেন খুঁজে পায় তার আসল স্বাদ।
  </p>
  <br>
  <div class="video__grid">    
    <div class="video__card">
      <video autoplay muted loop controls>
        <source src="02_User Site/assets/vid-3.mp4" type="video/mp4" />
        আপনার ব্রাউজার ভিডিও প্লে করতে অক্ষম।
      </video>
            </div>  
  </div>
</section>

      <!--   EVENT   -->
        <section class="section__container event__container" id="event">
        <div class="event__content">
        <div class="event__image">
        <img src="02_User Site/assets/event.png" alt="event" />
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
        <!-- About Section -->
        <section class="about">
        <div class="about-content">
            <div class="about-text">
                <h2>কাচ্চিগঞ্জ সম্পর্কে</h2>
                <p>
                    কাচ্চিগঞ্জ হলো এমন একটি রেস্তোরাঁ যা মুন্সিগঞ্জের ঐতিহ্যবাহী সংস্কৃতি, উৎসব এবং খাবারের স্বাদকে জীবন্ত করে তোলার উদ্দেশ্যে প্রতিষ্ঠিত।
                </p>
                <p>
                    এখানে শুধু খাবারই পরিবেশন করা হয় না, বরং মুন্সিগঞ্জের সোনালি অতীতের সুগন্ধী স্মৃতি এবং ঐতিহ্যের রসনা তুলে ধরা হয় প্রতিটি পদে।
                </p>
                <p>
                    কাচ্চিগঞ্জে প্রতিটি খাবার তৈরি হয় সততা ও নিখুঁত কুশলতার মাধ্যমে, যেখানে স্থানীয় মশলা ও ঘরোয়া রেসিপির সমন্বয়ে গড়ে ওঠে এক অনন্য স্বাদ।
                </p>
            </div>
            <div class="about-image">
                <img src="02_User Site/assets/header.png" alt="কাচ্চিগঞ্জ" />
            </div>
        </div>
    </section>
    <!-- CTA Section -->
    <section class="cta">
        <h2>আজই আমাদের সাথে যুক্ত হন</h2>
        <p>কাচ্চিগঞ্জের অসাধারণ খাবারের স্বাদ নিতে এখনই অ্যাকাউন্ট তৈরি করুন এবং আপনার প্রথম অর্ডার করুন।</p>
        <div class="hero-buttons">
            <a href="01_Admin Site/register.php" class="btn-hero btn-primary">
                এখনই শুরু করুন
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="01_Admin Site/login.php">লগইন</a>
                <a href="01_Admin Site/register.php">নিবন্ধন</a>
                <a href="javascript:void(0)">যোগাযোগ করুন</a>
            </div>
            <div class="footer-divider">
                <p class="footer-text">
                    © ২০২৫ কাচ্চিগঞ্জ। সর্বাধিকার সংরক্ষিত।
                </p>
                <p class="footer-text">
                    <i class="ri-map-pin-2-fill"></i> সুখনিবাস সংলগ্ন, হাটলক্ষীগঞ্জ রোড, মুন্সীগঞ্জ সদর, মুন্সীগঞ্জ
                </p>
                <p class="footer-text">
                    <i class="ri-phone-fill"></i> +880 1886-083961 | 
                    <i class="ri-mail-fill"></i> kacciganjbd@gmail.com
                </p>
            </div>
        </div>
    </footer>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
</body>
</html>
