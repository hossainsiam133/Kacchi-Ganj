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
    if(isset($_POST['submitted'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $number = mysqli_real_escape_string($conn,$_POST['number']);
    $method = mysqli_real_escape_string($conn,$_POST['method']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $placed_on = date('d-M-Y');
    $cart_total = 0;
    $cart_product = array();
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');
    if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
        $cart_product[] = $cart_item['name'].' ('.$cart_item['quantity'].')';
        $sub_total = ($cart_item['price'] * $cart_item['quantity']);
        $cart_total += $sub_total;
      }
    }
    if(!$cart_total)
    {
      $order_message = '<div class="card" style="background:#ffe6e6;color:#d32f2f;padding:12px;margin-bottom:16px;border-radius:8px;">Your cart is empty. Please add items to your cart before placing an order.</div>';
    }
    else
    {
      $cart_total+=60; // Adding shipping cost
      $total_products = implode(', ', $cart_product);
    // Check password
    $user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id='$user_id'") or die('query failed');
    if(mysqli_num_rows($user_query) > 0){
      $user = mysqli_fetch_assoc($user_query);
      if($user['password'] === $password){
        // Password matches, place order
        $insert_result = mysqli_query($conn, "INSERT INTO `order` (`user_id`,`name`,`number`,`email`,`method`,`address`,`total_products`,`total_price`,`placed_on`) VALUES('$user_id','$name','$number','$email','$method','$address','$total_products','$cart_total','$placed_on')") or die('query failed');
        $order_id = mysqli_insert_id($conn);
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id='$user_id'") or die('query failed');
        $order_message = '<div class="card" style="background:#e6ffe6;color:#2563eb;padding:12px;margin-bottom:16px;border-radius:8px;">Order placed successfully! Your receipt will download automatically.</div>';
        $order_placed_successfully = true;
        $_SESSION['receipt_order_id'] = $order_id;
      } else {
        // Password error
        $order_message = '<div class="card" style="background:#ffe6e6;color:#d32f2f;padding:12px;margin-bottom:16px;border-radius:8px;">Password incorrect. Please try again.</div>';
      }
    } else {
      $order_message = '<div class="card" style="background:#ffe6e6;color:#d32f2f;padding:12px;margin-bottom:16px;border-radius:8px;">User not found.</div>';
    }
    }
    // $cart_total-=$cart_total;
  // $cart_check = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM `cart` WHERE user_id='$user_id'") or die('query failed');
  // $cart_count = 0;
  // if($cart_check)
  // {
  //   $cart_row = mysqli_fetch_assoc($cart_check);
  //   $cart_count = intval($cart_row['cnt']);
  // }
  // $cart_empty = ($cart_count === 0);
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
    <style>
      :root{
  --bg: #f7f9fc;
  --card: #ffffff;
  --muted: #6b7280;
  --accent: #2563eb;
  --accent-600: #1e40af;
  --radius: 10px;
  --max-width: 1100px;
  --gap: 20px;
  --container-padding: 24px;
  --shadow: 0 6px 20px rgba(15,23,42,0.06);
  --transition: 200ms ease;
}

/* Page base */
body {
  margin: 0;
  font-family: "Inter", "Segoe UI", Roboto, Arial, sans-serif;
  background: var(--bg);
  color: #0f172a;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  line-height: 1.45;
}

/* Main section container */
main.main-section {
  max-width: var(--max-width);
  margin: 40px auto;
  padding: var(--container-padding);
  box-sizing: border-box;
}

/* Grid layout */
.main-grid {
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: var(--gap);
  align-items: start;
}

/* Card */
.card {
  background: var(--card);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 20px;
  transition: transform var(--transition), box-shadow var(--transition);
}

.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(15,23,42,0.08);
}

/* Content elements */
.main-hero {
  margin-bottom: 18px;
}

.main-hero h1 {
  font-size: 1.9rem;
  margin: 0 0 8px 0;
  letter-spacing: -0.3px;
}

.main-hero p.lead {
  color: var(--muted);
  margin: 0 0 18px 0;
}

/* Article / text */
.article p {
  margin: 0 0 12px 0;
  color: #10203b;
}

/* Sidebar */
.sidebar .kicker {
  display:block;
  font-size: 0.85rem;
  color: var(--muted);
  margin-bottom: 8px;
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--accent);
  color: #fff;
  border: none;
  padding: 10px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: background var(--transition), transform var(--transition);
  text-decoration: none;
}

.btn:hover { background: var(--accent-600); transform: translateY(-2px); }
.btn.secondary {
  background: transparent;
  color: var(--accent-600);
  border: 1px solid rgba(37,99,235,0.12);
}

/* Utilities */
.row { display:flex; gap:12px; align-items:center; }
.mt-8{ margin-top:8px; } .mt-12{ margin-top:12px; } .mt-20{ margin-top:20px; }
.text-muted{ color:var(--muted); font-size:0.95rem; }

