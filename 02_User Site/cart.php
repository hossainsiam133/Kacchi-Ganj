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
     // updating product quantity in cart
    if(isset($_POST['update_qty_btn'])){
        $update_qty_id=$_POST['update_qty_id'];
        $update_value = $_POST['update_qty'];

        $update_query = mysqli_query($conn,"UPDATE `cart` SET quantity='$update_value' WHERE id='$update_qty_id'") or die('query failed');
        if($update_query){
            header('location:cart.php');
        }
    }
    //deleting products from cart
    if(isset($_GET['delete'])){
        $delete_id= $_GET['delete'];
        mysqli_query($conn,"DELETE FROM `cart` WHERE id='$delete_id'") or die('query failed');
        header('location:cart.php');
    }
    //deleting all products from cart
    if(isset($_GET['delete_all'])){
        mysqli_query($conn,"DELETE FROM `cart` WHERE user_id='$user_id'") or die('query failed');
        header('location:cart.php');
    }
?>
<!-- <style type="text/css">
   <?php
      include 'main.css';
    ?>
</style> -->
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
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>"> 
    <title>কাচ্চি গঞ্জ</title>
    <style>
      /* Container holding delete button & total section */
.dlt,
.wishlist_total {
    width: 100%;
    text-align: center;
    margin: 20px 0;
}

/* Delete All Button */
.btn2 {
    background: #e74c3c;
    color: #fff;
    padding: 10px 18px;
    border-radius: 6px;
    font-size: 15px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

.btn2:hover {
    background: #c0392b;
}

/* Total section styling */
.wishlist_total {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 100%;
    max-width: 500px;
    margin: 25px auto;
    box-shadow: 0 4px 25px rgba(0,0,0,0.15);
}

.wishlist_total p {
    font-size: 17px;
    margin-bottom: 12px;
    font-weight: 600;
    color: #333;
}

.wishlist_total span {
    color: #f90606ff;
    font-weight: bold;
}

/* Buttons inside wishlist_total */
.btn {
    display: inline-block;
    background: #27ae60;
    color: #fff;
    padding: 10px 18px;
    margin: 8px 5px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 15px;
    font-weight: bold;
    transition: 0.3s;
}

.btn:hover {
    background: #1e874b;
}

/* Disabled Checkout Button */
.disabled {
    pointer-events: none;
    opacity: 0.4;
}
/* Quantity Input & Update Button */
.qty {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 12px;
}

.qty input[type="number"] {
    width: 55px;
    height: 36px;
    text-align: center;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 6px;
    outline: none;
    font-weight: 600;
    transition: 0.3s;
}

.qty input[type="number"]:focus {
    border-color: #27ae60;
}

/* Update Button Style */
.qty input[type="submit"] {
    background: #27ae60;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    transition: 0.3s;
}

.qty input[type="submit"]:hover {
    background: #1e874b;
}

/* Total Amount style */
.total-amt {
    margin-top: 10px;
    font-size: 15px;
    text-align: center;
    font-weight: 600;
    /* color: #333; */
    color: white;
}

.total-amt span {
    color: #06f46dff;
    font-weight: 700;
}

    </style>
  </head>
  <body>
    <header class="header">
      <?php include 'nav.php'; ?>
      <div class="section__container header__container" id="home">
        <div class="header__image">
          <img src="assets/header.png" alt="header" />
        </div>
        <div class="header__content">
          <h2>"কাচ্চিগঞ্জের বৈচিত্র্যময় মেনুতে আছে আসল স্বাদের কাচ্চি, 
                মজাদার কাবাব আর লোভনীয় সব সাইড ডিশ।"</h2>
          <h1>কার্টে যুক্ত পণ্য</h1>
        </div>
      </div>
    </header>
    <br>
    <br>
     <div class="dish-container">
         <?php
               $grand_total = 0;
               $select_products = mysqli_query($conn,"SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');
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
          <h3 style="text-align: center;" class="dish-title"><?php echo $fetch_products['name'];?></h3>
          <!-- <p class="dish-desc">
           <?php echo $fetch_products['product_details'];?>
          </p> -->
           <form method="post">
                     <input type="hidden" name="update_qty_id" value="<?php echo $fetch_products['id']; ?>">
                     <div class="qty">
                        <input type="number" min="1" name="update_qty" value="<?php echo $fetch_products['quantity']; ?>">
                        <input type="submit" name="update_qty_btn" value="update">
                    </div>
            </form>
             <div class="total-amt">
                       Total Amount : <span><?php echo $total_amt=($fetch_products['price']*$fetch_products['quantity']); ?></span>
              </div>
        </div>
         <?php
          $grand_total+=$total_amt;
           }
              }
              else
                {
                  echo '<p class="empty">no products added yet!</p>';
              }
         ?>
      </div>
       <div class="dlt">
        <a href="cart.php?delete_all" class="btn2" onclick="return confirm('do you want to delete all items in your cart')">delete all</a>
        </div>
        <div class="wishlist_total">
             <p>total amount payable : <span><?php echo $grand_total; ?>Tk /-</span></p>
             <a href="menu.php" class="btn">menu</a>
             <a href="checkout.php" class="btn <?php echo ($grand_total)?'':'disabled' ?>"> checkout</a>
        </div>

  <!-- Footer -->
   <section class="section__container contact__section" id="contact"> </section>
   <?php include 'footer.php'; ?>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="main.js"></script>
  </body>
</html>

