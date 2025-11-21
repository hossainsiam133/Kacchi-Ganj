<?php
include 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location: ../01_Admin Site/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>Order History - কাচ্চি গঞ্জ</title>
    <style>
    .order-status-pending { color: orange; font-weight: 600; }
    .order-status-complete { color: green; font-weight: 600; }
        .order-card { background: #fff8f0; border-radius: 10px; box-shadow: 0 2px 12px rgba(37,99,235,0.08); padding: 18px; margin-bottom: 18px; }
    /* Small variant for inline buttons to avoid overlap */
    .btn-small {
        padding: 6px 10px !important;
        font-size: 13px !important;
        border-radius: 6px !important;
        display: inline-block !important;
        vertical-align: middle !important;
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
          <h2>"ঐতিহ্যবাহী কাচ্চি মানেই — কাচ্চিগঞ্জ এর কাচ্চি।"</h2>
          <h1>অর্ডার ইতিহাস</h1>
        </div>
      </div>
    </header>

    <main class="main-section" style="max-width:800px;margin:auto;margin-top:3rem;">
        <h1 style="margin-bottom:24px;">Your Order History</h1>
        <?php
        $orders = mysqli_query($conn, "SELECT * FROM `order` WHERE user_id='$user_id' ORDER BY placed_on DESC") or die('query failed');
        if (mysqli_num_rows($orders) > 0) {
            while ($order = mysqli_fetch_assoc($orders)) {
                // use payment_status column (values: pending, complete)
                $status = strtolower($order['payment_status'] ?? 'pending');
                $status_class = $status === 'complete' ? 'order-status-complete' : 'order-status-pending';
                echo '<div class="order-card">';
                echo '<div><b>Order ID:</b> ' . $order['id'] . '</div>';
                echo '<div><b>Date:</b> ' . htmlspecialchars($order['placed_on']) . '</div>';
                echo '<div><b>Products:</b> ' . htmlspecialchars($order['total_products']) . '</div>';
                echo '<div><b>Total:</b> ৳' . htmlspecialchars($order['total_price']) . '</div>';
                echo '<div style="margin-top:8px;">';
                echo '<a href="generate_receipt.php?order_id=' . intval($order['id']) . '" class="btn btn-small" download="Receipt_' . str_pad($order['id'],6,'0',STR_PAD_LEFT) . '.html" style="margin-right:8px;">Download Receipt</a>';
                echo '<span style="margin-left:8px;">';
                echo '<b>Payment Status:</b> <span class="' . $status_class . '">' . ucfirst($status) . '</span>';
                echo '</span>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="card">No orders found.</div>';
        }
        ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