/* Reveal on scroll helper - initial state */
.reveal {
  opacity: 0;
  transform: translateY(12px);
  transition: opacity 420ms ease, transform 420ms ease;
}
.reveal.in-view {
  opacity: 1;
  transform: translateY(0);
}

/* Form elements (if you include a form in main) */
.input {
  width: 100%;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #e6eef8;
  font-size: 0.98rem;
  background: #fff;
  box-sizing: border-box;
}
.input:focus {
  outline: none;
  border-color: rgba(37,99,235,0.9);
  box-shadow: 0 6px 18px rgba(37,99,235,0.06);
}

/* Responsive */
@media (max-width: 880px) {
  .main-grid { grid-template-columns: 1fr; }
  main.main-section { margin: 20px; padding: 18px; }
  .sidebar { order: 2; }
}

/* Small touch-ups */
.small { font-size: 0.9rem; color:var(--muted); }
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
          <h2>"আপনার কাচ্চি উপভোগের জন্য অর্ডার সম্পূর্ণ করুন।"</h2>
          <h1> চেকআউট </h1>
        </div>
      </div>
    </header>
    <main class="main-section" id="main">
  <!-- Simple top navigation with mobile toggle (JS looks for .mobile-toggle and .nav) -->
  <header class="card" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
    <div style="display:flex;gap:12px;align-items:center;">
      <a href="/" class="small" style="font-weight:700;text-decoration:none;color:var(--accent);">Brand</a>
      <nav class="nav" aria-label="Main navigation" style="display:flex;gap:12px;">
        <a class="small" href="#order-summary">Overview</a>
        <a class="small" href="#help">Help</a>
      </nav>
    </div>

    <button class="mobile-toggle btn secondary" aria-expanded="false" aria-label="Toggle menu" style="display:none;">
      Menu
    </button>
  </header>

  <div class="main-grid">
    <!-- LEFT: Main content -->
    <section class="card main-content">
      <?php if(isset($order_message)) echo $order_message; ?>
      <div class="main-hero reveal">
        <h1>Finish your order</h1>
        <p class="lead text-muted">Review details and complete payment. We only need a few details to finalize your purchase.</p>
      </div>

      <article class="article">
        <!-- <h3 id="overview" class="reveal">Order summary</h3>
        <p class="reveal">Item: Kacchi Ganj Special — Quantity: 1 — Price: ৳500</p> -->

        <hr class="mt-12">

        <h3 id="checkout" class="mt-12 reveal">Customer details</h3>

        <!-- Registration / checkout form. JS validation attaches to #registerForm -->
        <form id="registerForm"  method="POST" class="mt-12">
          <div class="row" style="flex-direction:column;">
            <label class="small" for="name">Full name</label>
            <input class="input reveal" type="text" id="name" name="name" placeholder="Your full name" required>
          </div>

          <div class="row mt-8" style="flex-direction:column;">
            <label class="small" for="email">Email</label>
            <input class="input reveal" type="email" id="email" name="email" placeholder="you@example.com" required>
          </div>

          <div class="row mt-8" style="flex-direction:column;">
            <label class="small" for="mobile">Mobile</label>
            <input class="input reveal" type="tel" id="mobile" name="number" placeholder="+8801XXXXXXXXX" required>
          </div>

          <div class="row mt-8" style="flex-direction:column;">
            <label class="small" for="password">Password</label>
            <input class="input reveal" type="password" id="password" name="password" placeholder="Choose a password" required>
            <!-- <small class="text-muted mt-8">Password should be at least 2 characters.</small> -->
          </div>

          <div class="row mt-8" style="flex-direction:column;">
            <label class="small" for="address">Address</label>
            <input class="input reveal" type="text" id="address" name="address" placeholder="Your delivery address" required>
          </div>

          <div class="row mt-8" style="flex-direction:column;">
            <label class="small" for="payment">Payment Method</label>
            <select class="input reveal" id="payment" name="method" required>
              <option value="">Select payment method</option>
              <option value="cash">Cash on Delivery</option>
              <option value="card">Credit/Debit Card</option>
              <option value="bkash">bKash</option>
              <option value="nagad">Nagad</option>
            </select>
          </div>

          <div class="mt-20">
            <button type="submit" name="submitted" class="btn">Complete order</button>
            <a class="btn secondary" href="menu.php">Menu</a>
          </div>
        </form>
      </article>

      <div class="mt-20 small text-muted reveal">
        <p>By continuing you agree to our terms and privacy policy.</p>
      </div>
    </section>

    <!-- RIGHT: Sidebar -->
    <aside class="sidebar">
      <div class="card reveal">
        <span class="kicker">Delivery</span>
        <p class="small">Standard delivery — 1 Hours</p>
        <div class="mt-12">
          <a href="cart.php" class="btn" style="width:100%;">Edit details</a>
        </div>
      </div>

      <div class="card mt-12 reveal" id="order-summary">
        <span class="kicker">Summary</span>
         <?php
               $grand_total = 0;
               $shipping = 0;
               $select_products = mysqli_query($conn,"SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');
               if(mysqli_num_rows($select_products)>0)
                {
                  while($fetch_products=mysqli_fetch_assoc($select_products))
                    {
        ?>
        <span class="kicker">
                <?php
                $total_amt=($fetch_products['price']*$fetch_products['quantity']);
                $grand_total+=$total_amt;
                echo $fetch_products['name'].str_repeat("&nbsp;", 6)."— ". $fetch_products['quantity']."x".str_repeat("&nbsp;", 10)."— ". "৳".$total_amt;
                 
                 ?>
        </span>
        <?php 
          }
            }
        ?>
        <p class="small">Subtotal: <?php echo "৳".$grand_total; if($grand_total)$shipping = 60;?></p>
        <p class="small">Shipping: <?php echo "৳".$shipping?></p>
        <hr class="mt-8">
        <p style="font-weight:700;">Total: <?php echo $grand_total+$shipping?></p>
      </div>
    </aside>
  </div>

  <!-- Optional anchor target for "help" -->
  <section id="help" class="card mt-20 reveal" style="margin-top:20px;">
    <h3 class="small">Need help?</h3>
    <p class="small">Email support_KacchiGanj@gmail.com or call +8801994617025</p>
  </section>
</main>
  
  
  <!-- Footer -->
   <section class="section__container contact__section" id="contact"> </section>
   <?php include 'footer.php'; ?>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
    <script>
      (function () {
  /* Smooth scroll for same-page anchors */
  function enableSmoothScroll() {
    document.addEventListener('click', function (e) {
      const a = e.target.closest('a[href^="#"]');
      if (!a) return;
      const href = a.getAttribute('href');
      if (href === "#" || href === "#!"){ e.preventDefault(); return; }
      const targetEl = document.querySelector(href);
      if (!targetEl) return;
      e.preventDefault();
      const top = targetEl.getBoundingClientRect().top + window.pageYOffset - 20;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  }

  /* Reveal on scroll */
  function enableRevealOnScroll() {
    const els = document.querySelectorAll('.reveal');
    if (!els.length) return;
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          // optional: unobserve to improve performance
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    els.forEach(el => obs.observe(el));
  }

  /* Mobile menu toggle helper (if you have a .mobile-toggle and .nav) */
  function enableMobileToggle() {
    const toggle = document.querySelector('.mobile-toggle');
    const nav = document.querySelector('.nav');
    if (!toggle || !nav) return;
    toggle.addEventListener('click', () => {
      nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', nav.classList.contains('open'));
    });
  }

  /* Simple form validation (attach to form with id="registerForm") */
  function enableFormValidation() {
    const form = document.getElementById('registerForm');
    if (!form) return;

    function isEmail(v) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    }
    function isPhone(v) {
      // Basic validation: digits, optional +, spaces, -, parentheses
      return /^[+\d][\d\s\-().]{6,}$/.test(v);
      // return /^\+?[0-9][0-9\s\-().]{6,}$/.test(v);
    }

    form.addEventListener('submit', function (e) {
      const name = form.querySelector('[name="name"]')?.value.trim() || '';
      const email = form.querySelector('[name="email"]')?.value.trim() || '';
      const mobile = form.querySelector('[name="number"]')?.value.trim() || '';
      const password = form.querySelector('[name="password"]')?.value || '';

      let errors = [];
      if (name.length < 2) errors.push('Enter a valid name (at least 2 characters).');
      if (!isEmail(email)) errors.push('Enter a valid email address.');
      if (!isPhone(mobile)) errors.push('Enter a valid mobile number.');
      if (password.length < 2) errors.push('Password must be at least 2 characters.');

      // show errors - simple alert here; you can replace with inline UI
      if (errors.length) {
        e.preventDefault();
        alert('Please fix the following:\n- ' + errors.join('\n- '));
        return false;
      }

      // else allow submission
      return true;
    });
  }

  /* Auto-download receipt if order placed successfully */
  function triggerReceiptDownload() {
    <?php if(isset($order_placed_successfully) && $order_placed_successfully && isset($_SESSION['receipt_order_id'])): ?>
      const orderId = <?php echo intval($_SESSION['receipt_order_id']); ?>;
      // Trigger download after a small delay to allow user to see success message
      setTimeout(function() {
        const link = document.createElement('a');
        link.href = 'generate_receipt.php?order_id=' + orderId;
        link.download = 'Receipt_' + String(orderId).padStart(6, '0') + '.html';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        // Clear the session variable so it doesn't download again on refresh
        fetch('clear_receipt_session.php');
      }, 500);
    <?php endif; ?>
  }

  /* Init everything on DOMContentLoaded */
  document.addEventListener('DOMContentLoaded', function () {
    enableSmoothScroll();
    enableRevealOnScroll();
    enableMobileToggle();
    enableFormValidation();
    triggerReceiptDownload();
  });
})();
    </script>
  </body>
</html>

