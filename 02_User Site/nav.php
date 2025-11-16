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
      /* Navigation layout fix */
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  padding: 20px 6%;
}

/* Fix nav header container */
.nav__header {
  display: flex;
  align-items: center;
  gap: 30px;
}

/* Align nav links and icons properly */
.nav__links {
  display: flex;
  gap: 35px;
  margin-left: auto;
  margin-right: 50px;
}

/* Icons Row */
.icons {
  display: flex;
  align-items: center;
  gap: 18px;
  font-size: 24px;
  cursor: pointer;
  color: #fff;
}

/* Cart notification badge */
.icons sup {
  font-size: 12px;
  background: #fff;
  color: #b00000;
  padding: 2px 6px;
  border-radius: 50%;
}

/* ✅ User-box dropdown */
.user-box {
  position: absolute;
  top: 80px;
  right: 40px;
  background: #ffffff;
  padding: 15px;
  width: 220px;
  border-radius: 8px;
  display: none;
  box-shadow: 0px 5px 20px rgba(0,0,0,0.25);
  font-size: 14px;
  z-index: 999999;
}

.user-box.active {
  display: block;
}

/* Improve user box readability */
.user-box p, .user-box span {
  color: #111;
  margin: 6px 0;
}

/* Stylish Logout/Login Button */
.logout-btn, .login-btn {
  background: crimson;
  color: #fff;
  padding: 8px 12px;
  width: 100%;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 10px;
  font-weight: bold;
  text-align: center;
}

/* Hide mobile menu icon on desktop */
#menu-btn {
  display: none;
}

    </style>
    <title>কাচ্চি গঞ্জ</title>
  </head>
  <body>
    <nav>
        <div class="nav__header">
          <div class="nav__logo">
            <a href="home.php">
              <img src="assets/logo-white.png" alt="logo" class="nav__logo-white"/>
            </a>
          </div>          
        </div>

        <ul class="nav__links" id="nav-links">
          <li><a href="home.php">HOME</a></li>
          <li><a href="menu.php">MENU</a></li>
          <li><a href="about.php">ABOUT</a></li>
          <li><a href="messages.php">CONTACT</a></li>
        </ul>
        <div class="icons">
            <?php 
            $select_cart = mysqli_query($conn,"SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');
            $cart_num_rows = mysqli_num_rows($select_cart);
            ?>
            <a href="cart.php"><i class="bi bi-cart"></i><sup><?php echo $cart_num_rows; ?></sup></a>
            <i class="bi bi-list" id="menu-btn"></i>
            <i class="bi bi-person" id="user-btn"></i>
          </div>
        <div class="user-box">
          <?php if(isset($_SESSION['user_id'])): ?>
              <p><b>Username : </b><span><?php echo $_SESSION['user_name']; ?></span></p>
              <p><b>Email : </b><span><?php echo $_SESSION['user_email']; ?></span></p>
              <form method="post">
                <button type="submit" name="logout" class="logout-btn">Log out</button>
              </form>
          <?php else: ?>
              <p><b>Welcome, Guest!</b></p>
              <a href="login.php" class="login-btn">Login</a>
          <?php endif; ?>
        </div>
      </nav>
    <script>
      let userBox = document.querySelector('.user-box');
let userBtn = document.querySelector('#user-btn');
let navLinks = document.querySelector('#nav-links');
let menuBtn = document.querySelector('#menu-btn');

userBtn.addEventListener('click', () => {
  userBox.classList.toggle('active');
  navLinks.classList.remove('active');
});

menuBtn.addEventListener('click', () => {
  navLinks.classList.toggle('active');
  userBox.classList.remove('active');
});

    </script>
  </body>
</html>

